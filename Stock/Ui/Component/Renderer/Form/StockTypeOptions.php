<?php
namespace Adtones\Stock\Ui\Component\Renderer\Form;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Options tree for "Categories" field
 */
class StockTypeOptions implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getStockTypeOptionArray();
    }

    /**
     * Retrieve categories tree
     *
     * @return array
     */
    protected function getStockTypeOptionArray()
    {
        // Options for availability
        $this->ownershipOptionArray = null;
        $ownershipOption['1']['label'] = 'Primary';
        $ownershipOption['1']['value'] = 'Primary';
        $ownershipOption['2']['label'] = 'Drawing';
        $ownershipOption['2']['value'] = 'Drawing';
        $ownershipOption['3']['label'] = 'Merchandise';
        $ownershipOption['3']['value'] = 'Merchandise';
        $ownershipOption['4']['label'] = 'Original';
        $ownershipOption['4']['value'] = 'Original';
        $ownershipOption['5']['label'] = 'Painting';
        $ownershipOption['5']['value'] = 'Painting';
        $ownershipOption['6']['label'] = 'Photography';
        $ownershipOption['6']['value'] = 'Photography';
        $ownershipOption['7']['label'] = 'Print';
        $ownershipOption['7']['value'] = 'Print';
        $ownershipOption['8']['label'] = 'Sculpture';
        $ownershipOption['8']['value'] = 'Sculpture';
        $ownershipOption['9']['label'] = 'Other';
        $ownershipOption['9']['value'] = 'Other';
        $this->ownershipOptionArray = $ownershipOption;

        return $this->ownershipOptionArray;
    }
}