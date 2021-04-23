<?php
namespace Excellence\Slider\Block\Adminhtml\Sliderpages\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_systemStore;
    protected $_pages;
    protected $_status;
    protected $_sliderPosition;
    protected $_specificpageoptions;
    protected $_Stretchimage;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Excellence\Slider\Model\Adminhtml\Config\Source\Stretchimage $Stretchimage,
        \Excellence\Slider\Model\Adminhtml\Config\Source\Pages $pages,
        \Excellence\Slider\Model\Adminhtml\Config\Source\Status $status,
        \Excellence\Slider\Model\Adminhtml\Config\Source\Specificpageoptions $specificpageoptions,
        \Excellence\Slider\Model\Adminhtml\Config\Source\Sliderposition $sliderPosition,
        array $data = []
    ) {
        $this->_Stretchimage = $Stretchimage;
        $this->_systemStore = $systemStore;
        $this->_pages = $pages;
        $this->_status = $status;
        $this->_sliderPosition = $sliderPosition;
        $this->_specificpageoptions = $specificpageoptions;
        parent::__construct($context, $registry, $formFactory, $data);
    }
  
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('sliderpages_edit');
        if ($this->_isAllowedAction('Excellence_Slider::save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('slide_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Slider Information')]);
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
      
        $fieldset->addField(
            'slider_name',
            'text',
            [
                'name' => 'slider_name',
                'label' => __('Slider Name'),
                'title' => __('Slider Name'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_id',
                'multiselect',
                [
                    'name' => 'stores[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true),
                    'disabled' => $isElementDisabled
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_id',
                'hidden',
                ['name' => 'stores[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }
        $fieldset->addField(
            'stretch_image',
            'select',
            [
                'name' => 'stretch image',
                'label' => __('Stretch Image'),
                'title' => __('Stretch Image'),
                'required' => true,
                'values' => $this->_Stretchimage->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'slider_display_page',
            'select',
            [
                'name' => 'slider_display_page',
                'label' => __('Show Slider On'),
                'title' => __('Show Slider On'),
                'class' => 'required-entry',
                'required' => true,
                'onchange' => "showchilddropdown()",
                'values' => $this->_pages->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'slider_specific_display_page_cms',
            'select',
            [
                'name' => 'slider_specific_display_page_cms',
                'label' => __('Specify Slider CMS Page'),
                'title' => __('Specify Slider CMS Page'),
                'class' => 'required-entry',
                'required' => true,
                'values' => $this->_specificpageoptions->getCmsPages(),
            ]
        );   
        $fieldset->addField(
            'slider_specific_display_page_category',
            'multiselect',
            [
                'name' => 'slider_specific_display_page_category',
                'label' => __('Specify Slider Category Page'),
                'title' => __('Specify Slider Category Page'),
            ]
        );
        $sku_comment = "<font id='product_searchbox_label' size=2 color='#666666'>&uarr;&nbsp;".__("Enter Product SKU or Name")."</font></p>";
        $fieldset->addField(
            'product_sku',
            'text',
            [
                'name' => 'product_sku',
                'label' => __('Product SKU or Name'),
                'title' => __('Product SKU or Name'),
                'disabled' => $isElementDisabled,
                'after_element_html' => $sku_comment
            ]
        );
        $fieldset->addField(
            'slider_specific_display_page_product_name',
            'multiselect',
            [
                'name' => 'slider_specific_display_page_product_name',
                'label' => __('Specify Product Collection'),
                'title' => __('Specify  Product Collection'),
            ]
        );
        $fieldset->addField(
            'slider_specific_display_page_product',
            'textarea',
            [
                'name' => 'slider_specific_display_page_product', 
            ]
        );
        $fieldMap = $fieldset->addField(
            'map_data',
            'text',
            [
                'name' => 'map_data',
                'label' => __('Specify Product Selected'),
                'title' => __('Specify Product Selected'),
                'required' => true
            ]
        );
        $renderer = $this->getLayout()->createBlock(
            'Excellence\Slider\Block\Adminhtml\Sliderpages\Edit\Renderer\MapRenderer'
        );
        $fieldMap->setRenderer($renderer);
        $fieldset->addField(
            'slider_position',
            'select',
            [
                'name' => 'slider_position',
                'label' => __('Slider Position'),
                'title' => __('Slider Position'),
                'class' => 'required-entry',
                'required' => true,
                'values' => $this->_sliderPosition->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'is_active',
            'select',
            [
                'name' => 'is_active',
                'label' => __('Status'),
                'title' => __('Status'),
                'class' => 'required-entry',
                'required' => true,
                'values' => $this->_status->toOptionArray(),
            ]
        );
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Slider Information');
    }
    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Slider Information');
    }
    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}