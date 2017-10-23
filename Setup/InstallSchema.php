<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-05 02:32:05
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-17 15:08:54
 */

namespace PHPCuong\ProductQuestionAndAnswer\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use PHPCuong\ProductQuestionAndAnswer\Model\UserType;
use PHPCuong\ProductQuestionAndAnswer\Model\Status;
use PHPCuong\ProductQuestionAndAnswer\Model\Visibility;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'phpcuong_product_question_status'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('phpcuong_product_question_status'))
            ->addColumn(
                'status_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Status id'
            )
            ->addColumn(
                'status_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false],
                'Status code'
            )
            ->setComment('Product Question and Answer statuses');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'phpcuong_product_question_user_type'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('phpcuong_product_question_user_type'))
            ->addColumn(
                'user_type_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'User Type id'
            )
            ->addColumn(
                'user_type_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false],
                'User Type code'
            )
            ->setComment('Product Question and Answer User Types');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'phpcuong_product_question_visibility'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('phpcuong_product_question_visibility'))
            ->addColumn(
                'visibility_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Visibility id'
            )
            ->addColumn(
                'visibility_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false],
                'Visibility code'
            )
            ->setComment('Product Question and Answer Visibilities');
        $installer->getConnection()->createTable($table);

        /**
         * Create the table 'phpcuong_product_question'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('phpcuong_product_question')
        )->addColumn(
            'question_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            null,
            ['identity' => true, 'nullable' => false, 'unsigned' => true, 'primary' => true],
            'Question ID'
        )->addColumn(
            'question_detail',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            \Magento\Framework\DB\Ddl\Table::MAX_TEXT_SIZE,
            ['nullable' => true, 'nullable' => false],
            'Content of question'
        )->addColumn(
            'question_author_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['default' => null, 'nullable' => false],
            'Question Author Name'
        )->addColumn(
            'question_author_email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['default' => null, 'nullable' => false],
            'Email of asker'
        )->addColumn(
            'question_status_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => Status::STATUS_PENDING],
            'Status code'
        )->addColumn(
            'question_user_type_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => UserType::USER_TYPE_GUEST],
            'User code'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true, 'default' => null],
            'Customer ID'
        )->addColumn(
            'question_visibility_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => Visibility::VISIBILITY_VISIBLE],
            'Visibility code'
        )->addColumn(
            'question_store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'default' => '0'],
            'Question asked in the store id'
        )->addColumn(
            'question_likes',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Total number of likes'
        )->addColumn(
            'question_dislikes',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Total number of dislikes'
        )->addColumn(
            'total_answers',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Total number of answers'
        )->addColumn(
            'pending_answers',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Total number of pending answers'
        )->addColumn(
            'entity_pk_value',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Product ID'
        )->addColumn(
            'question_created_by',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => UserType::USER_TYPE_GUEST],
            'User code'
        )->addColumn(
            'question_created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Question create date'
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_question', 'question_status_id', 'phpcuong_product_question_status', 'status_id'),
            'question_status_id',
            $installer->getTable('phpcuong_product_question_status'),
            'status_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_question', 'question_user_type_id', 'phpcuong_product_question_user_type', 'user_type_id'),
            'question_user_type_id',
            $installer->getTable('phpcuong_product_question_user_type'),
            'user_type_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_question', 'question_created_by', 'phpcuong_product_question_user_type', 'user_type_id'),
            'question_created_by',
            $installer->getTable('phpcuong_product_question_user_type'),
            'user_type_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_question', 'question_visibility_id', 'phpcuong_product_question_visibility', 'visibility_id'),
            'question_visibility_id',
            $installer->getTable('phpcuong_product_question_visibility'),
            'visibility_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_question', 'question_store_id', 'store', 'store_id'),
            'question_store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_question', 'entity_pk_value', 'catalog_product_entity', 'entity_id'),
            'entity_pk_value',
            $installer->getTable('catalog_product_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_question', 'customer_id', 'customer_entity', 'entity_id'),
            'customer_id',
            $installer->getTable('customer_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('phpcuong_product_question'),
                ['question_detail', 'question_author_name', 'question_author_email'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['question_detail', 'question_author_name', 'question_author_email'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Question information of product'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'phpcuong_product_question_store'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('phpcuong_product_question_store')
        )->addColumn(
            'store_view_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'default' => '0'],
            'Question shared in the store id'
        )->addColumn(
            'question_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            null,
            ['nullable' => false, 'unsigned' => true],
            'Question ID'
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_question_store', 'store_view_id', 'store', 'store_id'),
            'store_view_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_NULL
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_question_store', 'question_id', 'phpcuong_product_question', 'question_id'),
            'question_id',
            $installer->getTable('phpcuong_product_question'),
            'question_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Product Question To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create the table 'phpcuong_product_answer'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('phpcuong_product_answer')
        )->addColumn(
            'answer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            null,
            ['identity' => true, 'nullable' => false, 'unsigned' => true, 'primary' => true],
            'Answer ID'
        )->addColumn(
            'answer_detail',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            \Magento\Framework\DB\Ddl\Table::MAX_TEXT_SIZE,
            ['nullable' => false],
            'Content of answer'
        )->addColumn(
            'answer_author_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['default' => null, 'nullable' => false],
            'author_name of respondent'
        )->addColumn(
            'answer_author_email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['default' => null, 'nullable' => false],
            'Email of respondent'
        )->addColumn(
            'question_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            null,
            ['nullable' => false, 'unsigned' => true],
            'Question ID'
        )->addColumn(
            'answer_status_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => Status::STATUS_PENDING],
            'Status code'
        )->addColumn(
            'answer_user_type_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => UserType::USER_TYPE_GUEST],
            'User code'
        )->addColumn(
            'answer_user_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true, 'default' => null],
            'User ID'
        )->addColumn(
            'answer_created_by',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => UserType::USER_TYPE_GUEST],
            'User code'
        )->addColumn(
            'answer_visibility_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => Visibility::VISIBILITY_VISIBLE],
            'Visibility code'
        )->addColumn(
            'answer_likes',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Total number of likes'
        )->addColumn(
            'answer_dislikes',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Total number of dislikes'
        )->addColumn(
            'answer_created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Answer create date'
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_answer', 'answer_status_id', 'phpcuong_product_question_status', 'status_id'),
            'answer_status_id',
            $installer->getTable('phpcuong_product_question_status'),
            'status_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_answer', 'answer_user_type_id', 'phpcuong_product_question_user_type', 'user_type_id'),
            'answer_user_type_id',
            $installer->getTable('phpcuong_product_question_user_type'),
            'user_type_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_answer', 'answer_created_by', 'phpcuong_product_question_user_type', 'user_type_id'),
            'answer_created_by',
            $installer->getTable('phpcuong_product_question_user_type'),
            'user_type_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_answer', 'answer_visibility_id', 'phpcuong_product_question_visibility', 'visibility_id'),
            'answer_visibility_id',
            $installer->getTable('phpcuong_product_question_visibility'),
            'visibility_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_NO_ACTION
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_answer', 'question_id', 'phpcuong_product_question', 'question_id'),
            'question_id',
            $installer->getTable('phpcuong_product_question'),
            'question_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('phpcuong_product_answer'),
                ['answer_detail', 'answer_author_name', 'answer_author_email'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['answer_detail', 'answer_author_name', 'answer_author_email'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Answer\'s information of a question on a product'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'phpcuong_product_question_sharing'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('phpcuong_product_question_sharing')
        )->addColumn(
            'entity_pk_value',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Product ID'
        )->addColumn(
            'question_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            null,
            ['nullable' => false, 'unsigned' => true],
            'Question ID'
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_question_sharing', 'entity_pk_value', 'catalog_product_entity', 'entity_id'),
            'entity_pk_value',
            $installer->getTable('catalog_product_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('phpcuong_product_question_sharing', 'question_id', 'phpcuong_product_question', 'question_id'),
            'question_id',
            $installer->getTable('phpcuong_product_question'),
            'question_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Product Question To Product Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
