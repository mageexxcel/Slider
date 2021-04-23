<?php


namespace Adtones\Stock\Controller\Adminhtml\Edition;

class Delete extends \Adtones\Stock\Controller\Adminhtml\Edition
{
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('stock_id');
        $productId = '';
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Adtones\Stock\Model\Stock::class);
                $customHelper = $this->_objectManager->create(\Adtones\Stock\Helper\Data::class);

                $model->load($id);
                $productId = $model->getProductId();
                $model->delete();
                
                // Updating Quantity after deleting edition.
                $customHelper->getDecreaseProductQty($productId);

                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Edition.'));
                // go to grid
                return $resultRedirect->setPath('catalog/product/edit/id/'.$productId.'/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['stock_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Edition to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
