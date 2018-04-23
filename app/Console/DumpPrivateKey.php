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

// BIP32/BIP39 dependencies
use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39SeedGenerator;
use BitWasp\Bitcoin\Mnemonic\MnemonicFactory;
use BitWasp\Bitcoin\Key\Deterministic\HierarchicalKeyFactory;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Buffertools\Buffer;

use NEM\Core\KeyPair;
use App\WatchAddress;

class DumpPrivateKey 
    extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'wallet:dumpprivkey
                       {--P|path= : Define a BIP44 derivation path for the private key derivation.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will display the private key at the said derivation path.';

    /**
     * List of arguments passed to this command instance.
     * 
     * @var array
     */
    protected $arguments = [];

    /**
     * The BIP44 path.
     *
     * @see .env.example 
     * @var string
     */
    protected $bip44_path = null;

    /**
     * Handle command line arguments
     *
     * @return array
     */
    public function setUp()
    {
        $our_opts = ["path" => null];

        // parse command line arguments.
        $options  = array_intersect_key($this->option(), $our_opts);

        $this->bip44_path = env("NEM_BIP44_PATH");
        if (!empty($options["path"])) {
            $this->bip44_path = trim($options["path"], "/");
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

        $keypair = $this->createKeyPair();
        $this->info($keypair->getPrivateKey()->getHex());

        // Job done.
        return ;
    }

    /**
     * Create a NEM KeyPair.
     * 
     * @return \NEM\Core\KeyPair
     */
    public function createKeyPair()
    {
        // if NEM_PRIVATE_KEY is set we will use this one
        $privateKey = env("NEM_PRIVATE_KEY", "");
        if (!empty($privateKey)) {
            return KeyPair::create($privateKey);
        }

        $hdNode = $this->createHDNode();

        // check path existence
        $this->bip44_path = $this->bip44_path;

        // get actual private key
        $bip44 = $hdNode->derivePath($this->bip44_path);
        $private = $bip44->getPrivateKey()->getHex();

        return KeyPair::create($private);
    }

    /**
     * Create a HD Wallet factory.
     * 
     * @return \BitWasp\Bitcoin\Key\Deterministic\HierarchicalKeyFactory
     */
    public function createHDNode()
    {
        // generate private key from APP_ENTROPY_SOURCE 
        // and APP_ENCRYPTION_SEED

        $secret = hash("sha256", env("APP_ENCRYPTION_SEED"), true); // true=raw
        $bytes  = env("APP_ENTROPY_SOURCE", "");
        if (!empty($bytes)) {
            $bytes = hex2bin($bytes);
        }
        else {
            $bytes = random_bytes(512);
        }

        // the random secret is seeded here + 512 random bytes (or APP_ENTROPY_SOURCE)
        $entropy = new Buffer(hash("sha256", $secret . $bytes, true), 32);

        // BIP39 mnemonic seed generation
        $bip39 = (new MnemonicFactory())->bip39();
        $mnemonic = $bip39->entropyToMnemonic($entropy);

        $generator = new Bip39SeedGenerator($bip39);
        $bip39Seed = $generator->getSeed($mnemonic, $secret);

        // crate HD key factory for BIP39 seed
        $bip32 = HierarchicalKeyFactory::fromEntropy($bip39Seed);

        unset($entropy,
              $bip39,
              $mnemonic,
              $bip39Seed);
        return $bip32;
    }
}
