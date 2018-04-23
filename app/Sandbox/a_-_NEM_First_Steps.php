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

use NEM\Core\KeyPair;
use NEM\Infrastructure\Account as AccountService;

// Example 1: Create a KeyPair *locally*
// ------------------------------------------
// This is the recommended method of creating
// new KeyPairs when working with NEM.

$localKeyPair = new KeyPair(); // random if no args

// Example 2: Let NIS create your KeyPair.
// ------------------------------------------
// This method of creating KeyPairs is discouraged
// because it lets NIS forward you a Private Key
// over the network. This is only possible on a
// locally running but still should be avoided
// where possible.

$service = new AccountService();
$remoteKeyPair = $service->generateAccount();

var_dump($localKeyPair, $remoteKeyPair);
exit();
