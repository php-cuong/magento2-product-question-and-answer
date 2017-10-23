<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-18 16:07:10
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-23 08:23:29
 */

namespace PHPCuong\ProductQuestionAndAnswer\Block;

class Form extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \PHPCuong\ProductQuestionAndAnswer\Helper\Data
     */
    protected $questionData;

    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $customerUrl;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \PHPCuong\ProductQuestionAndAnswer\Helper\Data $questionData
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \PHPCuong\ProductQuestionAndAnswer\Helper\Data $questionData,
        \Magento\Customer\Model\Url $customerUrl,
        array $data = []
    ) {
        $this->questionData = $questionData;
        $this->customerUrl = $customerUrl;
        parent::__construct($context, $data);
    }

    /**
     * Get question product post action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->getUrl(
            'question/product/post',
            [
                '_secure' => $this->getRequest()->isSecure(),
                'id' => $this->getProductId(),
            ]
        );
    }

    /**
     * Return an indicator of whether or not guest or customer is allowed to write
     *
     * @return bool
     */
    public function getAllowToWrite()
    {
        return $this->questionData->getAllowToWrite();
    }

    /**
     *
     * @return bool
     */
    public function getIsGuest()
    {
        return $this->questionData->getIsGuest();
    }

    /**
     *
     * @return bool
     */
    public function getIsCustomersAllowToWrite()
    {
        return $this->questionData->getIsCustomersAllowToWrite();
    }

    /**
     * Get question product id
     *
     * @return int
     */
    protected function getProductId()
    {
        return $this->getRequest()->getParam('id', false);
    }

    /**
     * Retrieve the question rules URL
     *
     * @return string
     */
    public function getQuestionRulesUrl()
    {
        return $this->questionData->getProductQuestionRulesPage();
    }

    /**
     * Return register URL
     *
     * @return string
     */
    public function getRegisterUrl()
    {
        return $this->customerUrl->getRegisterUrl();
    }

    /**
     * Return login URL
     *
     * @return string
     */
    public function getLoginUrl()
    {
        return $this->customerUrl->getLoginUrl();
    }
}
