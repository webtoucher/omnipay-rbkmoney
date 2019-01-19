<?php

namespace Omnipay\RbkMoney\Message\v2;

use Omnipay\RbkMoney\Message\Response as BaseResponse;

class CreateInvoiceResponse extends BaseResponse
{
    /**
     * @inheritdoc
     */
    public function getData()
    {
        return [
            'invoiceId' => $this->data['invoice']['id'],
            'invoiceAccessToken' => $this->data['invoiceAccessToken']['payload'],
        ];
    }
}
