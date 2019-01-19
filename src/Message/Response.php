<?php

namespace Omnipay\RbkMoney\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class Response extends AbstractResponse
{
    protected $statusCode;

    public function __construct(RequestInterface $request, $data, $statusCode = 200)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
    }

    public function isSuccessful()
    {
        return $this->getCode() < 400;
    }

    /**
     * @inheritdoc
     */
    public function getMessage()
    {
        if (isset($this->data['message'])) {
            return $this->data['message'];
        }
        if (isset($this->data['description'])) {
            return $this->data['description'];
        }
        return null;
    }

    public function getCode()
    {
        return $this->statusCode;
    }
}
