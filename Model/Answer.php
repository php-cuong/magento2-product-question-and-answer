<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-15 03:17:58
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-17 04:46:39
 */

namespace PHPCuong\ProductQuestionAndAnswer\Model;

class Answer extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'phpcuong_product_answer';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('PHPCuong\ProductQuestionAndAnswer\Model\ResourceModel\Answer');
    }

    /**
     * Update the answer(s) visibility
     *
     * @param array $answerIds
     * @param int $visibility
     * @return void
     */
    public function massUpdateVisibility($answerIds, $visibility)
    {
        foreach ($answerIds as $answerId) {
            $this->load((int) $answerId)->setAnswerVisibilityId((int) $visibility)->save();
        }
    }

    /**
     * Update the answer(s) status
     *
     * @param array $answerIds
     * @param int $status
     * @return void
     */
    public function massUpdateStatus($answerIds, $status)
    {
        foreach ($answerIds as $answerId) {
            $this->load((int) $answerId)->setAnswerStatusId((int) $status)->save();
        }
    }

    /**
     * Delete the answer(s)
     *
     * @param array $answerIds
     * @return void
     */
    public function massDelete($answerIds)
    {
        foreach ($answerIds as $answerId) {
            $this->load((int) $answerId)->delete();
        }
    }

    /**
     * Retrieve the administrator code
     *
     * @return int
     */
    public function getAdministratorCode()
    {
        return \PHPCuong\ProductQuestionAndAnswer\Model\UserType::USER_TYPE_ADMINISTRATOR;
    }
}
