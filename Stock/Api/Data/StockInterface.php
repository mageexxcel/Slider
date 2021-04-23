<?php


namespace Adtones\Stock\Api\Data;

interface StockInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const THUMBNAIL            = 'thumbnail';
    const EDITION_ID           = 'edition_id';
    const SKU                  = 'sku';
    const ARTIST               = 'artist';
    const WORK                 = 'work';
    const AVAILABILITY         = 'availability';
    const CONDITION            = 'condition';
    const OWNERSHIP            = 'ownership';
    const LOCATION             = 'location';
    const EDITION_NUMBER       = 'edition_number';
    const STOCK_TYPE           = 'stock_type';
    const PACKING_REQUIREMENTS = 'packing_requirements';
    const FRAMED               = 'framed';
    const PRODUCT_ID           = 'product_id';
    const PRODUCT_NAME         = 'product_name';
    const ALLOCATED            = 'allocated';
    const ALLOCATED_ORDER_ID   = 'allocated_order_id';
    const SAME_PRODUCT_NUMBER  = 'sameProductNumber';

    /**
     * Get thumbnail
     * @return string|null
     */
    public function getThumbnail();

    /**
     * Set thumbnail
     * @param string $editionId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setThumbnail($thumbnail);

    /**
     * Get edition_id
     * @return string|null
     */
    public function getEditionId();

    /**
     * Set edition_id
     * @param string $editionId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setEditionId($edition_id);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $editionId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setProductId($product_id);

    /**
     * Get sku
     * @return string|null
     */
    public function getSku();

    /**
     * Set sku
     * @param string $editionId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setSku($sku);

    /**
     * Get artist
     * @return string|null
     */
    public function getArtist();

    /**
     * Set artist
     * @param string $editionId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setArtist($artist);

    /**
     * Get work
     * @return string|null
     */
    public function getWork();

    /**
     * Set work
     * @param string $editionId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setWork($work);

    /**
     * Get availability
     * @return string|null
     */
    public function getAvailability();

    /**
     * Set availability
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setAvailability($availability);

    /**
     * Get condition
     * @return string|null
     */
    public function getCondition();

    /**
     * Set condition
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setCondition($condition);

    /**
     * Get ownership
     * @return string|null
     */
    public function getOwnership();

    /**
     * Set ownership
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setOwnership($ownership);

    /**
     * Get location
     * @return string|null
     */
    public function getLocation();

    /**
     * Set location
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setLocation($location);

    /**
     * Get edition_number
     * @return string|null
     */
    public function getEditionNumber();

    /**
     * Set edition_number
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setEditionNumber($edition_number);

    /**
     * Get stock_type
     * @return string|null
     */
    public function getStockType();

    /**
     * Set stock_type
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setStockType($stock_type);

    /**
     * Get packing_requirements
     * @return string|null
     */
    public function getPackingRequirements();

    /**
     * Set packing_requirements
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setPackingRequirements($packing_requirements);

    /**
     * Get framed
     * @return string|null
     */
    public function getFramed();

    /**
     * Set framed
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setFramed($framed);

    /**
     * Get productName
     * @return string|null
     */
    public function getProductName();

    /**
     * Set productName
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setProductName($productName);

    /**
     * Get allocated
     * @return string|null
     */
    public function getAllocated();

    /**
     * Set allocated
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setAllocated($allocated);

    /**
     * Get allocatedOrderId
     * @return string|null
     */
    public function getAllocatedOrderId();

    /**
     * Set allocatedOrderId
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setAllocatedOrderId($allocatedOrderId);

    /**
     * Get sameProductNumber
     * @return string|null
     */
    public function getSameProductNumber();

    /**
     * Set sameProductNumber
     * @param string $sameProductNumber
     * @return \Adtones\Stock\Api\Data\EditionInterface
     */
    public function setSameProductNumber($sameProductNumber);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Adtones\Stock\Api\Data\StockExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Adtones\Stock\Api\Data\StockExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Adtones\Stock\Api\Data\StockExtensionInterface $extensionAttributes
    );
}
