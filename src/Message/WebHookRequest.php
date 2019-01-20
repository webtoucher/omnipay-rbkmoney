<?php

namespace Omnipay\RbkMoney\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\RbkMoney\LoggerTrait;

class WebHookRequest extends AbstractRequest
{
    use LoggerTrait;

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
     * @return ResponseInterface
     */
    protected function createResponse($data)
    {
        return $this->response = new WebHookResponse($this, $data);
    }

    /**
     * @inheritdoc
     * @throws InvalidRequestException
     */
    public function sendData($data)
    {
        return $this->response = $this->createResponse(json_decode($this->getContent(), true));
    }

    /**
     * Get webhook public key.
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->getParameter('publicKey');
    }

    /**
     * Set webhook public key.
     *
     * @param string $value
     * @return $this
     */
    public function setPublicKey($value)
    {
        return $this->setParameter('publicKey', $value);
    }

    /**
     * Get project name.
     *
     * @return string
     */
    public function getProject()
    {
        return $this->getParameter('project');
    }

    /**
     * Set project name.
     *
     * @param string $value
     * @return $this
     */
    public function setProject($value)
    {
        return $this->setParameter('project', $value);
    }

    /**
     * Get request signature.
     *
     * @return string
     * @throws InvalidRequestException
     */
    public function getSignature()
    {
        $signature = preg_replace("/alg=(\S+);\sdigest=/", '', $_SERVER['HTTP_CONTENT_SIGNATURE']);

        if (empty($signature)) {
            throw new InvalidRequestException('Signature is missing');
        }

        return base64_decode(strtr($signature, '-_,', '+/='));
    }

    /**
     * Get webhook content.
     *
     * @return string
     * @throws InvalidRequestException
     */
    public function getContent()
    {
        $requestId = str_replace('.', '', microtime(true));
        $content = file_get_contents('php://input');
        $this->log("Webhook from RBK.money: [$requestId]\n$content");
        if (!$this->getPublicKey()) {
            $this->log("You should add public key to verify the Webhook signature: [$requestId]");
            return $content;
        }

        if ($publicKeyId = openssl_get_publickey($this->getPublicKey())) {
            if (1 === openssl_verify($content, $this->getSignature(), $publicKeyId, OPENSSL_ALGO_SHA256)) {
                $this->log("Webhook is verified successfully: [$requestId]");
                return $content;
            }
        }
        if ($currentProject = $this->getProject()) {
            $data = json_decode($content, true);
            $project = isset($data['invoice']) && isset($data['invoice']['metadata'])
                && isset($data['invoice']['metadata']['project'])
                ? $data['invoice']['metadata']['project']
                : null;
            if ($project && $project === $currentProject) {
                $this->log("Webhook is not for this project (it is for \"$project\"), so it was rejected: [$requestId]");
                throw new InvalidRequestException('Webhook is not for this project, so it was rejected', 200);
            }
        }
        $this->log("Webhook has failed verification: [$requestId]");
        throw new InvalidRequestException('Webhook has failed verification');
    }
}
