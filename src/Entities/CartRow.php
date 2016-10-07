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
        $this->name = $name;
        $this->reference = $ref;
        $this->unit_price = $price;
        $this->quantity = $quantity;
        $this->tax_rate = $taxrate;
    }
}