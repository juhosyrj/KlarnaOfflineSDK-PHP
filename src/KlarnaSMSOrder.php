<?php
/**
 * Created by PhpStorm.
 * User: mattias.nording
 * Date: 2016-10-05
 * Time: 16:00
 */
namespace Klarna;
use Klarna\Entities\MerchantConfig;
use Klarna\Entities\Cart;
use Klarna\Entities\ServerMode;
class KlarnaSMSOrder
{
    private $order;
    private $config;
    private $result;
    private $statusUrl;
    private $headers;
    private $order_information;
    function __construct(MerchantConfig $config, Cart $cart, $phone, $terminal_id, $merchant_reference1,$postback  = null){
        $this->order = array();
        $this->order["locale"] = $config->locale;
        $this->order["purchase_country"] = $config->purchase_country;
        $this->order["purchase_currency"] = $config->purchase_currency;
        $this->order["mobile_no"]= $phone;
        $this->order["order_lines"]= $cart->items;
        $this->config = $config;
        if($postback !== null) // ange postback som variabel i det första för att testa postbacklösning.
        {
            if(strpos($postback,'https://') === false)
            {
                throw new Error("Postback URL must be HTTPS");
            }
            $this->order["postback_uri"] = $postback;
        }
        $this->headers = array(
            'Content-Type:application/json',
            'Authorization: Basic '.$this->config->auth
        );
    }
    function Create(){
        $url = $this->config->enviournment.'/v1/'.$this->config->eid.'/orders';
//open connection
        $ch = curl_init();
//set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($this->order));
        if($this->config->servermode === ServerMode::TEST)
        {
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER	 ,false );
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER,  $this->headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//execute post
        $result = curl_exec($ch);
        if($result === false)
        {
            echo 'Curl error: ' . curl_error($ch);
        }
        else
        {
            $this->result = json_decode($result);
            if(isset($this->result->status_uri))
            {
                $this->statusUrl = $this->result->status_uri;
            }

        }
//close connection
        curl_close($ch);
    }

    public function PollStatus($secondsTimeout){
        if($secondsTimeout < 30)
        {
            throw new \InvalidArgumentException("Timeout needs to be longer than 30 seconds");
        }
        if($this->statusUrl == "")
        {
            throw new \Exception("Cannot poll an order that is pushing status.");
        }
        set_time_limit(0);
        $ch = curl_init();
//set the url, number of POST vars, POST data CURLOPT_HTTPGET
        curl_setopt($ch,CURLOPT_HTTPGET, true);
        curl_setopt($ch,CURLOPT_URL, $this->statusUrl);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER	 ,false ); //   #### REMOVE BEFORE LIVE ### ONLY FOR TESTING 	marie.andersson@junkyard.se
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,$secondsTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $secondsTimeout); //timeout in seconds
        curl_setopt($ch, CURLOPT_HTTPHEADER,  $this->headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//execute post
        $result = curl_exec($ch);
        $this->order_information = json_decode($result);
    }
    public function GetOrderInformation()
    {
        return $this->order_information;
    }
    public function Cancel()
    {
        if($this->result == null)
        {
            throw  new \Exception("Cannot cancel order that have not been created");
        }
        $url = $this->config->enviournment.'/v1/'.$this->config->eid.'/orders/'.$this->result->id.'/cancel';

//open connection
        $ch = curl_init();
//set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER	 ,false ); //   #### REMOVE BEFORE LIVE ### ONLY FOR TESTING 	marie.andersson@junkyard.se
        curl_setopt($ch, CURLOPT_HTTPHEADER,  $this->headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $headers  = curl_getinfo($ch);
        if($result === false)
        {
            echo 'Curl error: ' . curl_error($ch);
        }
        if($headers["http_code"] != 204)
        {

           echo "fail";
        }
//close connection
        curl_close($ch);
    }
}