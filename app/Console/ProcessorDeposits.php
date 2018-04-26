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
use App\UserDeposit;
use App\UserDepositTx;
use App\WatchAddress;
use App\KnownMosaic;

use DB;
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
                       {--N|network=mainnet : Define a NEM Network name (or ID). Defaults to Mainnet.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will go through all received deposits.';

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
        $our_opts = ["network" => null,];

        // parse command line arguments.
        $options  = array_intersect_key($this->option(), $our_opts);

        if (!in_array(strtolower($options["network"]), ["mainnet", "testnet", "mijin"])) {
            $options["network"] = "mainnet";
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

        $deposits = UserDeposit::where("is_paid", false)
                               ->whereNull("paid_height")
                               ->get();

        foreach ($deposits as $deposit) :

            $this->info("Now processing open user deposit: " . $deposit->reference);
            $this->processUserDeposit($deposit);

        endforeach ;
    }

    /**
     * Read batches of transactions for the given `address` NEM Wallet
     * Address.
     * 
     * This will read *all* transactions available on the said NEM Wallet.
     * 
     * @param   \App\WatchAddress  $address
     * @return  
     */
    public function processUserDeposit(UserDeposit $deposit)
    {
        // shortcuts
        $network = $this->arguments["network"];
        $address = $deposit->address;
        $pool = new ConnectionPool($network);
        $api = $pool->getEndpoint();

        $cntTrxes     = 0;
        $pageSize     = 25;
        $result       = false;

        //XXX evaluate endpoint heartbeat (->status())

        do {
            try {
                $uri   = "account/transfers/incoming?" . http_build_query(["address" => $address->address]);
                $json  = $api->getJSON($uri);
                $trxes = json_decode($json, true);
                $trxes = $trxes["data"];

                $cntTrxes = count($trxes);
                $result   = $this->processTransactions($deposit, $trxes);
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
    public function processTransactions(UserDeposit $deposit, array $transactions)
    {
        for ($i = 0, $c = count($transactions); $i < $c; $i++) {
            $txHash = $transactions[$i]["meta"]["hash"]["data"];

            // read transaction content
            $txMeta = $transactions[$i]["meta"];
            $txData = $transactions[$i]["transaction"];
            $txBlk  = $txMeta["height"];

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
            $exists = UserDepositTx::where("hash", $txHash)->first();

            if ($exists !== null) {
                // transaction was read before
                return false;
            }
            elseif ($message != $deposit->reference) {
                // incorrect message for current deposit
                return true;
            }

            // extract the amount
            $payAmount = $this->extractAmount($realData, $deposit->mosaic);

            // invalid incoming transaction for said `deposit`
            if (false === $payAmount) {
                $payAmount = 0;
            }

            $deposit->paid_amount = $deposit->paid_amount + $payAmount;

            if ($deposit->paid_amount >= $deposit->awaited_amount) {
                $deposit->is_paid = true;
                $deposit->paid_height = $txBlk;

                //XXX event "paid"
            }

            $deposit->save();

            $tx = UserDepositTx::create([
                "deposit_id" => $deposit->id,
                "hash" => $txHash,
                "height" => $txBlk,
                "amount" => $payAmount,
                "mosaics_json" => json_encode($realData["mosaics"]),
            ]);
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
    public function extractAmount(array $transaction, KnownMosaic $mosaic)
    {
        if (empty($transaction["mosaics"]) && $mosaic->fqmn === "nem:xem") {
            // only XEM available.
            return $transaction["amount"];
        }
        elseif (empty($transaction["mosaics"])) {
            return false;
        }

        $mosaicAmount = 0;
        $attachments  = $transaction["mosaics"];
        for ($i = 0, $n = count($attachments); $i < $n; $i++) {
            $attachment = $attachments[$i];
            $attachId = $attachment["mosaicId"];
            if ($mosaic->namespace !== $attachId["namespaceId"]) {
                continue;
            }

            if ($mosaic->mosaic_name !== $attachId["name"]) {
                continue;
            }

            $mosaicAmount += (int) $attachment["quantity"];
        }

        return $mosaicAmount;
    }
}
