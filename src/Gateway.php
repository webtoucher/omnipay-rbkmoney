<?php

namespace Omnipay\RbkMoney;

use Guzzle\Http\ClientInterface;
use Omnipay\Common\AbstractGateway;
use Omnipay\RbkMoney\Message\AbstractRequest;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class Gateway extends AbstractGateway
{
    /**
     * @var callable
     */
    protected $logger;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'RBK.money';
    }

    public function __construct(ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        parent::__construct($httpClient, $httpRequest);
        // Default logger keeps silent
        $this->logger = function ($message, $level) {
        };
    }

    /**
     * @inheritdoc
     */
    public function getDefaultParameters()
    {
        return [
            'gateUrl' => 'https://api.rbk.money',
            'version' => 'v2',
            'shopId' => '',
            'apiKey' => '',
        ];
    }

    /**
     * Get gate base URL.
     *
     * @return string
     */
    public function getGateUrl()
    {
        return $this->getParameter('gateUrl');
    }

    /**
     * Set gate base URL.
     *
     * @param string $value
     * @return $this
     */
    public function setGateUrl($value)
    {
        return $this->setParameter('gateUrl', rtrim($value, '/'));
    }

    /**
     * Get API version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->getParameter('version');
    }

    /**
     * Set API version.
     *
     * @param string $value
     * @return $this
     */
    public function setVersion($value)
    {
        return $this->setParameter('version', $value);
    }

    /**
     * Get shop ID.
     *
     * @return string
     */
    public function getShopId()
    {
        return $this->getParameter('shopId');
    }

    /**
     * Set shop ID.
     *
     * @param string $value
     * @return $this
     */
    public function setShopId($value)
    {
        return $this->setParameter('shopId', $value);
    }

    /**
     * Get API private key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    /**
     * Set API private key.
     *
     * @param string $value
     * @return $this
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * Set logger function.
     *
     * @param callable $value
     * @return $this
     */
    public function setLogger($value)
    {
        $this->logger = $value;
        return $this;
    }

    /**
     * Log a message.
     *
     * @param string $message
     * @param string $level
     * @return void
     */
    protected function log($message, $level = 'info')
    {
        call_user_func($this->logger, $message, $level);
    }

    /**
     * @param $name
     * @param array $parameters
     * @return AbstractRequest
     */
    private function getRequest($name, array $parameters)
    {
        $version = $this->getVersion();
        $parameters['baseEndpoint'] = "{$this->getGateUrl()}/$version";
        $parameters['shopId'] = $this->getShopId();
        $parameters['apiKey'] = $this->getApiKey();
        $parameters['logger'] = $this->logger;

        return $this->createRequest("\\Omnipay\\RbkMoney\\Message\\$version\\$name", $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function createInvoice(array $parameters = [])
    {
        return $this->getRequest('CreateInvoiceRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function getInvoice(array $parameters = [])
    {
        return $this->getRequest('GetInvoiceRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function fulfillInvoice(array $parameters = [])
    {
        return $this->getRequest('FulfillInvoiceRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function rescindInvoice(array $parameters = [])
    {
        return $this->getRequest('RescindInvoiceRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function getInvoiceEvents(array $parameters = [])
    {
        return $this->getRequest('GetInvoiceEventsRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function createPayment(array $parameters = [])
    {
        return $this->getRequest('CreatePaymentRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function webHook(array $parameters = [])
    {
        $parameters['logger'] = $this->logger;
        return $this->createRequest('\Omnipay\RbkMoney\Message\WebHookRequest', $parameters);
    }
}
