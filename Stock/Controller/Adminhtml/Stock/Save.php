<?php
namespace Adtones\Stock\Controller\Adminhtml\Stock;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    protected $dataPersistor;
    /**
     * @var \Adtones\Stock\Helper\Data
     */
    protected $_customHelper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Adtones\Stock\Helper\Data $customHelper,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->_customHelper = $customHelper;
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
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('stock_id');
        
            $model = $this->_objectManager->create(\Adtones\Stock\Model\Stock::class)->load($id);
                
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Stock item no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $model->setData($data);

            try {
                $model->save();

                // Updating Quantity after Creating New edition.
                $this->_customHelper->getIncreaseProductQty($model->getProductId());

                $this->messageManager->addSuccessMessage(__('You saved the Stock item.'));
                $this->dataPersistor->clear('adtones_stock_stock');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['stock_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Stock item.'));
            }
        
            $this->dataPersistor->set('adtones_stock_stock', $data);
            return $resultRedirect->setPath('*/*/edit', ['stock_id' => $this->getRequest()->getParam('stock_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
