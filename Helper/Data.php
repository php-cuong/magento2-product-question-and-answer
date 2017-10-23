<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-21 07:42:02
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-23 11:15:53
 */

namespace PHPCuong\ProductQuestionAndAnswer\Helper;

use Magento\Store\Model\ScopeInterface;

/**
 * Default question helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_QUESTION_GUETS_ALLOW_TO_WRITE = 'catalog/question/allow_guest';

    const XML_QUESTION_GUETS_ALLOW_TO_REPLY = 'catalog/question/allow_guest_reply';

    const XML_QUESTION_CUSTOMERS_ALLOW_TO_WRITE = 'catalog/question/allow_customer';

    const XML_QUESTION_CUSTOMERS_ALLOW_TO_REPLY = 'catalog/question/allow_customer_reply';

    const XML_QUESTION_LIST_PER_PAGE = 'catalog/question/list_per_page';

    const XML_QUESTION_RULES = 'catalog/question/rules';

    const XML_QUESTION_AUTO_APPROVAL = 'catalog/question/auto_approval_new_question';

    const XML_ANSWER_AUTO_APPROVAL = 'catalog/question/auto_approval_new_answer';

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\Status
     */
    protected $statusCode;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\Status $statusCode
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \PHPCuong\ProductQuestionAndAnswer\Model\Status $statusCode
    ) {
        $this->customerSession = $customerSession;
        $this->statusCode = $statusCode;
        parent::__construct($context);
    }

    /**
     * Return an indicator of whether or not guest or customer is allowed to write
     *
     * @return bool
     */
    public function getAllowToWrite()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->getIsCustomersAllowToWrite();
        }

        return $this->scopeConfig->isSetFlag(
            self::XML_QUESTION_GUETS_ALLOW_TO_WRITE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Return an indicator of whether or not guest or customer is allowed to reply
     *
     * @return bool
     */
    public function getAllowToReply()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->scopeConfig->isSetFlag(
                self::XML_QUESTION_CUSTOMERS_ALLOW_TO_REPLY,
                ScopeInterface::SCOPE_STORE
            );
        }

        return $this->scopeConfig->isSetFlag(
            self::XML_QUESTION_GUETS_ALLOW_TO_REPLY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *
     * @return bool
     */
    public function getIsGuest()
    {
        return $this->customerSession->isLoggedIn() ? false : true;
    }

    /**
     * Return an indicator of whether or not customer is allowed to write
     *
     * @return bool
     */
    public function getIsCustomersAllowToWrite()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_QUESTION_CUSTOMERS_ALLOW_TO_WRITE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Return the status text
     *
     * @param int $statusId
     * @return string
     */
    public function getStatusText($statusId)
    {
        return $this->statusCode->getStatusText($statusId);
    }

    /**
     * Retrieve the list per page.
     *
     * @return int
     */
    public function getPageSize()
    {
        $pageSize = $this->scopeConfig->getValue(
            self::XML_QUESTION_LIST_PER_PAGE,
            ScopeInterface::SCOPE_STORE
        );

        if ($pageSize >= 1 && $pageSize <= 20) {
            return $pageSize;
        }

        return 5;
    }

    /**
     * Retrieve the product question rules page
     *
     * @return string
     */
    public function getProductQuestionRulesPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_QUESTION_RULES,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve the auto approval new question
     *
     * @return string
     */
    public function getAutoApprovalNewQuestion()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_QUESTION_AUTO_APPROVAL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve the auto approval new answer
     *
     * @return string
     */
    public function getAutoApprovalNewAnswer()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_ANSWER_AUTO_APPROVAL,
            ScopeInterface::SCOPE_STORE
        );
    }
}
