<?php

namespace Omnipay\RbkMoney\Message\v2;

use Omnipay\RbkMoney\Message\Response as BaseResponse;

/**
 * @property GetInvoiceEventsRequest $request
 */
class GetInvoiceEventsResponse extends BaseResponse
{
    /**
     * @inheritdoc
     */
    public function getData()
    {
        $lastEventId = $this->request->getLastEventId();
        $events = [];
        foreach ($this->data as $item) {
            if ($item['id'] > $lastEventId) {
                $lastEventId = $item['id'];
                foreach ($item['changes'] as $event) {
                    $events[] = $event;
                }
            }
        }
        return [
            'lastEventId' => $lastEventId,
            'events' => $events,
        ];
    }
}
