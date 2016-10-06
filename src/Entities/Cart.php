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
            $items[] = $item;
        }
    }
    public function AddDiscount(CartRow $discount)
    {
        if($discount->unit_price < 0)
        {
            $items[] = $item;
        }
    }
}