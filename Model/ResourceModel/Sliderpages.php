<?php
namespace Excellence\Slider\Model\ResourceModel;
class Sliderpages extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('excellence_slider_sliderpages','id');
    }
    public function getSliderId($pageTypeId, $currentPageId, $position, $storeId)
    {        
    	$table = $this->getMainTable();
        $whereTypeId = $this->getConnection()->quoteInto("slider_display_page = ?", $pageTypeId);
        if($pageTypeId == 1){
            $wherePageId = $this->getConnection()->quoteInto("slider_specific_display_page_cms = ?", $currentPageId);
        }
        else if($pageTypeId == 2){
            $catId = (int)$currentPageId;
            $wherePageId = $this->getConnection()->quoteInto("slider_specific_display_page_category like '%\"?\"%'",  $catId);
          }
       
        else if($pageTypeId == 3){
            $productId = (int)$currentPageId;
            $wherePageId = $this->getConnection()->quoteInto("slider_specific_display_page_product like '%\"?\"%'", $productId);
        }
        else{
            $wherePageId = null;
        }
        $whereStoreId = $this->getConnection()->quoteInto("store_id REGEXP '[[:<:]](?)[[:>:]]'", (int)$storeId);
        if($wherePageId != null){
            $wherePosition = $this->getConnection()->quoteInto("slider_position = ?", $position);
           
            $whereStatus = $this->getConnection()->quoteInto("is_active = ?", 1);
            // Get Store Id from sliderpages table
            $storeSql = $this->getConnection()->select()
                ->from($table,array('store_id'))
                ->where($whereTypeId)
                ->where($wherePageId)
                ->where($wherePosition)
                ->where($whereStatus);
            $store_id = $this->getConnection()->fetchOne($storeSql);
            if($store_id == 0){
                // if store view is set to all
                $sql = $this->getConnection()->select()
                    ->from($table,array('id'))
                    ->where($whereTypeId)
                    ->where($wherePageId)
                    ->where($wherePosition)
                    ->where($whereStatus);
            }
            else{
                // specific store
                $sql = $this->getConnection()->select()
                    ->from($table,array('id'))
                    ->where($whereTypeId)
                    ->where($wherePageId)
                    ->where($wherePosition)
                    ->where($whereStoreId)
                    ->where($whereStatus);
            }
        } else{
            $wherePosition = $this->getConnection()->quoteInto("slider_position = ?", $position);
            $whereStatus = $this->getConnection()->quoteInto("is_active = ?", 1);
            // Get Store Id from sliderpages table
            $storeSql = $this->getConnection()->select()
                ->from($table,array('store_id'))
                ->where($whereTypeId)
                ->where($wherePosition)
                ->where($whereStoreId)
                ->where($whereStatus);
            $store_id = $this->getConnection()->fetchOne($storeSql);
            if($store_id == 0){
                // if store view is set to all
                $sql = $this->getConnection()->select()
                    ->from($table,array('id'))
                    ->where($whereTypeId)
                    ->where($wherePosition)
                    ->where($whereStatus);
            }
            else{
                // specific store
                $sql = $this->getConnection()->select()
                    ->from($table,array('id'))
                    ->where($whereTypeId)
                    ->where($wherePosition)
                    ->where($whereStoreId)
                    ->where($whereStatus);
            }
        }
        $id = $this->getConnection()->fetchOne($sql);
        return $id;
    }
}
