<?php 

namespace Adtones\Stock\Ui\DataProvider\Product\Form\Modifier; 

use Magento\Catalog\Model\Locator\LocatorInterface; 
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier; 
use Magento\Framework\Stdlib\ArrayManager; 
use Magento\Framework\UrlInterface; 
use Magento\Ui\Component\Container; 
use Magento\Framework\Phrase;
use Magento\Ui\Component\Form\Fieldset; 

class EditionTab extends AbstractModifier { 
    
    const SAMPLE_FIELDSET_NAME = 'adtones_stock_edition_grid'; 
    const SAMPLE_FIELD_NAME = 'edition_grid'; 
    const BUTTON_FIELD_NAME = "add_new_button";
    protected $_backendUrl; 
    protected $_productloader; 
    protected $_modelCustomgridFactory; 
    protected $_registry;
    /**
     * @var string
     * @since 101.0.0
     */
    protected $scopeName;
    /**
     * @var string
     * @since 101.0.0
     */
    protected $scopePrefix;
    /** 
     * @var \Magento\Catalog\Model\Locator\LocatorInterface */ 
    protected $locator; 
    /** * @var ArrayManager */ 
    protected $arrayManager; 
    /** * @var UrlInterface */ 
    protected $urlBuilder; 
    /** * @var array */ 
    protected $meta = []; 

    /** 
     * @param LocatorInterface $locator 
     * @param ArrayManager $arrayManager 
     * @param \Magento\Framework\Registry $registry
     * @param UrlInterface $urlBuilder 
     */ 
    public function __construct( 
        LocatorInterface $locator, 
        ArrayManager $arrayManager, 
        UrlInterface $urlBuilder, 
        \Magento\Catalog\Model\ProductFactory $_productloader, 
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Framework\Registry $registry,
        $scopeName = '',
        $scopePrefix = ''
    ) { 
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->urlBuilder = $urlBuilder;
        $this->_registry = $registry;
        $this->_productloader = $_productloader;
        $this->_backendUrl = $backendUrl;
        $this->scopeName = $scopeName;
        $this->scopePrefix = $scopePrefix;
    }
  
    public function modifyData(array $data)
    {
        return $data;
    }
  
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        $this->addCustomTab();
        return $this->meta;
    }
  
    protected function addCustomTab()
    {
        $this->meta = array_merge_recursive(
            $this->meta,
            [
                static::SAMPLE_FIELDSET_NAME => $this->getTabConfig(),
                static::BUTTON_FIELD_NAME => $this->getRelatedFieldset(),
            ]
        );
    }
  
    protected function getTabConfig()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Stock Edition Grid'),
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'provider' => static::FORM_NAME . '.product_form_data_source',
                        'ns' => static::FORM_NAME,
                        'collapsible' => true,
                    ],
                ],
            ],
            'children' => [
                static::SAMPLE_FIELD_NAME => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'autoRender' => true,
                                'componentType' => 'insertListing',
                                'dataScope' => 'adtones_stock_edition_listing',
                                'externalProvider' => 'adtones_stock_edition_listing.adtones_stock_edition_listing_data_source',
                                'selectionsProvider' => 'adtones_stock_edition_listing.adtones_stock_edition_listing.product_columns.ids',
                                'ns' => 'adtones_stock_edition_listing',
                                'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                'realTimeLink' => false,
                                'behaviourType' => 'simple',
                                'externalFilterMode' => true,
                                'imports' => [
                                    'productId' => '${ $.provider }:data.product.current_product_id'
                                ],
                                'exports' => [
                                    'productId' => '${ $.externalProvider }:params.current_product_id'
                                ],
    
                            ],
                        ],
                    ],
                    'children' => [],
                ],
            ],
        ];
    }

    /**
     * Prepares config for the Related products fieldset
     *
     * @return array
     * @since 101.0.0
     */
    protected function getRelatedFieldset()
    {
        return [
            'children' => [
                'button_set' => $this->getButtonSet(
                    __('Add New Edition'),
                    $this->scopePrefix . static::BUTTON_FIELD_NAME
                )
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section_edition',
                        'label' => __(''),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                    ],
                ],
            ]
        ];
    }

    /**
     * Retrieve button set
     *
     * @param Phrase $content
     * @param Phrase $buttonTitle
     * @param string $scope
     * @return array
     * @since 101.0.0
     */
    protected function getButtonSet(Phrase $buttonTitle, $scope)
    {
        $modalTarget = $this->scopeName . '.' . static::BUTTON_FIELD_NAME . '.' . $scope . '.modal';

        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'label' => false,
                        'content' => '',
                        'template' => 'ui/form/components/complex',
                    ],
                ],
            ],
            'children' => [
                'button_' . $scope => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'container',
                                'componentType' => 'container',
                                'component' => 'Magento_Ui/js/form/components/button',
                                'actions' => [],
                                'title' => $buttonTitle,
                                'provider' => null,
                            ],
                        ],
                    ],

                ],
            ],
        ];
    }
}