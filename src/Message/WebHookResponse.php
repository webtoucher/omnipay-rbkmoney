<?php

namespace Omnipay\RbkMoney\Message;

use Omnipay\Common\Message\AbstractResponse;

class WebHookResponse extends AbstractResponse
{
    protected $statusCode;

    public function isSuccessful()
    {
        return true;
    }
}
