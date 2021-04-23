<?php
namespace Excellence\Slider\Block\Adminhtml\Slides\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_systemStore;
    protected $_sliders;
    protected $_status;
    protected $_slidePages;
    protected $_specificpageoptions;
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
        \Excellence\Slider\Model\Adminhtml\Config\Source\Selectpages $slidePages,
        \Excellence\Slider\Model\Adminhtml\Config\Source\Specificpageoptions $specificpageoptions,
        \Magento\Store\Model\System\Store $systemStore,
        \Excellence\Slider\Model\Adminhtml\Config\Source\Sliders $sliders,
        \Excellence\Slider\Model\Adminhtml\Config\Source\Status $status,
        \Magento\Backend\Helper\Data $helper,
        array $data = []
    ) {
        
        $this->_systemStore = $systemStore;
        $this->_sliders = $sliders;
        $this->_status = $status;
        $this->_helper = $helper;
        $this->_slidePages = $slidePages;
        $this->_specificpageoptions = $specificpageoptions;
        parent::__construct($context, $registry, $formFactory, $data);
    }
  
    protected function _prepareForm()
    {
        /* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('slides_edit');
        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('Excellence_Slider::save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('slide_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Slide Information')]);
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
        $fieldset->addField(
            'image_name',
            'text',
            [
                'name' => 'image_name',
                'label' => __('Image Name'),
                'title' => __('Image Name'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );
        $caption_comment = "<font size=2 color='#666666'>&uarr;&nbsp;".__("Leave blank if you don't want to show caption with image.")."</font></p>";
        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Caption'),
                'title' => __('Caption'),
                'required' => false,
                'disabled' => $isElementDisabled,
                'after_element_html' => $caption_comment
            ]
        );
        $image_comment=null;
        $isImageRequired = true;
        if(!empty($model->getId())){
            $isImageRequired = false;
            $image_comment = "<p><a style='text-decoration:none;' href='".$model->getFilename()."' target='_blank'><div>".__('Previously Selected Image')."</div><img style='padding: 5px; border: 1px solid #777; margin: 5px 0 0 0; height: 50px; width: 70px;' src='".$model->getFilename()."'></a><p>";
        }            
        $fieldset->addField(
            'image',
            'image',
            [
                'title' => __('Image'),
                'label' => __('Image'),
                'name' => 'image',
                'note' => 'Allowed image types: jpg, jpeg, gif, png',
                'before_element_html' => $image_comment,
                'required' => $isImageRequired
            ]
        );
        $caption_comment = "<font size=2 color='#666666'>&uarr;&nbsp;".__("Enter image position(optional). Default Value: 0")."</font></p>";
        $fieldset->addField(
            'image_position',
            'text',
            [
                'name' => 'image_position',
                'label' => __('Image Position'),
                'title' => __('Image Position'),
                'required' => false,
                'disabled' => $isElementDisabled,
                'after_element_html' => $caption_comment
            ]
        );

        $slider_name_comment = "<font size=2 color='#666666'>&uarr;&nbsp;".__("Select the slider to show the slide. To find slider name, ")."<a href='".$this->_helper->getUrl('slider/sliderpages')."' target = '_blank'>".__("Click here")."</a>"."</font></p>";
        $fieldset->addField(
            'slider_name',
            'select',
            [
                'name' => 'slider_name',
                'label' => __('Add Slide To'),
                'title' => __('Add Slide To'),
                'class' => 'required-entry',
                'required' => true,
                'values' => $this->_sliders->toOptionArray(),
                'after_element_html' => $slider_name_comment
            ]
        );
        $fieldset->addField(
            'slider_display_page',
            'select',
            [
                'name' => 'slider_display_page',
                'label' => __('Select Page For URL'),
                'title' => __('Select Page For URL'),
                'class' => 'required-entry',
                'required' => true,
                'onchange' => "showchilddropdown()",
                'values' => $this->_slidePages->toOptionArray(),
            ]
        );
        $cms_comment = "<font size=2 color='#666666'>&uarr;&nbsp;".__("Select CMS Page To Set Its URL")."</font></p>";
        $fieldset->addField(
            'slider_specific_display_page_cms',
            'select',
            [
                'name' => 'slider_specific_display_page_cms',
                'label' => __('Select CMS Url'),
                'title' => __('Select CMS Url'),
                'class' => 'required-entry',
                'required' => true,
                'values' => $this->_specificpageoptions->getCmsPages(),
                'disabled' => $isElementDisabled,
                'after_element_html' => $cms_comment
            ]
        ); 
        $category_comment = "<font size=2 color='#666666'>&uarr;&nbsp;".__("Select Category To Set Its URL")."</font></p>";
        $fieldset->addField(
            'slider_specific_display_page_category',
            'select',
            [
                'name' => 'slider_specific_display_page_category',
                'label' => __('Select Category Url'),
                'title' => __('Select Category Url'),
                'disabled' => $isElementDisabled,
                'after_element_html' => $category_comment
            ]
        );
        $sku_comment = "<font size=2 color='#666666'>&uarr;&nbsp;".__("Enter Product SKU or Name to Begin Search")."</font></p>";
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
        $product_comment = "<font size=2 color='#666666'>&uarr;&nbsp;".__("Select Product To Set Its URL")."</font></p>";
        $fieldset->addField(
            'slider_specific_display_page_product',
            'select',
            [
                'name' => 'slider_specific_display_page_product',
                'label' => __('Select Product Url'),
                'title' => __('Select Product Url'),
                'disabled' => $isElementDisabled,
                'after_element_html' => $product_comment
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
        $custom_comment = "<font size=2 color='#666666'>&uarr;&nbsp;".__("Enter Custom URL")."</font></p>";
        $fieldset->addField(
            'content',
            'text',
            [
                'name' => 'content',
                'label' => __('URL'),
                'title' => __('URL'),
                'required' => false,
                'class' => 'validate-url',
                'disabled' => $isElementDisabled,
                'after_element_html' => $custom_comment
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
        return __('Slide Information');
    }
    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Slide Information');
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