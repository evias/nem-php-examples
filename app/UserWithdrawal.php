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

class UserWithdrawal 
    extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'nonce',
        'address',
        'reference',
        'mosaic_fqmn',
        'amount',
        'tx_hash',
        'broadcast_height',
    ];

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = "nem_user_withdrawals";

    /**
     * The automatically casted fields
     * 
     * @var array
     */
    protected $casts = [
        "nonce" => "integer",
        "amount" => "integer",
        "broadcast_height" => "integer",
    ];

    /**
     * Retrieve the associated `users` entry
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'user_id');
    }
}
