<?php

namespace Omnipay\RbkMoney\Message\v2;

use Omnipay\RbkMoney\Message\Response as BaseResponse;

class CreatePaymentResponse extends BaseResponse
{
    /**
     * @inheritdoc
     */
    public function getData()
    {
        return [
            'id' => $this->data['id'],
            'invoiceId' => $this->data['invoiceID'],
            'makeRecurrent' => $this->data['makeRecurrent'],
        ];
    }
}
