<?php
/**
 * Created by PhpStorm.
 * User: mattias.nording
 * Date: 2016-10-05
 * Time: 16:15
 */

namespace Klarna\Entities;


class Cart
{
    public $items = array();
    public function AddProduct(CartRow $item)
    {
        if($item->unit_price > 0)
        {
            $this->items[] = $item;
        }
        else{
            throw new Exception("Cannot add negative amount for product");
        }
    }
    public function AddDiscount(CartRow $discount)
    {
        if($discount->unit_price < 0)
        {
            $this->items[] = $discount;
        }
        else{
            throw new Exception("Cannot add positive amount for discount");
        }
    }
}