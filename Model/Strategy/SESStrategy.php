<?php
/**
 * Copyright © 2016 OpsWay. All rights reserved.
 */

namespace OpsWay\EmailAmazonSES\Model\Strategy;

use OpsWay\EmailAmazonSES\Helper\Data as Helper;
use \Zend_Http_Client as Http;
use \Zend_Uri as Uri;

class SESStrategy implements StrategyInterface
{
    /**
     * @var \OpsWay\EmailAmazonSES\Helper\Data
     */
    private $helper;

    /**
     * @var \Magento\Framework\Mail\Message
     */
    private $mail;

    private $host = 'https://email.us-west-2.amazonaws.com';

    private $config;

    /**
     * @param \OpsWay\EmailAmazonSES\Helper\Data $helper
     */
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->config = $this->helper->getAmazonSettings();
        $this->host = Uri::factory($this->host);
    }

    /**
     * @return string
     * @throws \Zend_Http_Client_Exception
     */
    public function send()
    {
        if ($this->isEnabled()) {
            $date = gmdate('D, d M Y H:i:s O');

            $host = $this->config['host'] ? $this->config['host'] : $this->host;

            $client = new Http($host);
            $client->setMethod(Http::POST);
            $client->setHeaders([
                'Date' => $date,
                'X-Amzn-Authorization' => $this->_buildAuthKey($date),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]);
            $client->resetParameters();
            $client->setEncType(Http::ENC_URLENCODED);
            $client->setParameterPost($this->generateParams());
            $response = $client->request(Http::POST);

            return $response->getRawBody();
        }
    }

    /**
     * get module configs
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * if module is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->helper->isEnabled();
    }

    /**
     * @param \Magento\Framework\Mail\MessageInterface $mail
     * @return $this
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Returns header string containing encoded authentication key
     *
     * @param   date $date
     * @return  string
     */
    private function _buildAuthKey($date)
    {
        return 'AWS3-HTTPS AWSAccessKeyId=' . $this->config['keyId'] .
            ',Algorithm=HmacSHA256,Signature=' . base64_encode(hash_hmac('sha256', $date, $this->config['privateKey'], true));
    }

    /**
     * @param $message
     * @return null
     */
    private function extractHtml($message)
    {
        $body = $message->getBody();

        if ($body->type === 'text/html') {
            return $body->getRawContent();
        }

        return null;
    }

    /**
     * @return string
     */
    private function getMailType()
    {
        $body = $this->mail->getBody();

        if (is_string($body)) {
            return 'Text';
        }

        return explode('/', $body->type)[1];
    }

    /**
     * @param $message
     * @return null
     */
    private function extractText($message)
    {
        $body = $message->getBody();

        if (is_string($body)) {
            return $body;
        }

        if ($body->type === 'text/plain') {
            return $body->getContent();
        }

        return null;
    }

    /**
     * @return array
     */
    private function generateParams()
    {
        $params = [
            'Action' => 'SendEmail',
            'Source' => $this->mail->getFrom(),
            'Message.Body.Html.Data' => $this->extractHtml($this->mail),
            'Message.Subject.Data' => $this->mail->getSubject()
        ];

        $recipients = $this->mail->getRecipients();
        $i = 0;
        foreach ($recipients as $recipient) {
            $params[sprintf('Destination.ToAddresses.member.%d', $i + 1)] = $recipient;
        }
        return $params;
    }
}
