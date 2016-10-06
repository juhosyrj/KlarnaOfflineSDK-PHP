<?php
/**
 * Created by PhpStorm.
 * User: mattias.nording
 * Date: 2016-10-05
 * Time: 16:14
 */

namespace Klarna\Entities;


use Klarna\Helpers\DigestCreator;

class MerchantConfig
{

    public $purchase_currency;
    public $purchase_country;
    public $shared_secret;
    public $eid;
    public $locale;
    public $enviournment;
    public $auth;
    public function __construct($eid,$secret,$currency,$country,$locale,$livemode)
    {
        $this->eid = $eid;
        $this->shared_secret = $secret;
        $this->purchase_country = $country;
        $this->purchase_currency = $currency;
        $this->locale = $locale;
        $this->enviournment = $livemode === true ? "https://buy.klarna.com" : "https://buy.playground.klarna.com";
        $creator = new DigestCreator();
        $this->auth = $creator->CreateOfflineDigest($this->eid,$this->shared_secret);
    }
}