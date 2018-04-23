<?php
/**
 * Part of the evias/nem-php-examples package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under MIT License.
 *
 * This source file is subject to the MIT License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    evias/nem-php-examples
 * @version    1.0.0
 * @author     Grégory Saive <greg@evias.be>
 * @license    MIT License
 * @copyright  (c) 2017-2018, Grégory Saive <greg@evias.be>
 * @link       http://github.com/evias/nem-php-examples
 */
namespace App\Console;

use Illuminate\Console\Command;
use NEM\Core\KeyPair;
use NEM\Models\Message;
use NEM\Models\Transaction\Transfer;
use NEM\SDK;
use NEM\Infrastructure\Account as AccountService;
use NEM\Infrastructure\Network as NetworkService;
use App\Blockchain\ConnectionPool;
use Invoice;

use Exception;
use RuntimeException;
use InvalidArgumentException;

class ProcessorDeposits 
    extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'processor:deposits
                       {--N|network=mainnet : Define a NEM Network name (or ID). Defaults to Mainnet.}
                       {--A|address= : Define a NEM Address for which you wish to observe Payments.}
                       {--p|prefix= : (Optional) Define a Payment Processing message Prefix.}
                       {--c|currency= : (Optional) Define a custom NEM Fully Qualified Mosaic Name (Ex. : `nem:xem`, `dim:coin`, etc.).}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will go through all received deposits. You can define a custom message if needed.';

    /**
     * List of multiple addresse to listen for Payments.
     * 
     * @var array
     */
    protected $listenAddressList = [];

    /**
     * List of multiple addresse to listen for Payments.
     * 
     * @var array
     */
    protected $currencies = [
        "nem:xem",
        "dim:coin",
        "dim:token",
    ];

    /**
     * List of arguments passed to this command instance.
     * 
     * @var array
     */
    protected $arguments = [];

    /**
     * Handle command line arguments
     *
     * @return array
     */
    public function setUp()
    {
        $our_opts = ["prefix" => null, "network" => null, "address" => null, "currency" => null];

        // parse command line arguments.
        $options  = array_intersect_key($this->option(), $our_opts);

        if (!in_array(strtolower($options["network"]), ["mainnet", "testnet", "mijin"])) {
            $options["network"] = "mainnet";
        }

        if (!empty($options["address"])) {
            // determine network by address (address prevails)
            $options["network"] = Network::fromAddress($options["address"]);
            array_push($this->listenAddressList, $options["address"]);
        }

        if (!empty($options["currency"])) {
            $currencies = explode(",", $options["currency"]);
            $this->currencies = array_merge($this->currencies, $currency);
        }

        // store arguments
        $this->arguments = $options;
        return $this->arguments;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->setUp();

        while (!empty($this->listenAddressList)) {

            $currentAddress = array_shift($this->listenAddressList);
            $this->readTransactionsForAddress($currentAddress);
        }
    }

    /**
     * Read batches of transactions for the given `address` NEM Wallet
     * Address.
     * 
     * This will read *all* transactions available on the said NEM Wallet.
     * 
     * @param   string  $address
     * @return  
     */
    public function readTransactionsForAddress($address)
    {
        // shortcuts
        $network = $this->arguments["network"];
        $pool = new ConnectionPool($network);
        $api = $pool->getEndpoint();

        $cntTrxes     = 0;
        $pageSize     = 25;
        $result       = false;

        //XXX evaluate endpoint heartbeat (->status())

        do {
            try {
                $uri   = "account/transfers/incoming?" . http_build_query(["address" => $multisigAddr]);
                $json  = $api->getJSON($uri);
                $trxes = json_decode($json, true);
                $trxes = $trxes["data"];

                $cntTrxes = count($trxes);
                $result   = $this->processTransactions($trxes);
            }
            catch(RequestException $e) {
                //XXX E_NETUNREACH, E_NOTCONNECTED, E_CONNREFUSED
                echo "[ERROR][REQUEST] " . $e->getMessage(), PHP_EOL;
            }
            catch(Exception $e) {
                //XXX FATAL
                //  `400 Bad Request` response:
                // {"timeStamp":92405140,"error":"Bad Request","message":"encoded address cannot be null","status":400
                echo "[ERROR][UNKNOWN] " . $e->getMessage(), PHP_EOL;
            }
        }
        while ($cntTrxes == $pageSize && $result === true);
    }

    /**
     * Helper method to process a set of NEM Transactions.
     * 
     * @param   array       $transactions
     * @return  boolean     Whether to continue (true) fetching transactions or to stop (false).
     */
    public function processTransactions(array $transactions)
    {
        for ($i = 0, $c = count($transactions); $i < $c; $i++) {
            $txHash = $transactions[$i]["meta"]["hash"]["data"];

            // read transaction content
            $txMeta = $transactions[$i]["meta"];
            $txData = $transactions[$i]["transaction"];

            // handle multisig
            $realData = $txData["type"] === 4100 ? $txData["otherTrans"] : $txData;

            // only transfers
            if ($realData["type"] !== 257) {
                continue;
            }

            // only with message
            if (empty($realData["message"]["payload"])) {
                continue;
            }

            $transfer = new Transfer($realData);
            $message  = $transfer->message()->toPlain();
            $exists = Invoice::where("tx_hash", $txHash)->first();

            if ($exists !== null) // transaction was read before
                return false;

            // multi payments possible
            $byInvoice = Invoice::where("number", $message)->first();
            $xemAmount = $this->extractAmount($realData, "nem:xem");

            if ($byInvoice === null) {
                // save invoice
                $invoice = new Invoice([
                    "tx_hash" => $txHash,
                    "number"  => $message,
                ]);
            }
            else {
                $invoice = $byInvoice;
                $invoice->amount_paid = $invoice->amount_paid + $xemAmount;
            }
        }

        return true;
    }

    /**
     * Helper to extract amounts of the given `mosaic` in a said
     * NEM transaction.
     * 
     * @param   array   $transaction
     * @param   string  $mosaic
     * @return  integer
     */
    public function extractAmount(array $transaction, $mosaic)
    {
        if (empty($transaction["mosaics"])) {
            // only XEM available.
            return $transaction["amount"];
        }

        $mosaicAmount = 0;
        for ($i = 0, $n = count($transaction["mosaics"]); $i < $n; $i++) {
            
        }
    }
}
