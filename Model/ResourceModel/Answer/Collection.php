<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-15 03:21:00
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-18 07:07:54
 */

namespace PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Answer;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'answer_id';

    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('PHPCuong\ProductQuestionAndAnswer\Model\Answer', 'PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Answer');
    }

    /**
     * Add status filter
     *
     * @param int $status
     * @return $this
     */
    public function addStatusFilter($status)
    {
        $this->addFilter('answer_status_id', $this->getConnection()->quoteInto('main_table.answer_status_id=?', $status), 'string');
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
        $this->addFilter('answer_visibility_id', $this->getConnection()->quoteInto('main_table.answer_visibility_id=?', $visibility), 'string');
        return $this;
    }
}
