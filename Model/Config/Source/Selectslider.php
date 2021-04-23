<?php
namespace Excellence\Slider\Model\Config\Source;
use Excellence\Slider\Model\SliderpagesFactory;
 
class Selectslider implements \Magento\Framework\Option\ArrayInterface
{

    const WIDGET_PAGE = 6;
    const ACTIVE_VALUE = 1;
    protected $_sliderPagesFactory;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        SliderpagesFactory $sliderPagesFactory,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_sliderPagesFactory = $sliderPagesFactory;
        $this->_registry = $registry;
    }
    public function toOptionArray()
    {
        
        $sliders = $this->_sliderPagesFactory->create()->getCollection();
        $sliders->addFieldToFilter('slider_display_page',self::WIDGET_PAGE);
        $sliders->addFieldToFilter('is_active',self::ACTIVE_VALUE);
        $widgetSliderOptions = [['value' => '', 'label' => __('Select')]];
        foreach ($sliders as $slider) {
            $widgetSliderOptions[] = ['value' => $slider->getId(), 'label' => $slider->getSliderName()];
        }
        return $widgetSliderOptions;
    }
}