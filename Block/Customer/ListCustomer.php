<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-21 11:24:32
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-21 18:50:07
 */

namespace PHPCuong\ProductQuestionAndAnswer\Block\Customer;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Customer Questions list block
 */
class ListCustomer extends \Magento\Customer\Block\Account\Dashboard
{
    /**
     * Product questions collection
     *
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Question\Collection
     */
    protected $collection;

    /**
     * Question resource model
     *
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Question\CollectionFactory
     */
    protected $questionColFactory;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Answer\CollectionFactory
     */
    protected $answerColFactory;

    /**
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\Config\Source\FormatDateTime
     */
    protected $formatDateTime;

    /**
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\UserType
     */
    protected $userType;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Question\CollectionFactory $collectionFactory
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Answer\CollectionFactory $answerColFactory
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\Config\Source\FormatDateTime $formatDateTime
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\UserType $userType
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        \PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Question\CollectionFactory $collectionFactory,
        \PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Answer\CollectionFactory $answerColFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \PHPCuong\ProductQuestionAndAnswer\Model\Config\Source\FormatDateTime $formatDateTime,
        \PHPCuong\ProductQuestionAndAnswer\Model\UserType $userType,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        array $data = []
    ) {
        $this->questionColFactory = $collectionFactory;
        $this->answerColFactory = $answerColFactory;
        $this->formatDateTime = $formatDateTime;
        $this->userType = $userType;
        $this->dataObjectFactory = $dataObjectFactory;
        parent::__construct(
            $context,
            $customerSession,
            $subscriberFactory,
            $customerRepository,
            $customerAccountManagement,
            $data
        );
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * Get html code for toolbar
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Initializes toolbar
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        if ($this->getQuestions()) {
            $toolbar = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'customer_review_list.toolbar'
            )->setCollection(
                $this->getQuestions()
            );

            $this->setChild('toolbar', $toolbar);
        }
        return parent::_prepareLayout();
    }

    /**
     * Get reviews
     *
     * @return bool|\PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Question\Collection
     */
    public function getQuestions()
    {
        if (!($customerId = $this->currentCustomer->getCustomerId())) {
            return false;
        }
        if (!$this->collection) {
            $this->collection = $this->questionColFactory->create();
            $this->collection
                ->addStoreFilter($this->_storeManager->getStore()->getId())
                ->addCustomerFilter($customerId)
                ->setProductName()
                ->setDateOrder();
        }
        return $this->collection;
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
        return $this->formatDateTime->formatCreatedAt($date);
    }

    /**
     * Get list of answers of the question
     *
     * @param int $questionId
     * @return array
     */
    public function getAnswerList($questionId)
    {
        $collection = $this->answerColFactory->create()->addFieldToFilter(
            'main_table.question_id', (int) $questionId
        )->addStatusFilter(
            \PHPCuong\ProductQuestionAndAnswer\Model\Status::STATUS_APPROVED
        )->addVisibilityFilter(
            \PHPCuong\ProductQuestionAndAnswer\Model\Visibility::VISIBILITY_VISIBLE
        );

        $answers = [];
        foreach ($collection as $answer) {
            $answers[] = $this->getAnswerInfo($answer);
        }

        return $answers;
    }

    /**
     * Retrieve the answer information
     *
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Answer\CollectionFactory $answer
     * @return array
     */
    protected function getAnswerInfo($answer)
    {
        /** @var \Magento\Framework\DataObjectFactory $dataObjectFactory */
        return $this->dataObjectFactory->create()
            ->setId($answer->getAnswerId())
            ->setContent(nl2br($answer->getAnswerDetail()))
            ->setAuthorName(ucwords(strtolower($answer->getAnswerAuthorName())))
            ->setFirstCharacter(substr($answer->getAnswerAuthorName(), 0, 1))
            ->setLikes($answer->getAnswerLikes())
            ->setDislikes($answer->getAnswerDislikes())
            ->setAnsweredBy($this->userType->getUserTypeText($answer->getAnswerUserTypeId()))
            ->setCreatedAt($this->formatDateTime->formatCreatedAt($answer->getAnswerCreatedAt()))
            ->getData();
    }

    /**
     * Get URL for likes and dislikes
     *
     * @return string
     */
    public function getLikeDislikeUrl()
    {
        return $this->getUrl(
            'question/product/likeDislike',
            [
                '_secure' => $this->getRequest()->isSecure()
            ]
        );
    }
}
