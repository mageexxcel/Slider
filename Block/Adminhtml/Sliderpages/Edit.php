<?php
namespace Excellence\Slider\Block\Adminhtml\Sliderpages;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    
    protected $_coreRegistry = null;
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Helper\Data $helper,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }
    /**
     * Initialize cms page edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Excellence_slider';
        $this->_controller = 'adminhtml_sliderpages';
        parent::_construct();

        if ($this->_isAllowedAction('Excellence_Slider::save')) {
            $this->buttonList->update('save', 'label', __('Save Slider'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }
        
        if ($this->_isAllowedAction('Excellence_Slider::page_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Slider'));
        } else {
            $this->buttonList->remove('delete');
        }
        $this->_formScripts[] = "
            require(['jquery'], function($){
                var url = jQuery(location).attr('href');
                var id = url.match('id/(.*)/key');
                if(id != null){
                    if(jQuery('#slide_slider_display_page').val() == 3){
                        getProductSku(id[1]);
                    }
                    if(jQuery('#slide_slider_display_page').val() == 2){
                        getCategoryOnEdit(id[1]);
                    }
                }
                
                showSpecific();

                var timeo;
                $('#slide_product_sku').on('input', function(){
                    jQuery('#slide_slider_specific_display_page_product_name').html('');
                    clearTimeout(timeo);
                    timeo = setTimeout(ajx, 500);
                });
            });

            function getCategoryOnEdit(id){
                
                var url = jQuery(location).attr('href');
                var part1 = url.match('(.*)edit');
                jQuery.ajax( {
                    
                    url: '".$this->_helper->getUrl('slider/sliderpages/getcategory')."',
                    data: {form_key: window.FORM_KEY, categoryId: id},
                    dataType: 'json',
                    type: 'POST'
                }).done(function(data) {
                    selectedId = data['selectId'];
                    showCategory(selectedId);
                    
                });
            }

            function getProductSku(id){
                require(['mage/translate'], function($){
                    jQuery('<p id=loading-msg-product><font size=2 color=#666666>'+jQuery.mage.__('Loading...Please Wait.')+'</font></p>').insertAfter('#slide_slider_specific_display_page_product_name');
                    var url = jQuery(location).attr('href');
                    var part1 = url.match('(.*)edit');
                    if(typeof ajax_request !== 'undefined'){
                        ajax_request.abort();
                    }
                    ajax_request = jQuery.ajax( {
                        url: '".$this->_helper->getUrl('slider/sliderpages/getproduct')."',
                        data: {form_key: window.FORM_KEY, id: id},
                        dataType: 'json',
                        type: 'POST'
                    }).done(function(data) {
                        jQuery('#loading-msg-product').remove();
                        jQuery('#slide_product_sku').attr('value',data['sku']);
                        ajx();
                    });
                });
            }

            function ajx(){
                require(['mage/translate'], function($){
                    jQuery('#loading-msg-product').remove();
                    var url = jQuery(location).attr('href');
                    var part1 = url.match('(.*)new');
                    if(part1 == null){
                        var part1 = url.match('(.*)edit');
                    }
                    var val = jQuery('#slide_product_sku').val();
                    if(val.length >= 1){
                        jQuery('<p id=loading-msg-product><font size=2 color=#666666>'+jQuery.mage.__('Loading...Please Wait.')+'</font></p>').insertAfter('#slide_slider_specific_display_page_product_name');

                        if(typeof ajax_request !== 'undefined'){
                            ajax_request.abort();
                        }
                        
                        ajax_request = jQuery.ajax( {
                            url: '".$this->_helper->getUrl('slider/sliderpages/search')."',
                            data: {form_key: window.FORM_KEY, searchTerm: val},
                            dataType: 'json',
                            type: 'POST'
                        }).done(function(data) { 
                            var len = data.length;
                            if(len > 0){
                                jQuery('#loading-msg-product').remove();
                                for(var i = 0; i < len; i++){
                                    jQuery('#slide_slider_specific_display_page_product_name').append('<option value='+ data[i]['value'] +'>'+data[i]['label']+'</option>');
                                }
                                jQuery('#product_searchbox_label').text(jQuery.mage.__('Enter Product SKU or Name to Search Again.'));
                            }
                            else{
                                jQuery('#loading-msg-product').html('<font size=2 color=red>'+jQuery.mage.__('No Match Found. Try Again...')+'</font>');
                            }
                        });
                    }
                });
            }

            function showCategory(categoryId){
                require(['mage/translate'], function($){
                    var catId = jQuery.parseJSON(categoryId);
                    jQuery('#loading-msg-category').remove();
                    jQuery('#slide_slider_specific_display_page_category').html('');
                    jQuery('<p id=loading-msg-category><font size=2 color=#666666>'+jQuery.mage.__('Loading...Please Wait.')+'</font></p>').insertAfter('#slide_slider_specific_display_page_category');
                    var val = jQuery('#slide_store_id').val();
                    var url = jQuery(location).attr('href');
                    var part1 = url.match('(.*)new');
                    if(part1 == null){
                        var part1 = url.match('(.*)edit');
                    }
                    jQuery.ajax( {
                        url: '".$this->_helper->getUrl('slider/sliderpages/getcategory')."',
                        data: {form_key: window.FORM_KEY, storeId: val},
                        dataType: 'json',
                        type: 'POST'
                    }).done(function(data) { 
                        var len = data.length;
                        if(len > 0){
                            jQuery('#loading-msg-category').remove();
                            if(categoryId == null){
                                for(var i = 0; i < len; i++){
                                    jQuery('#slide_slider_specific_display_page_category').append('<option value='+ data[i]['value'] +'>'+data[i]['label']+'</option>');
                                }
                            }
                            else{
                                for(var i = 0; i < len; i++){
                                    if(jQuery.inArray( data[i]['value'], catId ) >= 0){
                                        jQuery('#slide_slider_specific_display_page_category').append('<option selected=selected value='+ data[i]['value'] +'>'+data[i]['label']+'</option>');
                                    }
                                    else{
                                        jQuery('#slide_slider_specific_display_page_category').append('<option value='+ data[i]['value'] +'>'+data[i]['label']+'</option>');
                                    }
                                }
                            }
                                
                        }
                        else{
                            jQuery('#loading-msg-category').html('<font size=2 color=red>'+jQuery.mage.__('No Category Found in The Selected Store')+'</font>');
                        }
                    });
                });
            }

            function showchilddropdown(){
                if(jQuery('#slide_slider_display_page').val() == 2){
                    showCategory(null);
                }
                showSpecific();
            }

            function showSpecific(){
                var selectedPage =jQuery('#slide_slider_display_page').val();
                
                if(selectedPage == 0 || selectedPage == 4 || selectedPage == 5){
                    
                    jQuery('#slide_slider_specific_display_page_cms').parents('div:eq(1)').hide();
                    jQuery('#slide_slider_specific_display_page_category').parents('div:eq(1)').hide();
                    jQuery('#slide_slider_specific_display_page_product').parents('div:eq(1)').hide();
                    jQuery('#slide_store_id').parents('div:eq(1)').show();
                    jQuery('#slide_product_sku').parents('div:eq(2)').hide();
                    jQuery('#slide_slider_specific_display_page_product_name').parents('div:eq(1)').hide();
                    jQuery('.slider-container').hide();
                }
                if(selectedPage == 1){
                    jQuery('#slide_slider_specific_display_page_cms').parents('div:eq(1)').show();
                    jQuery('#slide_slider_specific_display_page_category').parents('div:eq(1)').hide();
                    jQuery('#slide_slider_specific_display_page_product').parents('div:eq(1)').hide();
                    jQuery('#slide_product_sku').parents('div:eq(2)').hide();
                    jQuery('#slide_store_id').parents('div:eq(1)').show();
                    jQuery('#slide_slider_specific_display_page_product_name').parents('div:eq(1)').hide();
                    jQuery('.slider-container').hide();
                }

                if(selectedPage == 2){
                    jQuery('#slide_slider_specific_display_page_category').parents('div:eq(1)').show();
                    jQuery('#slide_store_id').parents('div:eq(1)').show();
                    jQuery('#slide_slider_specific_display_page_cms').parents('div:eq(1)').hide();
                    jQuery('#slide_slider_specific_display_page_product').parents('div:eq(1)').hide();
                    jQuery('#slide_product_sku').parents('div:eq(2)').hide();
                    jQuery('#slide_slider_specific_display_page_product_name').parents('div:eq(1)').hide();
                    jQuery('.slider-container').hide();
                    
                }
                if(selectedPage == 3){
                    jQuery('#slide_product_sku').parents('div:eq(2)').show();
                    jQuery('#slide_slider_specific_display_page_product').parents('div:eq(1)').hide();
                    jQuery('#slide_store_id').parents('div:eq(1)').show();
                    jQuery('#slide_slider_specific_display_page_cms').parents('div:eq(1)').hide();
                    jQuery('#slide_slider_specific_display_page_category').parents('div:eq(1)').hide();
                    jQuery('#slide_slider_specific_display_page_product_name').parents('div:eq(1)').hide();
                    jQuery('.slider-container').show();
                    

                }
                if(selectedPage == 6){
                    jQuery('#slide_slider_specific_display_page_category').parents('div:eq(1)').hide();
                    jQuery('#slide_store_id').parents('div:eq(1)').show();
                    jQuery('#slide_slider_specific_display_page_cms').parents('div:eq(1)').hide();
                    jQuery('#slide_slider_specific_display_page_product').parents('div:eq(1)').hide();
                    jQuery('#slide_product_sku').parents('div:eq(2)').hide();
                    jQuery('#slide_slider_position').parents('div:eq(1)').hide();
                    jQuery('#slide_slider_specific_display_page_product_name').parents('div:eq(1)').hide();
                    jQuery('.slider-container').hide();
                }
                if(selectedPage == 7){
                    console.log('7');
                    jQuery('#slide_slider_specific_display_page_cms').parents('div:eq(1)').hide();
                    jQuery('#slide_slider_specific_display_page_category').parents('div:eq(1)').hide();
                    jQuery('#slide_product_sku').parents('div:eq(2)').hide();
                    jQuery('#slide_slider_specific_display_page_product_name').parents('div:eq(1)').hide();
                    jQuery('#slide_slider_specific_display_page_product').parents('div:eq(1)').hide();
                    jQuery('#product_collections').parents('div:eq(3)').hide();
                    jQuery('#slide_slider_position').parents('div:eq(1)').hide();
                }
            }
            "; 
    }
    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('sliderpages_edit')->getId()) {
            return __("Edit Slider '%1'", $this->escapeHtml($this->_coreRegistry->registry('sliderpages_edit')->getTitle()));
        } else {
            return __('New Slider');
        }
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
    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('excellence/slider/sliderpages/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }
    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'page_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'page_content');
                }
            };
        ";
        return parent::_prepareLayout();
    }
}?>