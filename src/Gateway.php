<?php

namespace Omnipay\RbkMoney;

use Omnipay\Common\AbstractGateway;
use Omnipay\RbkMoney\Message\AbstractRequest;

class Gateway extends AbstractGateway
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'RBK.money';
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
            'logger' => function ($message, $level = 'info') { },
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
     * Get logger function.
     *
     * @return callable
     */
    public function getLogger()
    {
        return $this->getParameter('logger');
    }

    /**
     * Set logger function.
     *
     * @param callable $value
     * @return $this
     */
    public function setLogger($value)
    {
        return $this->setParameter('logger', $value);
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
        $parameters['logger'] = $this->getLogger();

        return $this->createRequest("\\Omnipay\\RbkMoney\\Message\\$version\\$name", $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function getInvoice(array $parameters = [])
    {
        return $this->getRequest('GetInvoiceRequest', $parameters);
    }
}
