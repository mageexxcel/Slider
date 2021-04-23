<?php
namespace Adtones\Stock\Ui\Component\Renderer\Form;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Options tree for "Product" field
 */
class OrderStatus implements OptionSourceInterface
{
    /**
     * @var Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusCollectionFactory
     */
    protected $statusCollectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusCollectionFactory
     * @param array $data = []
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusCollectionFactory,      
        array $data = []
    ){    
        $this->statusCollectionFactory = $statusCollectionFactory;   
    }

    /**
     * Get status options
     *
     * @return array
     */
    public function toOptionArray()
    {       
        $options = $this->statusCollectionFactory->create()->toOptionArray();        
        return $options;
    }   
}