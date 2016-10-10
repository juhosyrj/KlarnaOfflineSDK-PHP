<?php
/**
 * Created by PhpStorm.
 * User: mattias.nording
 * Date: 2016-10-05
 * Time: 16:15
 */

namespace Klarna\Entities;


class CartRow
{
    public $name;
    public $reference;
    public $unit_price;
    public $quantity;
    public $tax_rate;
    function __construct($name,$ref,$price,$quantity,$taxrate)
    {
        if($price <= 99)
        {
            throw new \Exception("Price must be atleast 100 cents");
        }
        if($taxrate > 0 && $taxrate < 100)
        {
            throw new \Exception("Taxrate must be 0 or atleast 1%");
        }
        $this->name = $name;
        $this->reference = $ref;
        $this->unit_price = $price;
        $this->quantity = $quantity;
        $this->tax_rate = $taxrate;
    }
}