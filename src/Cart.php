<?php

namespace Omnipay\RbkMoney;

use Omnipay\RbkMoney\Message\AbstractRequest;

class Cart
{
    /**
     * @var CartItem[]
     */
    private $items = [];

    /**
     * @var float
     */
    private $amount = 0;

    /**
     * @var AbstractRequest
     */
    private $request;

    /**
     * Add item to the cart.
     *
     * @param CartItem $item
     */
    public function addItem(CartItem $item)
    {
        $item->setCart($this);
        $this->items[] = $item;
        $this->recalculate();
    }

    /**
     * Get total cost of all products in the cart.
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Recalculate total cost.
     *
     * @return void
     */
    public function recalculate()
    {
        $this->amount = 0;
        foreach ($this->items as $item) {
            $this->amount = round($this->amount + $item->getCost(), 2);
        }
        if ($this->request) {
            $this->request->setAmount($this->amount);
        }
    }

    /**
     * Set the invoice request in which the cart is located.
     *
     * @param AbstractRequest $request
     */
    public function setRequest(AbstractRequest $request)
    {
        $this->request = $request;
        $this->request->setAmount($this->amount);
    }

    /**
     * Get data as array.
     *
     * @return array|null
     */
    public function toArray()
    {
        if (!$this->items) {
            return null;
        }
        return array_map(function (CartItem $item) {
            return $item->toArray();
        }, $this->items);
    }
}
