<?php

namespace Excellence\Slider\Controller\Adminhtml\Slides;

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
        if (strlen($post['slider_slides']['title']) === 0) {
            $response->setError(true);
            $messages = [];
            $messages[] = __('Title cannot be empty');
            $response->setMessages($messages);
        } elseif (strlen($post['slider_slides']['image_name']) === 0) {
            $response->setError(true);
            $messages = [];
            $messages[] = __('Image Name cannot be empty');
            $response->setMessages($messages);
        } elseif (strlen($post['slider_slides']['image_position']) === 0) {
            $response->setError(true);
            $messages = [];
            $messages[] = __('Image Position cannot be empty');
            $response->setMessages($messages);
        } elseif (strlen($post['slider_slides']['content']) === 0) {
            $response->setError(true);
            $messages = [];
            $messages[] = __('URL cannot be empty');
            $response->setMessages($messages);
        }
        $resultJson->setData($response);
        return $resultJson;
    }
}