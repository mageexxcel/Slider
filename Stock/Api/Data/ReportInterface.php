<?php


namespace Adtones\Stock\Api\Data;

interface ReportInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const THUMBNAIL            = 'thumbnail';
    const EDITION_ID           = 'edition_id';
    const PRODUCT_ID           = 'product_id';
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
    const STOCK_ITEM_ID        = 'stock_item_id';

    /**
     * Get edition_id
     * @return string|null
     */
    public function getThumbnail();

    /**
     * Set edition_id
     * @param string $editionId
     * @return \Adtones\Stock\Api\Data\ReportInterface
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
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setEditionId($edition_id);

    /**
     * Get edition_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set edition_id
     * @param string $editionId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setProductId($product_id);

    /**
     * Get edition_id
     * @return string|null
     */
    public function getSku();

    /**
     * Set edition_id
     * @param string $editionId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setSku($sku);

    /**
     * Get edition_id
     * @return string|null
     */
    public function getArtist();

    /**
     * Set edition_id
     * @param string $editionId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setArtist($artist);

    /**
     * Get edition_id
     * @return string|null
     */
    public function getWork();

    /**
     * Set edition_id
     * @param string $editionId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setWork($work);

    /**
     * Get entity_id
     * @return string|null
     */
    public function getAvailability();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setAvailability($availability);

    /**
     * Get entity_id
     * @return string|null
     */
    public function getCondition();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setCondition($condition);

    /**
     * Get entity_id
     * @return string|null
     */
    public function getOwnership();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setOwnership($ownership);

    /**
     * Get entity_id
     * @return string|null
     */
    public function getLocation();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setLocation($location);

    /**
     * Get entity_id
     * @return string|null
     */
    public function getEditionNumber();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setEditionNumber($edition_number);

    /**
     * Get entity_id
     * @return string|null
     */
    public function getStockType();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setStockType($stock_type);

    /**
     * Get entity_id
     * @return string|null
     */
    public function getPackingRequirements();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setPackingRequirements($packing_requirements);

    /**
     * Get entity_id
     * @return string|null
     */
    public function getFramed();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setFramed($framed);

    /**
     * Get stock_item_id
     * @return string|null
     */
    public function getStockItemId();

    /**
     * Set stock_item_id
     * @param string $entityId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setStockItemId($stock_item_id);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Adtones\Stock\Api\Data\ReportExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Adtones\Stock\Api\Data\ReportExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Adtones\Stock\Api\Data\ReportExtensionInterface $extensionAttributes
    );
}
