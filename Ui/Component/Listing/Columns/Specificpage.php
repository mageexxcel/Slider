<?php

namespace Excellence\Slider\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

use Psr\Log\LoggerInterface;


class Specificpage extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    protected $logger;
    protected $_sliderpagesFactory;
    protected $_categoryFactory;
    protected $_pageFactory;
    protected $_productFactory;
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        \Excellence\Slider\Model\SliderpagesFactory $sliderpagesFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $components = [],
        array $data = []
    ) {
        $this->logger = $logger;
        $this->urlBuilder = $urlBuilder;
        $this->_sliderpagesFactory = $sliderpagesFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_pageFactory = $pageFactory;
        $this->_productFactory = $productFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        
        $model = $this->_sliderpagesFactory->create();

        
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $page_type_id = $model->load($item['id'])->getData('slider_display_page');
                if($page_type_id == 1){
                    $page_id = $model->load($item['id'])->getData('slider_specific_display_page_cms');
                    $item[$this->getData('name')] = $this->getCmsPages($page_id)->getData('title');
                } elseif ($page_type_id == 2) {
                    $page_id = $model->load($item['id'])->getData('slider_specific_display_page_category');
                    $displayPageId = json_decode($page_id);
                    $catId = array();
                    foreach ($displayPageId as $displayPageIdInfo) {    
                        $catId[] = $this->getCategory($displayPageIdInfo)->getData('name');
                    }
                    $item[$this->getData('name')] = $catId;
                } elseif ($page_type_id == 3) {
                    $page_id = $model->load($item['id'])->getData('slider_specific_display_page_product');
                    $displayProductPageId = json_decode($page_id);
                    $productId = array();
                    foreach ($displayProductPageId as $productPageInfo) {
                       $productId[] = $this->getProduct($productPageInfo)->getData('name');
                    }
                    $item[$this->getData('name')] = $productId;
                } else{
                    $item[$this->getData('name')] = "---";
                }
                
            }
        }
        return $dataSource;
    }
    public function getCategory($categoryId) 
    {
        $category = $this->_categoryFactory->create();
        $category->load($categoryId);
        return $category;
    }
    public function getCmsPages($pageId)
    {
        $cms_model = $this->_pageFactory->create();
        $cms_model->load($pageId);
        return $cms_model;
    }
    public function getProduct($productId)
    {
        $product = $this->_productFactory->create();
        $product->load($productId);
        return $product;
    }
}
