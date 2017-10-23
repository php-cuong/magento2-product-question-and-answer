<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date:   2017-10-15 03:57:37
 * @Last Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last Modified time: 2017-10-15 04:13:34
 */

namespace PHPCuong\ProductQuestionAndAnswer\Model;

class Product
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $productStatus;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $productVisibility;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility
    ) {
        $this->storeManager = $storeManager;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
    }

    /**
     * Retrieve ID and Name of the first default product
     *
     * @param int $productId
     * @return \Magento\Catalog\Api\Data\ProductInterface $product
     */
    public function getFirstDefaultProduct($productId)
    {
        $productCollection = $this->productCollectionFactory->create()
            ->addStoreFilter($this->storeManager->getStore()->getStoreId())
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()])
            ->addAttributeToFilter('visibility', ['in' => $this->productVisibility->getVisibleInSiteIds()]);
        if ((int) $productId > 0) {
            $productCollection = $productCollection->addFieldToFilter('entity_id', $productId);
        }
        return $productCollection->getFirstItem();
    }
}
