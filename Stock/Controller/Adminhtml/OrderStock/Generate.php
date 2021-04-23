<?php


namespace Adtones\Stock\Controller\Adminhtml\OrderStock;

use Adtones\Stock\Model\Order\Pdf\Invoice;

use Magento\Framework\App\Filesystem\DirectoryList;

class Generate extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;

    protected $request;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    /**
     * @var \Magento\Framework\Registry
    */
    
    protected $_registry;

    protected $authSession;

    /**
     * Constructor 
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Sales\Model\Order\Invoice $invoice,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Registry $registry,
        Invoice $pdfInvoice,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->request = $request;
        $this->resultPageFactory = $resultPageFactory;
        $this->orderRepository = $orderRepository;
        $this->invoiceService = $invoiceService;
        $this->transactionFactory = $transactionFactory;
        $this->messageManager = $messageManager;
        $this->invoiceSender = $invoiceSender;
        $this->_fileFactory = $fileFactory;
        $this->invoice = $invoice;
        $this->pdfInvoice = $pdfInvoice;
        $this->authSession = $authSession;
        $this->_registry = $registry;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $invoiceId = $this->request->getParam('invoice_id');
        $this->generateInvoice($invoiceId);
        return $resultRedirect->setPath('sales/order/view/order_id/'.$orderId.'/');
    }

    /**
     * Create Invoice Based on Order Object
     * @param \Magento\Sales\Model\Order $order
     * @return $this
     */
    public function generateInvoice($invoiceId){
        $invoice = $this->invoice->load($invoiceId);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $subtotal=0;
        foreach($invoice->getAllItems() as $item){
            
            $editionItem = $objectManager->get('\Magento\Catalog\Model\Product')->loadByAttribute('sku', $item->getSku());
            $product = $objectManager->create('Magento\Catalog\Model\Product')->load($editionItem->getId());
            $item->setPrice($product->getPrice());
            $item->setBasePrice($product->getPrice());

            $item->setRowTotal($product->getPrice()*$item->getQty());
            $item->setBaseRowTotal($product->getPrice()*$item->getQty());

            $item->getPriceInclTax($product->getPrice()+($item->getTaxAmount()/$item->getQty()));
            $item->getBasePriceInclTax($product->getPrice()+($item->getTaxAmount()/$item->getQty()));

            $item->getBaseRowTotalInclTax(($product->getPrice()*$item->getQty())+$item->getTaxAmount());
            $item->getBaseRowTotalInclTax(($product->getPrice()*$item->getQty())+$item->getTaxAmount());

            $item->save();

            $subtotal = $subtotal + $item->getRowTotal();
        }

        $invoice->setSubtotal($subtotal);
        $invoice->setBaseSubtotal($subtotal);

        $invoice->setSubtotalInclTax($subtotal+ $invoice->getTaxAmount());
        $invoice->setBaseSubtotalInclTax($subtotal+ $invoice->getTaxAmount());

        $invoice->setGrandTotal($subtotal + $invoice->getTaxAmount() + $invoice->getShippingInclTax());
        $invoice->setBaseGrandTotal($subtotal + $invoice->getTaxAmount() + $invoice->getShippingInclTax());

        $invoice->save();
               
        $this->_registry->register('edition_invoice', 'edition_invoice');

        $this->authSession->setEditionInvoice('checked');
       
        $pdf = $this->pdfInvoice->getPdf($invoice);
        $date = $this->_objectManager->get(
            \Magento\Framework\Stdlib\DateTime\DateTime::class
        )->date('Y-m-d_H-i-s');
        $fileContent = ['type' => 'string', 'value' => $pdf->render(), 'rm' => true];

        return $this->_fileFactory->create(
            'invoice' . $date . '.pdf',
            $fileContent,
            DirectoryList::VAR_DIR,
            'application/pdf'
        );
    }
}
