<?php
namespace Excellence\Slider\Controller\Adminhtml\Sliderpages;

class Validate extends \Magento\Backend\App\Action
{
   
    protected $resultJsonFactory = false;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
    }
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        $response = new \Magento\Framework\DataObject();
        $response->setError(0);
        $resultJson = $this->resultJsonFactory->create();
        if (!isset($post)) {
            $response->setError(true);
            $messages = [];
            $messages[] = __('Enter The required fields');
            $response->setMessages($messages);
        }
        $resultJson->setData($response);
        return $resultJson;
    }
}
?>