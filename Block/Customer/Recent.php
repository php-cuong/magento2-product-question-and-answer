<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-23 08:46:03
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-23 10:22:37
 */

namespace PHPCuong\ProductQuestionAndAnswer\Block\Customer;

/**
 * Recent Customer Questions Block
 */
class Recent extends \Magento\Framework\View\Element\Template
{
    /**
     * Product questions collection
     *
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Question\Collection
     */
    protected $collection;

    /**
     * Review resource model
     *
     * @var \Magento\Review\Model\ResourceModel\Review\Product\CollectionFactory
     */
    protected $questionColFactory;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\Config\Source\FormatDateTime
     */
    protected $formatDateTime;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Question\CollectionFactory $questionColFactory
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\Config\Source\FormatDateTime $formatDateTime
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Question\CollectionFactory $questionColFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \PHPCuong\ProductQuestionAndAnswer\Model\Config\Source\FormatDateTime $formatDateTime,
        array $data = []
    ) {
        $this->questionColFactory = $questionColFactory;
        $this->currentCustomer = $currentCustomer;
        $this->formatDateTime = $formatDateTime;
        parent::__construct($context, $data);
    }

    /**
     * Return collection of questions
     *
     * @return array|\PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Question\Collection
     */
    public function getQuestions()
    {
        if (!($customerId = $this->currentCustomer->getCustomerId())) {
            return [];
        }
        if (!$this->collection) {
            $this->collection = $this->questionColFactory->create();
            $this->collection
                ->addStoreFilter($this->_storeManager->getStore()->getId())
                ->addCustomerFilter($customerId)
                ->setProductName()
                ->setPageSize(5)
                ->load();
        }
        return $this->collection;
    }

    /**
     * Return question customer url
     *
     * @return string
     */
    public function getAllQuestionsUrl()
    {
        return $this->getUrl('question/customer');
    }

    /**
     * Truncate string
     *
     * @param string $value
     * @param int $length
     * @param string $etc
     * @param string &$remainder
     * @param bool $breakWords
     * @return string
     */
    public function truncateString($value, $length = 80, $etc = '...', &$remainder = '', $breakWords = true)
    {
        return $this->filterManager->truncate(
            $value,
            ['length' => $length, 'etc' => $etc, 'remainder' => $remainder, 'breakWords' => $breakWords]
        );
    }

    /**
     * Get product link
     *
     * @param int $productId
     * @return string
     */
    public function getProductUrl($productId)
    {
        return $this->getUrl('catalog/product/view/', ['id' => $productId]);
    }

    /**
     * Format date in short format
     *
     * @param string $date
     * @return string
     */
    public function dateFormat($date)
    {
        return $this->formatDate($date, \IntlDateFormatter::SHORT);
    }
}
