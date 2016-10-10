<?php
/**
 * Created by PhpStorm.
 * User: mattias.nording
 * Date: 2016-10-07
 * Time: 09:56
 */
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload
use Klarna\KlarnaSMSOrder;
use Klarna\Entities\Cart;
use Klarna\Entities\ServerMode;
use Klarna\Entities\MerchantConfig;
$cart = new Cart();
$item = new \Klarna\Entities\CartRow("test","testref",2000,2,25);
$cart->AddProduct($item);


$config = new MerchantConfig("Merchant_ID","Shared_SECRET","SEK","SE","sv-se",ServerMode::TEST);
$t = new KlarnaSMSOrder($config,$cart,"PHONE","Terminal","Reference");
$t->Create();
//polling for status
while($t->GetOrderInformation() == null)
{
    $t->PollStatus(30); // killing request every 30 seconds
}
// When polling completed then you will have order-data
$order = $t->GetOrderInformation();