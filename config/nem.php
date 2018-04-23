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

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mainnet Configuration
    |--------------------------------------------------------------------------
    |
    | Define NEM Nodes to connect to when using Mainnet.
    |
    | This list is used in the connection pool for whenever any of those nodes
    | is down, it should be able to retrieve data from different nodes.
    |
    */
    'mainnet' => [
        "nodes" => [
            "http://hugealice.nem.ninja:7890/",
            "http://alice1.nem.ninja:7890/",
            "http://alice2.nem.ninja:7890/",
            "http://alice3.nem.ninja:7890/",
            "http://alice4.nem.ninja:7890/",
            "http://alice5.nem.ninja:7890/",
            "http://alice6.nem.ninja:7890/",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Testnet Configuration
    |--------------------------------------------------------------------------
    |
    | Define NEM Nodes to connect to when using Testnet.
    |
    | This list is used in the connection pool for whenever any of those nodes
    | is down, it should be able to retrieve data from different nodes.
    |
    */
    'testnet' => [
        "nodes" => [
            "http://bigalice2.nem.ninja:7890/",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Mijin Configuration
    |--------------------------------------------------------------------------
    |
    | Define NEM Nodes to connect to when using Mijin.
    |
    | This list is used in the connection pool for whenever any of those nodes
    | is down, it should be able to retrieve data from different nodes.
    |
    */
    'Mijin' => [
        "nodes" => [],
    ],
];
