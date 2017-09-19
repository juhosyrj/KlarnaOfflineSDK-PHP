<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload
use Klarna\KlarnaSMSOrder;
use Klarna\Entities\Cart;
use Klarna\Entities\ServerMode;
use Klarna\Entities\MerchantConfig;

$cart = new Cart();
$config = new MerchantConfig("Merchant_ID","Shared_SECRET","SEK","SE","sv-se",ServerMode::TEST);
$t = new KlarnaSMSOrder($config,$cart,"PHONE","Terminal","Reference");

$amount = 100;
$vat = 2500;
$description = "Test refund";

$t->Refund("INVOICE_NUMBER", $amount, $vat, "Terminal", $description);
