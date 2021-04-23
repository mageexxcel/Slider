<?php
namespace Adtones\Stock\Ui\Component\Renderer\Form;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Options tree for "Categories" field
 */
class ConditionOptions implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getConditionOptionArray();
    }

    /**
     * Retrieve categories tree
     *
     * @return array
     */
    protected function getConditionOptionArray()
    {
        // Options for availability
        $this->ConditionOptionArray = null;
        $conditionOption['1']['label'] = 'New';
        $conditionOption['1']['value'] = 'New';
        $conditionOption['2']['label'] = 'Damaged';
        $conditionOption['2']['value'] = 'Damaged';
        $conditionOption['3']['label'] = 'Destroyed';
        $conditionOption['3']['value'] = 'Destroyed';
        $conditionOption['4']['label'] = 'Unknown';
        $conditionOption['4']['value'] = 'Unknown';
        $this->ConditionOptionArray = $conditionOption;

        return $this->ConditionOptionArray;
    }
}