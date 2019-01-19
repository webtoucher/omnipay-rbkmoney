<?php

namespace Omnipay\RbkMoney\Message\v2;

class CreatePaymentRequest extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    public function getEndpoint()
    {
        return "{$this->getBaseEndpoint()}/processing/invoices/{$this->getId()}/payments";
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function getData()
    {
        return array_filter([
            'flow' => $this->getFlow(),
            'payer' => $this->getPayer(),
            'makeRecurrent' => $this->getMakeRecurrent(),
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function createResponse($data, $statusCode)
    {
        return $this->response = new CreatePaymentResponse($this, $data, $statusCode);
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
     * Get payment flow.
     *
     * @return array
     */
    public function getFlow()
    {
        $flow = [];
        $type = $this->getType();
        switch ($type) {
            case 'PaymentFlowHold':
                $flow['onHoldExpiration'] = $this->getOnHoldExpiration();
            case 'PaymentFlowInstant':
                $flow['type'] = $type;
        }
        return array_filter($flow) ?: null;
    }

    /**
     * Get payer.
     *
     * @return array
     */
    public function getPayer()
    {
        $payer = [];
        $type = $this->getPayerType();
        $payer['payerType'] = $type;
        switch ($type) {
            case 'PaymentResourcePayer':
                $payer['paymentToolToken'] = $this->getPaymentToolToken();
                $payer['paymentSession'] = $this->getPaymentSession();
                $payer['contactInfo'] = $this->getContactInfo();
                break;
            case 'CustomerPayer':
                $payer['customerID'] = $this->getCustomerId();
                break;
            case 'RecurrentPayer':
                $payer['contactInfo'] = $this->getContactInfo();
                $payer['recurrentParentPayment'] = $this->getRecurrentParentPayment();
        }
        return array_filter($payer, function ($value) {
            // Keep empty contact info
            return $value || $value === [];
        });
    }

    /**
     * Get customer contact info.
     *
     * @return array
     */
    public function getContactInfo()
    {
        return array_filter([
            'email' => $this->getEmail(),
            'phoneNumber' => $this->getPhoneNumber(),
        ]);
    }

    /**
     * Get recurrent parent payment.
     *
     * @return array
     */
    public function getRecurrentParentPayment()
    {
        return array_filter([
            'invoiceID' => $this->getParentInvoiceId(),
            'paymentID' => $this->getParentPaymentId(),
        ]);
    }

    /**
     * Get cash management policy.
     *
     * @return string
     */
    public function getType()
    {
        return $this->getParameter('type') ?: 'PaymentFlowInstant';
    }

    /**
     * Set payment type.
     *
     * @param string $value "PaymentFlowInstant" or "PaymentFlowHold", "PaymentFlowInstant" by default.
     * @return $this
     */
    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }

    /**
     * Get cash management policy.
     *
     * @return string
     */
    public function getOnHoldExpiration()
    {
        return $this->getParameter('onHoldExpiration');
    }

    /**
     * Set cash management policy.
     *
     * @param string $value "cancel" or "capture", "cancel" by default.
     * @return $this
     */
    public function setOnHoldExpiration($value)
    {
        return $this->setParameter('onHoldExpiration', $value);
    }

    /**
     * Get payer type.
     *
     * @return string
     */
    public function getPayerType()
    {
        return $this->getParameter('payerType') ?: 'PaymentResourcePayer';
    }

    /**
     * Set payer type.
     *
     * @param string $value "PaymentResourcePayer", "CustomerPayer" or "RecurrentPayer".
     * @return $this
     */
    public function setPayerType($value)
    {
        return $this->setParameter('payerType', $value);
    }

    /**
     * Get payment tool token.
     *
     * @return string
     */
    public function getPaymentToolToken()
    {
        return $this->getParameter('paymentToolToken');
    }

    /**
     * Set payment tool token.
     *
     * @param string $value
     * @return $this
     */
    public function setPaymentToolToken($value)
    {
        return $this->setParameter('paymentToolToken', $value);
    }

    /**
     * Get payment session.
     *
     * @return string
     */
    public function getPaymentSession()
    {
        return $this->getParameter('paymentSession');
    }

    /**
     * Set payment session.
     *
     * @param string $value
     * @return $this
     */
    public function setPaymentSession($value)
    {
        return $this->setParameter('paymentSession', $value);
    }

    /**
     * Get customer ID.
     *
     * @return string
     */
    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    /**
     * Set customer ID.
     *
     * @param string $value
     * @return $this
     */
    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    /**
     * Get customer email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Set customer email.
     *
     * @param string $value
     * @return $this
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * Get customer phone number.
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->getParameter('phoneNumber');
    }

    /**
     * Set customer phone number.
     *
     * @param string $value
     * @return $this
     */
    public function setPhoneNumber($value)
    {
        return $this->setParameter('phoneNumber', $value);
    }

    /**
     * Get parent invoice ID for recurrent payment.
     *
     * @return string
     */
    public function getParentInvoiceId()
    {
        return $this->getParameter('parentInvoiceId');
    }

    /**
     * Set parent invoice ID for recurrent payment.
     *
     * @param string $value
     * @return $this
     */
    public function setParentInvoiceId($value)
    {
        return $this->setParameter('parentInvoiceId', $value);
    }

    /**
     * Get parent payment ID for recurrent payment.
     *
     * @return string
     */
    public function getParentPaymentId()
    {
        return $this->getParameter('parentPaymentId');
    }

    /**
     * Set parent payment ID for recurrent payment.
     *
     * @param string $value
     * @return $this
     */
    public function setParentPaymentId($value)
    {
        return $this->setParameter('parentPaymentId', $value);
    }

    /**
     * Get recurrent payment creating.
     *
     * @return bool
     */
    public function getMakeRecurrent()
    {
        return $this->getParameter('makeRecurrent');
    }

    /**
     * Set recurrent payment creating.
     *
     * @param bool $value
     * @return $this
     */
    public function setMakeRecurrent($value)
    {
        return $this->setParameter('makeRecurrent', $value);
    }
}
