<?php
/**
 * Copyright © 2016 OpsWay. All rights reserved.
 */

namespace OpsWay\EmailAmazonSES\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;

class Data extends AbstractHelper
{
    /**
     * @var ScopeConfigInterface
     */
    protected $config;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param EncryptorInterface $config
     */
    public function __construct(ScopeConfigInterface $scopeConfig, EncryptorInterface $config)
    {
        $this->config = $scopeConfig;
    }

    /**
     * @param string $path
     * @param string $type
     * @return mixed
     */
    public function getValue($path, $type = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->config->getValue($path, $type);
    }

    /**
     * @return array
     */
    public function getAmazonSettings()
    {
        return [
            'host' => $this->getValue('OpsWayemailsettings/ses/host'),
            'keyId' => $this->getValue('OpsWayemailsettings/ses/keyId'),
            'privateKey' => $this->getValue('OpsWayemailsettings/ses/privateKey'),
            'testMail'  => $this->getValue('OpsWayemailsettings/test/testEmail')
        ];
    }

    /**
     * Get test email address
     *
     * @return mixed
     */
    public function getTestEmail()
    {
        return $this->getValue('OpsWayemailsettings/test/testEmail');
    }

    /**
     * Get from test email address
     *
     * @return mixed
     */
    public function getFromEmail()
    {
        return $this->getValue('OpsWayemailsettings/test/fromEmail');
    }

    /**
     * is module enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        $enabled = $this->getValue('OpsWayemailsettings/ses/enabled');

        return $enabled == '1' ? true : false;
    }
}
