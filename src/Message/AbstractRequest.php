<?php

namespace Omnipay\RbkMoney\Message;

use Guzzle\Common\Event;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\RbkMoney\LoggerTrait;

abstract class AbstractRequest extends BaseAbstractRequest
{
    use LoggerTrait;

    /**
     * Get HTTP Method.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return [];
    }

    /**
     * Create response.
     *
     * @param mixed $data
     * @param int $statusCode
     * @return ResponseInterface
     */
    protected function createResponse($data, $statusCode)
    {
        return $this->response = new Response($this, $data, $statusCode);
    }

    /**
     * @inheritdoc
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {
        // Don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function (Event $event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        $requestId = str_replace('.', '', microtime(true));
        $headers = [
            'X-Request-ID' => $requestId,
            'Authorization' => $this->getToken(),
            'Accept' => 'application/json',
            'Content-type' => 'application/json; charset=utf-8',
        ];

        if ($this->getHttpMethod() === 'GET') {
            $query = http_build_query($data);
            $this->log("Request to RBK.money API: [$requestId] [GET] "
                . $this->getEndpoint() . ($query ? "?$query" : ''));
            $httpRequest = $this->httpClient->createRequest(
                $this->getHttpMethod(),
                $this->getEndpoint() . '?' . http_build_query($data),
                $headers
            );
        } else {
            $this->log("Request to RBK.money API: [$requestId] [" . $this->getHttpMethod() . '] '
                . $this->getEndpoint() . ' ' . json_encode($data));
            $httpRequest = $this->httpClient->createRequest(
                $this->getHttpMethod(),
                $this->getEndpoint(),
                $headers,
                json_encode($data)
            );
        }

        try {
            $httpResponse = $httpRequest->send();
            // Empty response body should be parsed also as and empty array
            $responseData = $httpResponse->getBody(true) ? $httpResponse->json() : [];
            $responseStatus = $httpResponse->getStatusCode();
            $this->log("Response from RBK.money API: [$requestId] [$responseStatus] " . $httpResponse->getBody(true));
            return $this->response = $this->createResponse($responseData, $responseStatus);
        } catch (\Exception $e) {
            $this->log("Request is failed: [$requestId] {$e->getMessage()}", 'error');
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Get request authorization token.
     *
     * @return string
     */
    public function getToken()
    {
        return 'Bearer ' . $this->getApiKey();
    }

    /**
     * Get request endpoint.
     *
     * @return string
     */
    abstract protected function getEndpoint();

    /**
     * Get base endpoint.
     *
     * @return string
     */
    public function getBaseEndpoint()
    {
        return $this->getParameter('baseEndpoint');
    }

    /**
     * Set base endpoint.
     *
     * @param string $value
     * @return $this
     */
    public function setBaseEndpoint($value)
    {
        return $this->setParameter('baseEndpoint', $value);
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
}
