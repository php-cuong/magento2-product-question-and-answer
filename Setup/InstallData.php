<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-05 03:25:27
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-23 08:20:19
 */

namespace PHPCuong\ProductQuestionAndAnswer\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $pageFactory;

    /**
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Cms\Model\PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        //Fill table phpcuong_product_question_status
        $questionAnswerStatuses = [
            \PHPCuong\ProductQuestionAndAnswer\Model\Status::STATUS_APPROVED => 'Approved',
            \PHPCuong\ProductQuestionAndAnswer\Model\Status::STATUS_PENDING => 'Pending',
            \PHPCuong\ProductQuestionAndAnswer\Model\Status::STATUS_NOT_APPROVED => 'Not Approved'
        ];
        foreach ($questionAnswerStatuses as $k => $v) {
            $bind = ['status_id' => $k, 'status_code' => $v];
            $installer->getConnection()->insertOnDuplicate($installer->getTable('phpcuong_product_question_status'), $bind);
        }

        //Fill table phpcuong_product_question_visibility
        $questionAnswerStatuses = [
            \PHPCuong\ProductQuestionAndAnswer\Model\Visibility::VISIBILITY_NOT_VISIBLE => 'Not visible',
            \PHPCuong\ProductQuestionAndAnswer\Model\Visibility::VISIBILITY_VISIBLE => 'Visible'
        ];
        foreach ($questionAnswerStatuses as $k => $v) {
            $bind = ['visibility_id' => $k, 'visibility_code' => $v];
            $installer->getConnection()->insertOnDuplicate($installer->getTable('phpcuong_product_question_visibility'), $bind);
        }

        //Fill table phpcuong_product_question_user_type
        $questionAnswerStatuses = [
            \PHPCuong\ProductQuestionAndAnswer\Model\UserType::USER_TYPE_GUEST => 'Guest',
            \PHPCuong\ProductQuestionAndAnswer\Model\UserType::USER_TYPE_CUSTOMER => 'Customer',
            \PHPCuong\ProductQuestionAndAnswer\Model\UserType::USER_TYPE_ADMINISTRATOR => 'Administrator'
        ];
        foreach ($questionAnswerStatuses as $k => $v) {
            $bind = ['user_type_id' => $k, 'user_type_code' => $v];
            $installer->getConnection()->insertOnDuplicate($installer->getTable('phpcuong_product_question_user_type'), $bind);
        }

        //add the product question rules page
        $this->pageFactory->create()
            ->load('product-question-rules', 'identifier')
            ->addData(
                [
                    'title' => 'Product Question Rules',
                    'page_layout' => '1column',
                    'meta_keywords' => 'Product Question Rules',
                    'meta_description' => 'Product Question Rules',
                    'identifier' => 'product-question-rules',
                    'content_heading' => 'Product Question Rules',
                    'content' => 'Content of the product question rule'
                ]
            )->setStores(
                [\Magento\Store\Model\Store::DEFAULT_STORE_ID]
            )->save();
    }
}
