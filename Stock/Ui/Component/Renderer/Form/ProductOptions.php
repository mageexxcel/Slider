<?php
namespace Adtones\Stock\Ui\Component\Renderer\Form;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Options tree for "Product" field
 */
class ProductOptions implements OptionSourceInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param array $data = []
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,        
        array $data = []
    ){    
        $this->_productCollectionFactory = $productCollectionFactory;    
    }
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
        $productCollection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')->addAttributeToSort('name', 'ASC');

        // Options for Product
        $this->ownershipOptionArray = null;
        foreach ($productCollection as $index => $collection) {
            $ownershipOption[$index]['label'] = $collection->getName();
            $ownershipOption[$index]['value'] = $collection->getId();
        }
        $this->ownershipOptionArray = $ownershipOption;
        return $this->ownershipOptionArray;
    }
}