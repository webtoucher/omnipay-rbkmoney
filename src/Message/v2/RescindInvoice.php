<?php

namespace Omnipay\RbkMoney\Message\v2;

class RescindInvoice extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    public function getEndpoint()
    {
        return "{$this->getBaseEndpoint()}/processing/invoices/{$this->getId()}/rescind";
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function getData()
    {
        return array_filter([
            'reason' => $this->getDescription(),
        ]);
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
