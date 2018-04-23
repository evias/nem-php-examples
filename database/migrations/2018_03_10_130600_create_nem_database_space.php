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

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNemDatabaseSpace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nem_watch_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bip44_path');
            $table->string('public_key');
            $table->string('address');
            $table->timestamps();

            $table->engine = "InnoDB";
            $table->unique(["bip44_path", "public_key"]);
        });

        Schema::create("nem_known_mosaics", function(Blueprint $table) {
            $table->string("namespace");
            $table->string("mosaic_name");
            $table->string("fqmn");
            $table->timestamps();

            $table->engine = "InnoDB";
            $table->unique(["namespace", "mosaic_name"]);
            $table->unique("fqmn");
        });

        Schema::create('nem_user_deposits', function (Blueprint $table) {
            $table->increments('id');

            // meta
            $table->integer("address_id")->unsigned();
            $table->string("reference", 32);
            $table->integer("user_id")->unsigned();
            $table->integer('nonce')->unsigned();

            // data
            $table->string('mosaic_fqmn');
            $table->bigInteger('awaited_amount')->unsigned()->default(0);
            $table->bigInteger('paid_amount')->unsigned()->default(0);
            $table->bigInteger('pending_amount')->unsigned()->default(0);
            $table->integer('creation_height')->unsigned();

            // status
            $table->tinyInteger('is_paid')->unsigned()->default(0);
            $table->integer('paid_height')->unsigned()->nullable();
            $table->timestamps();

            $table->engine = "InnoDB";
            $table->unique(["address_id", "reference"]); // per NEM address each reference can be used once
            $table->unique(["user_id", "nonce"]);

            $table->foreign("address_id")->references("id")->on("nem_watch_addresses");
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("mosaic_fqmn")->references("fqmn")->on("nem_known_mosaics");
        });

        Schema::create('nem_user_deposit_txes', function (Blueprint $table) {
            $table->integer("deposit_id")->unsigned();

            // meta
            $table->string('hash');
            $table->integer('height')->unsigned();

            // data
            $table->bigInteger("amount")->unsigned()->default(0);
            $table->string("mosaics_json");
            $table->timestamps();

            $table->engine = "InnoDB";
            $table->unique("hash");

            $table->foreign("deposit_id")->references("id")->on("nem_user_deposits");
        });

        Schema::create('nem_user_withdrawals', function (Blueprint $table) {
            $table->increments('id');

            // meta
            $table->integer("user_id")->unsigned();
            $table->string("address", 40);
            $table->string("reference", 32)->nullable(); // for withdrawals message is optional

            // data
            $table->string('mosaic_fqmn');
            $table->bigInteger("amount")->unsigned();

            // string
            $table->string("tx_hash");
            $table->integer("broadcast_height")->unsigned();

            $table->timestamps();

            $table->engine = "InnoDB";
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("mosaic_fqmn")->references("fqmn")->on("nem_known_mosaics");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_withdrawals');
        Schema::dropIfExists('user_deposit_txes');
        Schema::dropIfExists('user_deposits');
        Schema::dropIfExists('known_mosaics');
        Schema::dropIfExists('watch_addresses');
    }
}
