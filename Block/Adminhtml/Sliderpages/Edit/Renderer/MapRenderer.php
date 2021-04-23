<?php
namespace Excellence\Slider\Block\Adminhtml\Sliderpages\Edit\Renderer;

use Magento\Framework\App\ObjectManager;
/**
* CustomFormField Customformfield field renderer
*/
class MapRenderer extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element implements
    \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * Form element which re-rendering
     *
     * @var \Magento\Framework\Data\Form\Element\Fieldset
     */
    protected $_element;
    /**
     * @var string
     */
    protected $_template = 'selectedproducts.phtml';
    /**
     * Retrieve an element
     *
     * @return \Magento\Framework\Data\Form\Element\Fieldset
     */
    protected $_coreRegistry = null;
    protected $product; 
    // protected $productData =[];
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Helper\Data $helper,
        \Magento\Catalog\Model\ProductFactory $product,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_helper = $helper;
        $this->_products = $product;
        parent::__construct($context, $data);
    }
    public function getSelectedProducts(){
        if ($this->_coreRegistry->registry('sliderpages_edit')->getId()) {
            $productsId = $this->_coreRegistry->registry('sliderpages_edit')->getSliderSpecificDisplayPageProduct();
            $productIDVal = json_decode($productsId);
            return $productIDVal;
        }
    }
    public function getProductname($productId){  
            $productData =  $this->_products->create()->load($productId);
            $data =$productData->getName();
            return $data;
    } 
    public function getElement()
    {
        return $this->_element;
    }
    /**
     * Render element
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->_element = $element;
        return $this->toHtml();
    }
}