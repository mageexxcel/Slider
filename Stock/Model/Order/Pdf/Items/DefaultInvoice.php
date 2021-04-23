<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Adtones\Stock\Model\Order\Pdf\Items;

/**
 * Sales Order Invoice Pdf default items renderer
 */
class DefaultInvoice extends \Magento\Sales\Model\Order\Pdf\Items\Invoice\DefaultInvoice
{
    /**
     * Draw item line
     *
     * @return void
     */
    public function draw()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $session = $objectManager->create('Magento\Backend\Model\Auth\Session');

        if(!empty($session->getEditionInvoice()) && $session->getEditionInvoice()){
            $session->unsEditionInvoice();
            $editionItem = $objectManager->get('\Magento\Catalog\Model\Product')->loadByAttribute('sku', $this->getItem()->getSku());
            $product = $objectManager->create('Magento\Catalog\Model\Product')->load($editionItem->getId());
            $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data'); 
            $productPrice = $priceHelper->currency($product->getFinalPrice(), true, false);
            $this->drawEdition($product, $productPrice);
        }else{
            $this->drawWithoutEdition();
        }
    }

    /**
     * Draw item line with edition detail
     *
     * @return void
     */
    public function drawEdition($product, $productPrice)
    {
        $order = $this->getOrder();
        $item = $this->getItem();
        $pdf = $this->getPdf();
        $page = $this->getPage();
        $lines = [];

        // Getting Edition info.
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $reportData = $objectManager->create('Adtones\Stock\Model\Report');
        $customHelper = $objectManager->get('Adtones\Stock\Helper\Data');

        $orderFactory = $reportData->getCollection()->addFieldToFilter('sku', array('eq' => $this->getSku($item)))
                                                                        ->addFieldToFilter('order_id', array('eq' => $order->getIncrementId()));

        $productEditionNumber = "";
        $collectionSize = '';
        if($orderFactory->getSize() >= 1){
            foreach ($orderFactory as $report) {
                $productEditionNumber = $productEditionNumber.' Edition: '.$report->getEditionNumber().', ';
                $collectionSize = $customHelper->getCollectionSize($report->getSku());
            }
        }
        $editionInfo = '';
        if($collectionSize == 1){
            $editionInfo = "Edition: Unique";
        }else{
            for ($i = 0; $i < strlen($productEditionNumber)-2; $i++){
                $editionInfo = $editionInfo.''.$productEditionNumber[$i];
            }
        }
        
        // draw Product name
        $lines[0] = [['text' => $this->string->split($item->getName().' ('.$editionInfo.')', 35, true, true), 'feed' => 35]];

        // draw Event Description
        $lines[1] = [['text' => $this->string->split($product->getData('event_description'), 35, true, true), 'feed' => 35]];

        // draw SKU
        $lines[0][] = [
            'text' => $this->string->split($this->getSku($item), 17),
            'feed' => 290,
            'align' => 'right',
        ];

        // draw QTY
        $lines[0][] = ['text' => $item->getQty() * 1, 'feed' => 435, 'align' => 'right'];

        // draw item Prices
        $i = 0;
        $prices = $this->getItemPricesForDisplay();
        $feedPrice = 395;
        $feedSubtotal = $feedPrice + 170;
        foreach ($prices as $priceData) {
            if (isset($priceData['label'])) {
                // draw Price label
                $lines[$i][] = ['text' => $priceData['label'], 'feed' => $feedPrice, 'align' => 'right'];
                // draw Subtotal label
                $lines[$i][] = ['text' => $priceData['label'], 'feed' => $feedSubtotal, 'align' => 'right'];
                $i++;
            }
            // draw Price
            $lines[$i][] = [
                'text' => $priceData['price'],
                'feed' => $feedPrice,
                'font' => 'bold',
                'align' => 'right',
            ];
            // draw Subtotal
            $lines[$i][] = [
                'text' => $priceData['subtotal'],
                'feed' => $feedSubtotal,
                'font' => 'bold',
                'align' => 'right',
            ];
            $i++;
        }

        // draw Tax
        $lines[0][] = [
            'text' => $order->formatPriceTxt($item->getTaxAmount()),
            'feed' => 495,
            'font' => 'bold',
            'align' => 'right',
        ];

        // custom options
        $options = $this->getItemOptions();
        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $lines[][] = [
                    'text' => $this->string->split($this->filterManager->stripTags($option['label']), 40, true, true),
                    'font' => 'italic',
                    'feed' => 35,
                ];

                // Checking whether option value is not null
                if ($option['value'] !== null) {
                    if (isset($option['print_value'])) {
                        $printValue = $option['print_value'];
                    } else {
                        $printValue = $this->filterManager->stripTags($option['value']);
                    }
                    $values = explode(', ', $printValue);
                    foreach ($values as $value) {
                        $lines[][] = ['text' => $this->string->split($value, 30, true, true), 'feed' => 40];
                    }
                }
            }
        }

        $lineBlock = ['lines' => $lines, 'height' => 20];

        $page = $pdf->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $this->setPage($page);
    }

    /**
     * Draw item line for without edition
     *
     * @return void
     */
    public function drawWithoutEdition()
    {
        $order = $this->getOrder();
        $item = $this->getItem();
        $pdf = $this->getPdf();
        $page = $this->getPage();
        $lines = [];

        // Getting Edition info.
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $reportData = $objectManager->create('Adtones\Stock\Model\Report');
        $customHelper = $objectManager->get('Adtones\Stock\Helper\Data');

        $orderFactory = $reportData->getCollection()->addFieldToFilter('sku', array('eq' => $this->getSku($item)))
                                                                        ->addFieldToFilter('order_id', array('eq' => $order->getIncrementId()));
                                                                        
        $productEditionNumber = "";
        $collectionSize = '';
        if($orderFactory->getSize() >= 1){
            foreach ($orderFactory as $report) {
                $productEditionNumber = $productEditionNumber.' Edition: '.$report->getEditionNumber().', ';
                $collectionSize = $customHelper->getCollectionSize($report->getSku());
            }
        }
        $editionInfo = '';
        if($collectionSize == 1){
            $editionInfo = "Edition: Unique";
        }else{
            for ($i = 0; $i < strlen($productEditionNumber)-2; $i++){
                $editionInfo = $editionInfo.''.$productEditionNumber[$i];
            }
        }

        // draw Product name
        $lines[0] = [['text' => $this->string->split($item->getName().' ('.$editionInfo.')', 35, true, true), 'feed' => 35]];

        // draw SKU
        $lines[0][] = [
            'text' => $this->string->split($this->getSku($item), 17),
            'feed' => 290,
            'align' => 'right',
        ];

        // draw QTY
        $lines[0][] = ['text' => $item->getQty() * 1, 'feed' => 435, 'align' => 'right'];

        // draw item Prices
        $i = 0;
        $prices = $this->getItemPricesForDisplay();
        $feedPrice = 395;
        $feedSubtotal = $feedPrice + 170;
        foreach ($prices as $priceData) {
            if (isset($priceData['label'])) {
                // draw Price label
                $lines[$i][] = ['text' => $priceData['label'], 'feed' => $feedPrice, 'align' => 'right'];
                // draw Subtotal label
                $lines[$i][] = ['text' => $priceData['label'], 'feed' => $feedSubtotal, 'align' => 'right'];
                $i++;
            }
            // draw Price
            $lines[$i][] = [
                'text' => $priceData['price'],
                'feed' => $feedPrice,
                'font' => 'bold',
                'align' => 'right',
            ];
            // draw Subtotal
            $lines[$i][] = [
                'text' => $priceData['subtotal'],
                'feed' => $feedSubtotal,
                'font' => 'bold',
                'align' => 'right',
            ];
            $i++;
        }

        // draw Tax
        $lines[0][] = [
            'text' => $order->formatPriceTxt($item->getTaxAmount()),
            'feed' => 495,
            'font' => 'bold',
            'align' => 'right',
        ];

        // custom options
        $options = $this->getItemOptions();
        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $lines[][] = [
                    'text' => $this->string->split($this->filterManager->stripTags($option['label']), 40, true, true),
                    'font' => 'italic',
                    'feed' => 35,
                ];

                // Checking whether option value is not null
                if ($option['value'] !== null) {
                    if (isset($option['print_value'])) {
                        $printValue = $option['print_value'];
                    } else {
                        $printValue = $this->filterManager->stripTags($option['value']);
                    }
                    $values = explode(', ', $printValue);
                    foreach ($values as $value) {
                        $lines[][] = ['text' => $this->string->split($value, 30, true, true), 'feed' => 40];
                    }
                }
            }
        }

        $lineBlock = ['lines' => $lines, 'height' => 20];

        $page = $pdf->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $this->setPage($page);
    }

}
