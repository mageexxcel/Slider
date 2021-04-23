<?php
namespace Adtones\Stock\Model\Data;

use Adtones\Stock\Api\Data\ReportInterface;

class Report extends \Magento\Framework\Api\AbstractExtensibleObject implements ReportInterface
{

    /**
     * Get stock_id
     * @return string|null
     */
    public function getStockId()
    {
        return $this->_get(self::STOCK_ID);
    }

    /**
     * Set stock_id
     * @param string $stockId
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setStockId($stockId)
    {
        return $this->setData(self::STOCK_ID, $stockId);
    }

    /**
     * Get edition_id
     * @return string|null
     */
    public function getEditionId()
    {
        return $this->_get(self::EDITION_ID);
    }

    /**
     * Set edition_id
     * @param string $edition_id
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setEditionId($edition_id)
    {
        return $this->setData(self::EDITION_ID, $edition_id);
    }

    /**
     * Get Thumbnail
     * @return string|null
     */
    public function getThumbnail()
    {
        return $this->_get(self::THUMBNAIL);
    }

    /**
     * Set Thumbnail
     * @param string $thumbnail
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setThumbnail($thumbnail)
    {
        return $this->setData(self::THUMBNAIL, $thumbnail);
    }

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId()
    {
        return $this->_get(self::PRODUCT_ID);
    }

    /**
     * Set product_id
     * @param string $product_id
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setProductId($product_id)
    {
        return $this->setData(self::PRODUCT_ID, $product_id);
    }
    /**
     * Get sku
     * @return string|null
     */
    public function getSku()
    {
        return $this->_get(self::SKU);
    }

    /**
     * Set sku
     * @param string $sku
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }
    /**
     * Get artist
     * @return string|null
     */
    public function getArtist()
    {
        return $this->_get(self::ARTIST);
    }

    /**
     * Set artist
     * @param string $artist
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setArtist($artist)
    {
        return $this->setData(self::ARTIST, $artist);
    }

    /**
     * Get work
     * @return string|null
     */
    public function getWork()
    {
        return $this->_get(self::WORK);
    }

    /**
     * Set work
     * @param string $work
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setWork($work)
    {
        return $this->setData(self::WORK, $work);
    }
    
    /**
     * Get Availability
     * @return string|null
     */
    public function getAvailability()
    {
        return $this->_get(self::AVAILABILITY);
    }

    /**
     * Set Availability
     * @param string $availability
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setAvailability($availability)
    {
        return $this->setData(self::AVAILABILITY, $availability);
    }
    /**
     * Get Condition
     * @return string|null
     */
    public function getCondition()
    {
        return $this->_get(self::CONDITION);
    }

    /**
     * Set Condition
     * @param string $condition
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setCondition($condition)
    {
        return $this->setData(self::CONDITION, $condition);
    }
    /**
     * Get Ownership
     * @return string|null
     */
    public function getOwnership()
    {
        return $this->_get(self::OWNERSHIP);
    }

    /**
     * Set Ownership
     * @param string $ownership
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setOwnership($ownership)
    {
        return $this->setData(self::OWNERSHIP, $ownership);
    }
    /**
     * Get Location
     * @return string|null
     */
    public function getLocation()
    {
        return $this->_get(self::LOCATION);
    }

    /**
     * Set Location
     * @param string $location
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setLocation($location)
    {
        return $this->setData(self::LOCATION, $location);
    }
    /**
     * Get EditionNumber
     * @return string|null
     */
    public function getEditionNumber()
    {
        return $this->_get(self::EDITION_NUMBER);
    }

    /**
     * Set EditionNumber
     * @param string $edition_number
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setEditionNumber($edition_number)
    {
        return $this->setData(self::EDITION_NUMBER, $edition_number);
    }
    /**
     * Get StockType
     * @return string|null
     */
    public function getStockType()
    {
        return $this->_get(self::STOCK_TYPE);
    }

    /**
     * Set StockType
     * @param string $stock_type
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setStockType($stock_type)
    {
        return $this->setData(self::STOCK_TYPE, $stock_type);
    }
    /**
     * Get PackingRequirements
     * @return string|null
     */
    public function getPackingRequirements()
    {
        return $this->_get(self::PACKING_REQUIREMENTS);
    }

    /**
     * Set PackingRequirements
     * @param string $packing_requirements
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setPackingRequirements($packing_requirements)
    {
        return $this->setData(self::PACKING_REQUIREMENTS, $packing_requirements);
    }
    /**
     * Get Framed
     * @return string|null
     */
    public function getFramed()
    {
        return $this->_get(self::FRAMED);
    }

    /**
     * Set Framed
     * @param string $framed
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setFramed($framed)
    {
        return $this->setData(self::FRAMED, $framed);
    }
    /**
     * Get StockItemId
     * @return string|null
     */
    public function getStockItemId()
    {
        return $this->_get(self::STOCK_ITEM_ID);
    }

    /**
     * Set stock_item_id
     * @param string $stock_item_id
     * @return \Adtones\Stock\Api\Data\ReportInterface
     */
    public function setStockItemId($stock_item_id)
    {
        return $this->setData(self::STOCK_ITEM_ID, $stock_item_id);
    }
   
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Adtones\Stock\Api\Data\ReportExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Adtones\Stock\Api\Data\ReportExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Adtones\Stock\Api\Data\ReportExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
