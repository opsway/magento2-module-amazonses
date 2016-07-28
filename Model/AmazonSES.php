<?php
/**
 * Copyright © 2016 OpsWay.
 */
namespace OpsWay\EmailAmazonSES\Model;

use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Event\Manager as EventManager;
use OpsWay\EmailAmazonSES\Model\Strategy\StrategyInterface;

class AmazonSES extends \Zend_Mail_Transport_Sendmail implements \Magento\Framework\Mail\TransportInterface
{
    /**
     * @var \Magento\Framework\Mail\MessageInterface
     */
    protected $message;

    /**
     * @var \OpsWay\EmailAmazonSES\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Event\Manager
     */
    protected $eventManager;

    /**
     * @var \OpsWay\EmailAmazonSES\Model\Strategy\StrategyInterface
     */
    protected $strategy;

    /**
     * @param MessageInterface $message
     * @param EventManager $manager
     * @param null $parameters
     */
    public function __construct(MessageInterface $message, EventManager $manager, $parameters = null)
    {
        if (!$message instanceof \Zend_Mail) {
            throw new \InvalidArgumentException('The message should be an instance of \Zend_Mail');
        }
        parent::__construct($parameters);
        $this->message = $message;
        $this->eventManager = $manager;
    }

    /**
     * @return MessageInterface|\Zend_Mail
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param \OpsWay\EmailAmazonSES\Model\Strategy\StrategyInterface $strategy
     * @return $this
     */
    public function setStrategy(StrategyInterface $strategy)
    {
        $this->strategy = $strategy;

        return $this;
    }

    /**
     * @return \OpsWay\EmailAmazonSES\Model\Strategy\StrategyInterface
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * @return array
     * @throws \Zend_Mail_Transport_Exception
     */
    public function sendMessage()
    {
        $this->eventManager->dispatch('send_email_event',
            [
                'context' => $this,
            ]);

        if ($this->getStrategy() != null && $this->getStrategy()->getConfig()) {

            return [
                'result' => $this->getStrategy()->setMail($this->message)->send()
            ];
        }

        parent::send($this->message);
    }
}