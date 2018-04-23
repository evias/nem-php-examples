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
use App\User;

use DB;
use Exception;
use RuntimeException;
use InvalidArgumentException;

class CreateNewUser 
    extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'users:new
                       {--e|email= : Define the user email.}
                       {--N|name= : (Optional) Define a name for the user account.}
                       {--p|password= : (Optional) Define the password for the user account (Leave empty for random).}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will generate a new user in the database.';

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
        $our_opts = ["email" => null, "password" => null, "name" => null];

        // parse command line arguments.
        $options  = array_intersect_key($this->option(), $our_opts);

        // --email cannot be empty
        if (empty($options["email"])) {
            return false;
        }

        // default password for user creation is 32 random bytes.
        if (empty($options["password"])) {
            $options["password"] = random_bytes(32);
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
            $this->error("Missing mandatory parameter --email.");
            return ;
        }

        $user = User::create([
            "name" => empty($this->arguments["name"]) ? $this->arguments["email"] : $this->arguments["name"],
            "email" => $this->arguments["email"],
            "password" => bcrypt($this->arguments["password"]),
        ]);

        // Job done.
        return ;
    }

}
