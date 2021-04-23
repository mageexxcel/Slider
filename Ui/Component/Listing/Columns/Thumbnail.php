<?php 
namespace Excellence\Slider\Ui\Component\Listing\Columns; 

use Magento\Catalog\Helper\Image; 
use Magento\Framework\UrlInterface; 
use Magento\Framework\View\Element\UiComponentFactory; 
use Magento\Framework\View\Element\UiComponent\ContextInterface; 
use Magento\Store\Model\StoreManagerInterface; 
use Magento\Ui\Component\Listing\Columns\Column; 

class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column { 
    const NAME = 'thumbnail'; 
    const ALT_FIELD = 'name'; 
    /** 
    * @param ContextInterface $context 
    * @param UiComponentFactory $uiComponentFactory 
    * @param \Magento\Framework\UrlInterface $urlBuilder 
    * @param array $components * @param array $data 
    */ 
    public function __construct( 
        ContextInterface $context, 
        UiComponentFactory $uiComponentFactory, 
        Image $imageHelper, UrlInterface $urlBuilder, StoreManagerInterface $storeManager, 
        array $components = [], 
        array $data = [] 
    ) { 
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
        $this->urlBuilder = $urlBuilder;
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
        if(isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
           
            foreach($dataSource['data']['items'] as & $item) {
                $url = '';
               
                if($item[$fieldName] != '') {
                    
                   $url = $item[$fieldName];
                   
                }
                $item[$fieldName . '_src'] = $url;
                $item[$fieldName . '_alt'] = $this->getAlt($item) ?: '';
                $item[$fieldName . '_orig_src'] = $url;
            }
        }

        return $dataSource;
    }

    /**
     * @param array $row
     *
     * @return null|string
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}