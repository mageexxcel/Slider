<?php


namespace Adtones\Stock\Controller\Adminhtml\Edition;

class InlineEdit extends \Magento\Backend\App\Action
{

    protected $jsonFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     */
    protected $_productHelper;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Adtones\Stock\Helper\Data $helper
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->_productHelper = $helper;
    }

    /**
     * Inline edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        
        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $modelid) {
                    /** @var \Adtones\Stock\Model\Edition $model */
                    $model = $this->_objectManager->create(\Adtones\Stock\Model\Stock::class)->load($modelid);
                    try {
                        // print_r($model->getProductId());die('test');
                        $model->setData(array_merge($model->getData(), $postItems[$modelid]));
                        $model->save();
                        // Updating inventory after creating new editions
                        $this->_productHelper->getIncreaseProductQty($model->getProductId());
                    } catch (\Exception $e) {
                        $messages[] = "[Edition ID: {$modelid}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }
        
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
