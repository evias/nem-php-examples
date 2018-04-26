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

class UserDeposit 
    extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address_id',
        'user_id',
        'reference',
        'nonce',
        'mosaic_fqmn',
        'awaited_amount',
        'paid_amount',
        'pending_amount',
        'creation_height',
        'is_paid',
        'paid_height',
    ];

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = "nem_user_deposits";

    /**
     * The automatically casted fields
     * 
     * @var array
     */
    protected $casts = [
        "nonce" => "integer",
        "awaited_amount" => "integer",
        "paid_amount" => "integer",
        "pending_amount" => "integer",
        "is_paid" => "boolean",
        "creation_height" => "integer",
        "paid_height" => "integer",
    ];

    /**
     * Retrieve the associated `watch_addresses` entry
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function address()
    {
        return $this->belongsTo('App\WatchAddress');
    }

    /**
     * Retrieve the associated `users` entry
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Retrieve the associated `known_mosaics` entry
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function mosaic()
    {
        return $this->hasOne('App\KnownMosaic', 'fqmn', 'mosaic_fqmn');
    }
}
