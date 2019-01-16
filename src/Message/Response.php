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
        return $this->getCode() !== 200;
    }

    public function getCode()
    {
        return $this->statusCode;
    }
}
