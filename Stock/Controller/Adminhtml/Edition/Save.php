<?php
namespace Adtones\Stock\Controller\Adminhtml\Edition;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @return \Magento\Framework\App\Request\DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @return \Adtones\Stock\Model\StockFactory
     */
    protected $_stockFactory;
    /**
     * @return \Adtones\Stock\Helper\Data
     */
    protected $_productHelper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Adtones\Stock\Model\StockFactory $stockFactory
     * @param \Adtones\Stock\Helper\Data $helper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Adtones\Stock\Model\StockFactory $stockFactory,
        \Adtones\Stock\Helper\Data $helper
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->_stockFactory = $stockFactory;
        $this->_productHelper = $helper;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        $formData = "";
        if($this->getRequest()->getParam('formData')){
            $formData = $this->getRequest()->getParam('formData');
        }
        $data = json_decode($formData, true);

        // Stock manager grid collection factory.
        $stockCollection = $this->_stockFactory->create();

        $productData = $this->_productHelper->getProductAttribute($data['product_id']);
        $artistName  = $productData->getProductArtist();
        $work        = $productData->getProductWork(); 
        $productId   = $productData->getId(); 
        $productSku  = $productData->getSku();    
        $productName = $productData->getName();        

        if ($data) {
            // Creating array to store data in stock grid.
            $stockGridDataArray = array(
                'product_id'           => $productId,
                'sku'                  => $productSku,
                'artist'               => $artistName,
                'work'                 => $work,
                'availability'         => $data['availability'],
                'condition'            => $data['condition'],
                'ownership'            => $data['ownership'],
                'location'             => $data['location'],
                'edition_number'       => $data['edition_number'],
                'stock_type'           => $data['stock_type'],
                'packing_requirements' => $data['packing_requirements'],
                'framed'               => $data['framed']
            );

            $stockCollection->setData($stockGridDataArray);

            try {
                $stockCollection->save();
                $this->messageManager->addSuccessMessage(__('You saved the Edition.'));
                $this->dataPersistor->clear('adtones_stock_stock');

                // Updating inventory after creating new editions
                $this->_productHelper->getIncreaseProductQty($productId);
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['stock_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('catalog/product/edit/id/'.$productId);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Edition.'));
            }
        
            $this->dataPersistor->set('adtones_stock_edition', $data);
            return $resultRedirect->setPath('*/*/edit', ['stock_id' => $this->getRequest()->getParam('stock_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
