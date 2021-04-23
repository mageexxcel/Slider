<?php

namespace Adtones\Stock\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateProductQuantity extends Command
{
   protected function configure()
   {
       $this->setName('catalog:product:quantity-update');
       $this->setDescription('To update product quantity after creating an edition.');
       parent::configure();
   }

   protected function execute(InputInterface $input, OutputInterface $output)
   {
        // Using Object Manager here to get edition collection from Stock Table.
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $stockCollection = $objectManager->create('Magento\Catalog\Model\Product')->getCollection()->load();
        $helper = $objectManager->get('Adtones\Stock\Helper\Data');
        
        
        foreach ($stockCollection as $stock) {
            
            $product_id = $stock->getProductId();
            $product = $objectManager->get('Magento\Catalog\Model\Product')->load($product_id);
            $stockRepository = $objectManager->create('Magento\CatalogInventory\Api\StockRegistryInterface');
            $stockItem = $stockRepository->getStockItemBySku($stock->getSku());

            // Getting Edition Number from Edition Collection.
            $stockItem->setQty('999999');
            $stockRepository->updateStockItemBySku($product->getSku(), $stockItem);
            $stockItem->save();            
        }      
        $output->writeln("Product Quantity Successfully Updated."); 
   }
}