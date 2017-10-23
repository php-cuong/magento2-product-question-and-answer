<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-15 03:19:28
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-23 13:00:19
 */

namespace PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Exception\LocalizedException;
use PHPCuong\ProductQuestionAndAnswer\Model\Status as AnswerStatus;

class Answer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * ValidationRules
     *
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\ValidationRules
     */
    protected $validationRules;

    /**
     * Escaper
     *
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * Backend Auth session model
     *
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\AdminUser
     */
    protected $authSession;

    /**
     * User Type
     *
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\UserType
     */
    protected $userType;

    /**
     * Customer
     *
     * @var \Magento\Customer\Model\Customer
     */
    protected $customerModel;

    /**
     * Question
     *
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\Question
     */
    protected $question;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\ValidationRules $validationRules
     * @param \Magento\Framework\Escaper $escaper
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\AdminUser $authSession
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\UserType $userType
     * @param \Magento\Customer\Model\Customer $customerModel
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\Question $question
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \PHPCuong\ProductQuestionAndAnswer\Model\ValidationRules $validationRules,
        \Magento\Framework\Escaper $escaper,
        \PHPCuong\ProductQuestionAndAnswer\Model\AdminUser $authSession,
        \PHPCuong\ProductQuestionAndAnswer\Model\UserType $userType,
        \Magento\Customer\Model\Customer $customerModel,
        \PHPCuong\ProductQuestionAndAnswer\Model\Question $question
    ) {
        $this->validationRules = $validationRules;
        $this->escaper = $escaper;
        $this->authSession = $authSession;
        $this->userType = $userType;
        $this->customerModel = $customerModel;
        $this->question = $question;
        parent::__construct($context);
    }

    /**
     * construct
     * @return void
     */
    protected function _construct()
    {
        $this->_init('phpcuong_product_answer', 'answer_id');
    }

    /**
     * After save callback
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return parent
     */
    protected function _afterSave(AbstractModel $object)
    {
        $this->updateTotalAnswers($object);

        $this->updatePendingAnswers($object);

        return parent::_afterSave($object);
    }

    /**
     * After delete callback
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return parent
     */
    protected function _afterDelete(AbstractModel $object)
    {
        $this->updateTotalAnswers($object);

        $this->updatePendingAnswers($object);

        return parent::_afterDelete($object);
    }

    /**
     * Update the question
     *
     * @param AbstractModel $object
     * @param string $type
     * @return void
     */
    public function updateTheQuestion(AbstractModel $object, $type = 'total_answers')
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()->from(
            $this->getMainTable(),
            ['count' => 'COUNT(answer_id)']
        )->where('question_id =?', $object->getQuestionId());

        if ($type == 'pending_answers') {
            $select->where('answer_status_id =?', AnswerStatus::STATUS_PENDING);
        }

        $count = $this->getConnection()->fetchAll($select);

        foreach ($count as $value) {
            $adapter->update(
                $this->getTable('phpcuong_product_question'),
                [$type => $value['count']],
                ['question_id = ?' => (int) $object->getQuestionId()]
            );
        };
    }

    /**
     * Update total number of likes
     *
     * @param int $value
     * @param String $selectColumn
     * @return int
     */
    public function updateLikes($value, $selectColumn = 'answer_likes')
    {
        $this->getConnection()->query(
            'UPDATE '.$this->getMainTable().' SET '.$selectColumn.'='.$selectColumn.' + 1 WHERE answer_id = '.$value
        );

        $result = $this->getConnection()->fetchRow(
            'SELECT '.$selectColumn.' FROM '.$this->getMainTable().' WHERE answer_id = '.$value
        );

        return $result[$selectColumn];
    }

    /**
     * Update total number of dislikes
     *
     * @param int $value
     * @return int
     */
    public function updateDislikes($value)
    {
        return $this->updateLikes($value, 'answer_dislikes');
    }

    /**
     * Update total number of pending answers for the question
     *
     * @param AbstractModel $object
     * @return void
     */
    public function updatePendingAnswers(AbstractModel $object)
    {
        $this->updateTheQuestion($object, 'pending_answers');
    }

    /**
     * Update total number of answers for the question
     *
     * @param AbstractModel $object
     * @return void
     */
    public function updateTotalAnswers(AbstractModel $object)
    {
        $this->updateTheQuestion($object);
    }

    /**
     * Before save callback
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return parent
     */
    protected function _beforeSave(AbstractModel $object)
    {
        $this->setAnswerAuthorEmailName($object);

        $this->validateInputs($object);

        $this->cleanInputs($object);

        return parent::_beforeSave($object);
    }

    /**
     * Clean inputs
     *
     * @param AbstractModel $object
     * @return this
     */
    protected function cleanInputs(AbstractModel $object)
    {
        $object->setAnswerDetail(trim(strip_tags($object->getAnswerDetail())));
        $object->setAnswerAuthorName(trim($object->getAnswerAuthorName()));
        return $this;
    }

    /**
     * Validate Inputs
     *
     * @param AbstractModel $object
     * @return this
     * @throws LocalizedException
     */
    protected function validateInputs(AbstractModel $object)
    {
        $this->validationRules->validateEmptyValue($object->getAnswerDetail(), 'Content of Answer');
        $this->validationRules->validateEmptyValue($object->getAnswerAuthorName(), 'Author Name');
        $this->validationRules->validateEmail($object->getAnswerAuthorEmail(), 'Author Email');
        $this->validationRules->validateVisibility($object->getAnswerVisibilityId());
        $this->validationRules->validateStatus($object->getAnswerStatusId());
        $this->validationRules->validateUserType($object->getAnswerUserTypeId());
        $this->validationRules->validateIntegerNumber($object->getAnswerLikes(), 'Total number of likes');
        $this->validationRules->validateIntegerNumber($object->getAnswerDislikes(), 'Total number of dislikes');
        $this->validateQuestionId($object->getQuestionId(), 'Question ID');
        return $this;
    }

    /**
     * Set the value for the author email and author name
     *
     * @param AbstractModel $object
     * @return this
     */
    public function setAnswerAuthorEmailName(AbstractModel $object)
    {
        if ($this->getAdministratorCode() == $object->getData('answer_user_type_id')) {
            $object->setAnswerAuthorEmail($this->getAdminEmail());
            $object->setAnswerAuthorName($this->getAdminName());
            $object->setAnswerUserId($this->authSession->getID());
        }

        if ($this->getCustomerCode() == $object->getData('answer_user_type_id')) {
            $customerModel = $this->customerModel->load((int) $object->getAnswerUserId());
            if ($customerModel->getEntityId()) {
                $object->setAnswerAuthorEmail($customerModel->getEmail());
                $object->setAnswerAuthorName($customerModel->getFirstname().' '.$customerModel->getLastname());
            }
        }

        return $this;
    }

    /**
     * Retrieve the email of admin user
     *
     * @param string $default
     * @return string
     */
    public function getAdminEmail($default = 'phpcuong@example.com')
    {
        return $this->authSession->getEmail($default);
    }

    /**
     * Retrieve the name of admin user
     *
     * @param string $default
     * @return string
     */
    public function getAdminName($default = 'phpcuong')
    {
        return $this->authSession->getName($default);
    }

    /**
     * Retrieve the administrator code
     *
     * @return int
     */
    public function getAdministratorCode()
    {
        return $this->userType->getAdministratorCode();
    }

    /**
     * Retrieve the guest code
     *
     * @return int
     */
    public function getGuestCode()
    {
        return $this->userType->getGuestCode();
    }

    /**
     * Retrieve the customer code
     *
     * @return int
     */
    public function getCustomerCode()
    {
        return $this->userType->getCustomerCode();
    }

    /**
     * Validate Question ID
     *
     * @param int $questionId
     * @return void
     */
    public function validateQuestionId($value, $label)
    {
        $this->validationRules->validateEmptyValue($value, $label);

        $questionInfo = $this->question->load((int) $value);
        if (!$questionInfo->getQuestionId()) {
            throw new LocalizedException(
                __('The question ID is "%1" no longer exists.', $value)
            );
        }
    }
}
