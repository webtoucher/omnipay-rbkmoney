<?php

namespace Omnipay\RbkMoney\Message\v2;

class GetInvoiceRequest extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    public function getEndpoint()
    {
        return "{$this->getBaseEndpoint()}/processing/invoices/{$this->getId()}";
    }

    /**
     * @inheritdoc
     */
    protected function getHttpMethod()
    {
        return 'GET';
    }

    /**
     * Get invoice ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->getParameter('id');
    }

    /**
     * Set invoice ID.
     *
     * @param string $value
     * @return $this
     */
    public function setId($value)
    {
        return $this->setParameter('id', $value);
    }
}
