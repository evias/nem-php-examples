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
use App\KnownMosaic;

use DB;
use Exception;
use RuntimeException;
use InvalidArgumentException;

class CreateNewMosaic
    extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'mosaics:new
                       {--N|namespace= : Define the mosaic namespace.}
                       {--m|mosaic= : Define the mosaic name.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will add a known_mosaics entry for the said currency.';

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
        $our_opts = ["namespace" => null, "mosaic" => null];

        // parse command line arguments.
        $options  = array_intersect_key($this->option(), $our_opts);

        // --namespace and --mosaic cannot be empty
        if (empty($options["namespace"]) || empty($options["mosaic"])) {
            return false;
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
        $flag = $this->setUp();
        if ($flag === false) {
            $this->error("Missing mandatory parameter --namespace or --mosaic.");
            return ;
        }

        $fqmn = sprintf("%s:%s", $this->arguments["namespace"], $this->arguments["mosaic"]);
        $user = KnownMosaic::create([
            "namespace" => $this->arguments["namespace"],
            "mosaic_name" => $this->arguments["mosaic"],
            "fqmn" => $fqmn,
        ]);

        // Job done.
        return ;
    }

}
