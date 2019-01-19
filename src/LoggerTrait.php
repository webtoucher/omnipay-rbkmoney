<?php

namespace Omnipay\RbkMoney;

use Omnipay\Common\Message\AbstractRequest;

trait LoggerTrait
{
    /**
     * @var callable
     */
    protected $logger;

    /**
     * Get no logging sign.
     *
     * @return string
     */
    public function getNoLog()
    {
        /** @var AbstractRequest $this */
        return $this->getParameter('noLog');
    }

    /**
     * Set no logging sign.
     *
     * @param string $value
     * @return $this
     */
    public function setNoLog($value)
    {
        /** @var AbstractRequest $this */
        return $this->setParameter('noLog', $value);
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
        if (!$this->getNoLog()) {
            call_user_func($this->logger, $message, $level);
        }
    }
}
