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
$item = new \Klarna\Entities\CartRow();
$item->name = "test";
$item->quantity = 1;
$item->tax_rate = 25;
$item->unit_price = 2000;
$item->reference="test22";
$cart->AddProduct($item);

$config = new MerchantConfig("Merchant_ID","Shared_SECRET","SEK","SE","sv-se",ServerMode::TEST);
$t = new KlarnaSMSOrder($config,$cart,"PHONE","Terminal","Reference");
$t->Create();

// Polling for status
$t->PollStatus();

// When polling completed then you will have orderid
$order = $t->GetOrderInformation();