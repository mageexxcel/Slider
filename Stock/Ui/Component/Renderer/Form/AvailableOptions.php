<?php
namespace Adtones\Stock\Ui\Component\Renderer\Form;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Options tree for "Categories" field
 */
class AvailableOptions implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getAvailableOptionArray();
    }

    /**
     * Retrieve categories tree
     *
     * @return array
     */
    protected function getAvailableOptionArray()
    {
        // Options for availability
        $this->availableOptionArray = null;
        $availableOption['1']['label'] = 'Available';
        $availableOption['1']['value'] = 'Available';
        $availableOption['2']['label'] = 'Allocated';
        $availableOption['2']['value'] = 'Allocated';
        $availableOption['3']['label'] = 'Awaiting stock';
        $availableOption['3']['value'] = 'Awaiting stock';
        $availableOption['4']['label'] = 'Consigned out';
        $availableOption['4']['value'] = 'Consigned out';
        $availableOption['5']['label'] = 'Held';
        $availableOption['5']['value'] = 'Held';
        $availableOption['6']['label'] = 'Not for sale';
        $availableOption['6']['value'] = 'Not for sale';
        $availableOption['7']['label'] = 'Return to consigner';
        $availableOption['7']['value'] = 'Return to consigner';
        $availableOption['8']['label'] = 'Sold';
        $availableOption['8']['value'] = 'Sold';
        $availableOption['9']['label'] = 'Unknown';
        $availableOption['9']['value'] = 'Unknown';
        $availableOption['10']['label'] = 'Damaged';
        $availableOption['10']['value'] = 'Damaged';
        $this->availableOptionArray = $availableOption;

        return $this->availableOptionArray;
    }
}