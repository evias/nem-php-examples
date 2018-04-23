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
namespace App\Blockchain;

use NEM\API;

class ConnectionPool
{
    /**
     * List of currently used NEM Nodes.
     * 
     * @var array
     */
    protected $nodes = [];

    /**
     * List of currently connected Endpoints.
     * 
     * @var array
     */
    protected $endpoints = [];

    /**
     * The current connection pool Index.
     * 
     * @var integer
     */
    protected $poolIndex = 0;

    /**
     * Constructor for the NEM ConnectionPool instances.
     * 
     * @param   null|string|integer     $network
     */
    public function __construct($network = "mainnet")
    {
        if (!empty($network) && is_integer($network)) {
            $netId = $network;
            $network = Network::getFromId($netId, "name");
        }
        elseif (!in_array(strtolower($network), ["mainnet", "testnet", "mijin"])) {
            $network = "mainnet";
        }

        $config = config("nem.network.config");
        $this->nodes = $config[$network]["nodes"];
        $this->endpoints = [];
    }

    /**
     * Get a connected API using the NEM node configured
     * at the current `poolIndex`
     * 
     * @param   boolean     $forceNew
     * @return  \NEM\API
     */
    public function getEndpoint($forceNew = false)
    {
        $index = $forceNew === true ? ++$this->poolIndex : $this->poolIndex;

        if (!isset($this->endpoints[$index])) {
            $api = new API([
                "use_ssl"  => false,
                "protocol" => "http",
                "host" => "bigalice2.nem.ninja",
                "port" => 7890,
                "endpoint" => "/",
            ]);

            $this->endpoints[$index] = $api;
        }

        $this->poolIndex = $index;
        return $this->endpoints[$index];
    }
}
