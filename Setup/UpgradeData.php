<?php

namespace OpsWay\EmailAmazonSES\Setup;

use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    public function __construct(EncryptorInterface $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->encryptAwsSecretKey($setup);
        }

        $setup->endSetup();
    }

    private function encryptAwsSecretKey(ModuleDataSetupInterface $setup)
    {
        $select = $setup->getConnection()->select()
            ->from(
                $setup->getTable('core_config_data'),
                ['config_id', 'value']
            )
            ->where('path = ?', 'OpsWayemailsettings/ses/privateKey');

        foreach ($setup->getConnection()->fetchAll($select) as $configRow) {
            $value = (string)$configRow['value'];

            if ($value) {
                $row = [
                    'value' => (string)$this->encryptor->encrypt($value),
                ];

                $setup->getConnection()->update(
                    $setup->getTable('core_config_data'),
                    $row,
                    ['config_id = ?' => $configRow['config_id']]
                );
            }
        }
    }
}
