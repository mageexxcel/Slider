<?php

namespace Excellence\Slider\Controller\Adminhtml\Sliderpages;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Excellence\Slider\Model\ResourceModel\Sliderpages\CollectionFactory;

/**
 * Class MassDelete
 */
class MassStatus extends \Magento\Backend\App\Action
{
    protected $sliderpagesFactory = false;
    /**
     * @var Filter
     */
    protected $filter = false;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory = false;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory,\Excellence\Slider\Model\SliderpagesFactory $sliderpagesFactory)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->sliderpagesFactory = $sliderpagesFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $status = (int) $this->getRequest()->getParam('status');

        $model = $this->sliderpagesFactory->create();
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        $ids = $collection->getAllIds();
        foreach ($ids as $id) {
            $model->load($id)->setData('is_active', $status)->save();
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been Updated.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
