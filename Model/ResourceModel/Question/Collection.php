<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-05 05:00:40
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-21 16:32:53
 */

namespace PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Question;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'question_id';

    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('PHPCuong\ProductQuestionAndAnswer\Model\Question', 'PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Question');
    }

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        foreach ($this->_items as $item) {
            $item->setStoreId($this->getStoreViewIds($item->getQuestionId()));
        }
        return parent::_afterLoad();
    }

    /**
     * Get the store ids
     *
     * @param int $questionId
     * @return array
     */
    protected function getStoreViewIds($questionId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('phpcuong_product_question_store')
        )
        ->where('question_id =?', $questionId)
        ->group('store_view_id');
        $result = $connection->fetchAll($select);
        $storesData = [];
        if ($result) {
            foreach ($result as $storeData) {
                $storesData[] = $storeData['store_view_id'];
            }
        }
        return $storesData;
    }

    /**
     * Add store filter
     *
     * @param int|int[] $storeId
     * @return $this
     */
    public function addStoreFilter($storeId)
    {
        $inCond = $this->getConnection()->prepareSqlCondition('store.store_view_id', ['in' => $storeId]);
        $this->getSelect()->join(
            ['store' => $this->getTable('phpcuong_product_question_store')],
            'main_table.question_id=store.question_id',
            ['store.store_view_id']
        );
        $this->getSelect()->where($inCond);
        return $this;
    }

    /**
     * Add status filter
     *
     * @param int $status
     * @return $this
     */
    public function addStatusFilter($status)
    {
        $this->addFieldToFilter('question_status_id',  $status);
        return $this;
    }

    /**
     * Add visibility filter
     *
     * @param int $visibility
     * @return $this
     */
    public function addVisibilityFilter($visibility)
    {
        $this->addFieldToFilter('question_visibility_id', $visibility);
        return $this;
    }

    /**
     * Add product filter
     *
     * @param int $productId
     * @return $this
     */
    public function addProductIdFilter($productId)
    {
        $this->getSelect()->joinLeft(
            ['shared' => $this->getTable('phpcuong_product_question_sharing')],
            'main_table.question_id = shared.question_id',
            ['entity_pk_value']
        )->orWhere(
            'shared.entity_pk_value = ?',
            $productId
        )->group(
            'main_table.question_id'
        );

        // $this->printLogQuery(true);

        return $this;
    }

    /**
     * Add customer filter
     *
     * @param int|string $customerId
     * @return $this
     */
    public function addCustomerFilter($customerId)
    {
        $this->addFieldToFilter('customer_id', $customerId);
        return $this;
    }

    /**
     * Set date order
     *
     * @param string $dir
     * @return $this
     */
    public function setDateOrder($dir = 'DESC')
    {
        $this->setOrder('main_table.question_created_at', $dir);
        return $this;
    }

    /**
     * Set product filter
     *
     * @return $this
     */
    public function setProductName()
    {
        $this->getSelect()->joinLeft(
            ['product' => $this->getTable('catalog_product_entity_varchar')],
            'main_table.entity_pk_value = product.entity_id',
            ['product.value as product_name']
        )->where(
            'product.attribute_id = ?',
            73
        );

        return $this;
    }
}

