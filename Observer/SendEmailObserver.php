<?php
/**
 * Copyright © 2016 OpsWay.
 */
namespace OpsWay\EmailAmazonSES\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use OpsWay\EmailAmazonSES\Model\Strategy\SESStrategy;

class SendEmailObserver implements ObserverInterface
{
    /**
     * @var \OpsWay\EmailAmazonSES\Model\Strategy\StrategyInterface
     */
    private $strategy;

    /**
     * @param \OpsWay\EmailAmazonSES\Model\Strategy\SESStrategy $strategy
     */
    public function __construct(SESStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(Observer $observer)
    {
        $targetClass = $observer->getData('context');
        $this->strategy->setMail($targetClass->getMessage());

        if ($targetClass->getStrategy() == null && $this->strategy->isEnabled()) {
            $targetClass->setStrategy($this->strategy);
        }
    }
}
