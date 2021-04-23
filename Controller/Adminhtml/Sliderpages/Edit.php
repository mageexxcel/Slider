<?php
namespace Excellence\Slider\Controller\Adminhtml\Sliderpages;
class Edit extends \Magento\Backend\App\Action
{
   
    protected $resultRedirectFactory = false;
    protected $registry = false;
    protected $sliderpagesFactory = false;
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Excellence\Slider\Model\SliderpagesFactory $sliderpagesFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->registry = $registry;
        $this->sliderpagesFactory = $sliderpagesFactory;
    }
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->sliderpagesFactory->create();
        if ($id) {
            $model->load($id);
        }
        $this->registry->register('sliderpages_edit',$model);
        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Excellence_Slider::excellence_slider_manage_slider_pages')
            ->addBreadcrumb(__('Manage'), __('Sliders'))
            ->addBreadcrumb(__('Manage Sliders'), __('Manage Sliders'));
        return $resultPage;
    }
}