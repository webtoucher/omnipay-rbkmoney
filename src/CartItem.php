<?php

namespace Omnipay\RbkMoney;

class CartItem
{
    /**
     * @var string
     */
    public $product;

    /**
     * @var float
     */
    private $price;

    /**
     * @var int
     */
    private $quantity = 1;

    /**
     * @var array|null
     */
    private $taxMode;

    /**
     * @var Cart
     */
    private $cart;

    public function __construct($product, $price, $quantity = 1, $taxMode = null)
    {
        $this->product = $product;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->setTaxMode($taxMode);
    }

    /**
     * Set price of one product in the item.
     *
     * @param float $value
     */
    public function setPrice($value)
    {
        $this->price = $value;
        $this->recalculate();
    }

    /**
     * Set quantity of products.
     *
     * @param int $value
     */
    public function setQuantity($value)
    {
        $this->quantity = $value;
        $this->recalculate();
    }

    /**
     * Get total cost of products in the item.
     *
     * @return float
     */
    public function getCost()
    {
        return round($this->price * $this->quantity, 2);
    }

    /**
     * Get total cost of products in the item.
     *
     * @return int
     */
    public function getCostInteger()
    {
        return (int) round($this->getCost() * 100);
    }

    /**
     * Get price of one product in the item.
     *
     * @return int
     */
    public function getPriceInteger()
    {
        return (int) round($this->price * 100);
    }

    /**
     * Get tax mode.
     *
     * @return array|null
     */
    public function getTaxMode()
    {
        return $this->taxMode;
    }

    /**
     * Set tax mode.
     *
     * @param array|int|null $value
     */
    public function setTaxMode($value)
    {
        if (is_int($value)) {
            $this->taxMode = [
                'type' => 'InvoiceLineTaxVAT',
                'rate' => "$value%",
            ];
        } else {
            $this->taxMode = $value;
        }
    }

    /**
     * Set the cart in which the item is located.
     *
     * @param Cart $cart
     */
    public function setCart(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Get data as array.
     *
     * @return array|null
     */
    public function toArray()
    {
        return array_filter([
            'product' => $this->product,
            'price' => $this->getPriceInteger(),
            'quantity' => $this->quantity,
            'taxMode' => $this->getTaxMode(),
        ]);
    }

    /**
     * Recalculate total cost.
     *
     * @return void
     */
    public function recalculate()
    {
        if ($this->cart) {
            $this->cart->recalculate();
        }
    }
}
