<?php
/**
 * Copyright © 2016 OpsWay. All rights reserved.
 */

// @codingStandardsIgnoreFile

namespace OpsWay\EmailAmazonSES\Block\Page\System\Config\Test;

/**
 * "Send a test message" button renderer
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Test extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    protected $urlBuider;

    /**
     * @param \Magento\Backend\Model\UrlInterface $urlBuilder
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Model\UrlInterface $urlBuilder,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->urlBuider = $urlBuilder;
        parent::__construct($context, $data);
    }

    /**
     * Set template
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('OpsWay_EmailAmazonSES::page/system/config/test/test.phtml');
    }

    /**
     * generate the url for the send action
     *
     * @return mixed
     */
    public function getTestUrl()
    {
        return $this->urlBuider->getUrl('OpsWay/Amazon/Send');
    }

    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'reset_to_default_button',
                'label' => __('Send'),
                'onclick' => 'javascript:sendTestMessage(); return false;',
            ]
        );

        return $button->toHtml();
    }

    /**
     * Render button
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        // Remove scope label
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }
}
