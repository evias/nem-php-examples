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
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDepositTx
    extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'deposit_id',
        'hash',
        'height',
        'amount',
        'mosaics_json',
    ];

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = "nem_user_deposit_txes";

    /**
     * The automatically casted fields
     * 
     * @var array
     */
    protected $casts = [
        "height" => "integer",
        "amount" => "integer",
    ];

    /**
     * Retrieve the associated `watch_addresses` entry
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function deposit()
    {
        return $this->belongsTo('App\UserDeposit', 'id', 'deposit_id');
    }
}
