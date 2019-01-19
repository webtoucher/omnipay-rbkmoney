<?php

namespace Omnipay\RbkMoney\Message\v2;

use Omnipay\RbkMoney\Cart;

class CreateInvoiceRequest extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    public function getEndpoint()
    {
        return "{$this->getBaseEndpoint()}/processing/invoices";
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function getData()
    {
        return array_filter([
            'shopID' => $this->getShopId(),
            'dueDate' => $this->getDueDate(),
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrency(),
            'product' => $this->getProduct(),
            'description' => $this->getDescription(),
            'cart' => $this->getCartArray(),
            'metadata' => [
                'order_id' => $this->getTransactionId(),
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function createResponse($data, $statusCode)
    {
        return $this->response = new CreateInvoiceResponse($this, $data, $statusCode);
    }

    /**
     * Get due date.
     *
     * @return string
     * @throws \Exception
     */
    public function getDueDate()
    {
        return $this->getParameter('dueDate') ?: (new \DateTime('+1 hour'))->format(\DateTime::ATOM);
    }

    /**
     * Set due date.
     *
     * @param string $value
     * @return $this
     */
    public function setDueDate($value)
    {
        return $this->setParameter('dueDate', $value);
    }

    /**
     * Get product name.
     *
     * @return string
     */
    public function getProduct()
    {
        return $this->getParameter('product');
    }

    /**
     * Set product name.
     *
     * @param string $value
     * @return $this
     */
    public function setProduct($value)
    {
        return $this->setParameter('product', $value);
    }

    /**
     * Get cart contents.
     *
     * @return Cart
     */
    public function getCart()
    {
        return $this->getParameter('cart');
    }

    /**
     * Get cart contents.
     *
     * @return array|null
     */
    public function getCartArray()
    {
        if (!$cart = $this->getCart()) {
            return null;
        }
        return $cart->toArray();
    }

    /**
     * Set cart contents.
     *
     * @param Cart $cart
     * @return $this
     */
    public function setCart(Cart $cart)
    {
        $cart->setRequest($this);
        return $this->setParameter('cart', $cart);
    }
}
