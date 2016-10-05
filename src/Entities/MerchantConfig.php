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

    private $purchase_currency;
    private $purchase_country;
    private $shared_secret;
    private $eid;
    private $locale;
    private $enviournment;
    private $auth;
    public function __construct($eid,$secret,$currency,$country,$locale,$livemode)
    {
        $this->eid = $eid;
        $this->shared_secret = $secret;
        $this->purchase_country = $country;
        $this->purchase_currency = $currency;
        $this->locale = $locale;
        $this->enviournment = $livemode === true ? "buy.klarna.com" : "buy.playground.klarna.com";
        $creator = new DigestCreator();
        $this->auth = $creator->CreateOfflineDigest($this->eid,$this->shared_secret);
    }
}