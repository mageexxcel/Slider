<?php
namespace Adtones\Stock\Ui\Component\Renderer\Form;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Options tree for "Categories" field
 */
class OwnershipOptions implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getOwnershipOptionArray();
    }

    /**
     * Retrieve categories tree
     *
     * @return array
     */
    protected function getOwnershipOptionArray()
    {
        // Options for availability
        $this->stockTypeOptionArray = null;
        $stockTypeOption['1']['label'] = 'Consigned from artist';
        $stockTypeOption['1']['value'] = 'Consigned from artist';
        $stockTypeOption['2']['label'] = 'Consigned from other';
        $stockTypeOption['2']['value'] = 'Consigned from other';
        $stockTypeOption['3']['label'] = 'Exclusive to TAG';
        $stockTypeOption['3']['value'] = 'Exclusive to TAG';
        $stockTypeOption['4']['label'] = 'Published by TAG';
        $stockTypeOption['4']['value'] = 'Published by TAG';
        $stockTypeOption['5']['label'] = 'Unknown';
        $stockTypeOption['5']['value'] = 'Unknown';
        $stockTypeOption['6']['label'] = 'Not for sale';
        $stockTypeOption['6']['value'] = 'Not for sale';
        $stockTypeOption['7']['label'] = 'Wholly Owned';
        $stockTypeOption['7']['value'] = 'Wholly Owned';
        $this->stockTypeOptionArray = $stockTypeOption;

        return $this->stockTypeOptionArray;
    }
}