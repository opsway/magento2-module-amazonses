<?php
/**
 * Copyright © 2016 OpsWay.
 */
namespace OpsWay\EmailAmazonSES\Controller\Adminhtml\Amazon;

/**
 * Class Send - Sends the message for testing
 * @package OpsWay\EmailAmazonSES\Controller\Adminhtml\Amazon
 */
class Send extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var \Magento\Framework\Mail\Message
     */
    private $message;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Mail\Message $message
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Mail\Message $message
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->message = $message;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $email = $this->_objectManager->get('OpsWay\EmailAmazonSES\Helper\Data')->getTestEmail();
        $fromEmail = $this->_objectManager->get('OpsWay\EmailAmazonSES\Helper\Data')->getFromEmail();

        $receiverInfo = [
            'name' => 'Receiver Name',
            'email' => $email
        ];

        $senderInfo = [
            'name' => 'Sender Name',
            'email' => $fromEmail,
        ];

        $result = $this->_objectManager->get('OpsWay\EmailAmazonSES\Helper\Email')->sendMail(
            $senderInfo,
            $receiverInfo
        );

        return $this->resultJsonFactory->create()->setData($result);
    }
}
