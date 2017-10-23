<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-05 04:57:55
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-23 13:00:46
 */

namespace PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Exception\LocalizedException;

class Question extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * ValidationRules
     *
     * @var \PHPCuong\ProductQuestionAndAnswer\Model\ValidationRules
     */
    protected $validationRules;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * Escaper
     *
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $productStatus;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $productVisibility;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \PHPCuong\ProductQuestionAndAnswer\Model\ValidationRules $validationRules
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     * @param \Magento\Framework\Escaper $escaper
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \PHPCuong\ProductQuestionAndAnswer\Model\ValidationRules $validationRules,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Framework\Escaper $escaper
    ) {
        $this->validationRules = $validationRules;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
        $this->escaper = $escaper;
        parent::__construct($context);
    }

    /**
     * construct
     * @return void
     */
    protected function _construct()
    {
        $this->_init('phpcuong_product_question', 'question_id');
    }

    /**
     * After save callback
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return parent
     */
    protected function _afterSave(AbstractModel $object)
    {
        $this->saveQuestionStore($object);
        return parent::_afterSave($object);
    }

    /**
     * Save the question_id and store_id in the table phpcuong_product_question_store
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function saveQuestionStore(AbstractModel $object)
    {
        $questionId = (int) $object->getData('question_id');
        $storeIds = (array) $object->getData('store_ids');

        if (empty($storeIds)) {
            $storeIds = [\Magento\Store\Model\Store::DEFAULT_STORE_ID];
        }

        if ($questionId) {
            $adapter = $this->getConnection();
            $adapter->delete($this->getTable('phpcuong_product_question_store'), ['question_id =?' => $questionId]);
            foreach ($storeIds as $storeId) {
                $data = [
                    'question_id' => $questionId,
                    'store_view_id' => $storeId
                ];
                $adapter->insertMultiple($this->getTable('phpcuong_product_question_store'), $data);
            }
        }

        return $this;
    }

    /**
     * Save the question_id and product_id in the table phpcuong_product_question_sharing
     *
     * @param int $questionId
     * @param array $productIds
     * @return $this
     */
    public function saveProductIdToQuestionSharing($questionId = null, $productIds = [])
    {
        if ($questionId) {
            $adapter = $this->getConnection();
            $adapter->delete($this->getTable('phpcuong_product_question_sharing'), ['question_id =?' => $questionId]);
            foreach ($productIds as $productId) {
                if (!empty($productId)) {
                    $data = [
                        'question_id' => $questionId,
                        'entity_pk_value' => $productId
                    ];
                    $adapter->insertOnDuplicate($this->getTable('phpcuong_product_question_sharing'), $data);
                }
            }
        }

        return $this;
    }

    /**
     * Get the products were shared the question
     *
     * @param int $questionId
     * @return array
     */
    public function getProductsWereSharedQuestion($questionId)
    {
        if ($questionId) {
            $question = $this->getConnection()
                ->select()
                ->from($this->getTable('phpcuong_product_question'), ['entity_pk_value'])
                ->where('question_id = :question_id');
            $entityPkValue = $this->getConnection()->fetchCol($question, [':question_id' => $questionId]);
            $productId = 0;
            if (!empty($entityPkValue)) {
                $productId = (int) $entityPkValue[0];
            }
            $collection = $this->productCollectionFactory->create()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()])
                ->addAttributeToFilter('visibility', ['in' => $this->productVisibility->getVisibleInSiteIds()])
                ->addFieldToFilter('entity_id', ['neq' => $productId])
                ->joinField(
                    'entity_pk_value',
                    'phpcuong_product_question_sharing',
                    'entity_pk_value',
                    'entity_pk_value=entity_id',
                    'question_id='.$questionId,
                    'right'
                )->getData();

            $productIds = [];
            foreach ($collection as $result) {
                $productIds[] = $result['entity_pk_value'];
            }
            return $productIds;
        }
        return [];
    }

    /**
     * Update the question(s) visibility
     *
     * @param array $questionIds
     * @param int $visibility
     * @param string $column
     * @return void
     */
    public function massUpdateVisibility($questionIds, $visibility, $column = 'question_visibility_id')
    {
        if (!empty($visibility)) {
            $adapter = $this->getConnection();
            foreach ($questionIds as $questionId) {
                $adapter->update(
                    $this->getMainTable(),
                    [$column => (int) $visibility],
                    ['question_id = ?' => (int) $questionId]
                );
            }
        }
    }

    /**
     * Update the question(s) status
     *
     * @param array $questionIds
     * @param int $statusCode
     * @return void
     */
    public function massUpdateStatus($questionIds, $statusCode)
    {
        $this->massUpdateVisibility($questionIds, $statusCode, 'question_status_id');
    }

    /**
     * Before save callback
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return parent
     */
    protected function _beforeSave(AbstractModel $object)
    {
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
        $object->setQuestionDetail(trim(strip_tags($object->getQuestionDetail())));
        $object->setQuestionAuthorName(trim($object->getQuestionAuthorName()));
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
        $this->validationRules->validateEmptyValue($object->getQuestionDetail(), 'Content of Question');
        $this->validationRules->validateEmptyValue($object->getQuestionAuthorName(), 'Author Name');
        $this->validationRules->validateEmptyValue($object->getEntityPkValue(), 'Product ID');
        $this->validationRules->validateEmail($object->getQuestionAuthorEmail(), 'Author Email');
        $this->validationRules->validateVisibility($object->getQuestionVisibilityId());
        $this->validationRules->validateStatus($object->getQuestionStatusId());
        $this->validationRules->validateUserType($object->getQuestionUserTypeId(), 'question');
        $this->validationRules->validateIntegerNumber($object->getQuestionLikes(), 'Total number of likes');
        $this->validationRules->validateIntegerNumber($object->getQuestionDislikes(), 'Total number of dislikes');

        return $this;
    }

    /**
     * Method to run after load
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return parent
     */
    protected function _afterLoad(AbstractModel $object)
    {
        $questionStore = $this->getConnection()
            ->select()
            ->from($this->getTable('phpcuong_product_question_store'), ['store_view_id'])
            ->where('question_id = :question_id');

        $stores = $this->getConnection()->fetchCol($questionStore, [':question_id' => $object->getQuestionId()]);

        if ($stores) {
            $object->setData('store_ids', $stores);
        }

        return parent::_afterLoad($object);
    }
}
