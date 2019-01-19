<?php

namespace Omnipay\RbkMoney\Message\v2;

class GetInvoiceEventsRequest extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    public function getEndpoint()
    {
        return "{$this->getBaseEndpoint()}/processing/invoices/{$this->getId()}/events";
    }

    /**
     * @inheritdoc
     */
    protected function getHttpMethod()
    {
        return 'GET';
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function getData()
    {
        return [
            'limit' => $this->getLimit(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function createResponse($data, $statusCode)
    {
        return $this->response = new GetInvoiceEventsResponse($this, $data, $statusCode);
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

    /**
     * Get event count limit.
     *
     * @return string
     */
    public function getLimit()
    {
        return $this->getParameter('limit') ?: 100;
    }

    /**
     * Set event count limit.
     *
     * @param string $value
     * @return $this
     */
    public function setLimit($value)
    {
        return $this->setParameter('limit', $value);
    }

    /**
     * Get last event ID.
     *
     * @return string
     */
    public function getLastEventId()
    {
        return $this->getParameter('lastEventId') ?: 0;
    }

    /**
     * Set last event ID.
     *
     * @param string $value
     * @return $this
     */
    public function setLastEventId($value)
    {
        return $this->setParameter('lastEventId', $value);
    }
}
