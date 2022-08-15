<?php
    load(['CategoryService'],SERVICES);
    use Services\CategoryService;

    class ItemModel extends Model
    {
        public $table = 'items';
        public $_fillables = [
            'name',
            'sku',
            'barcode',
            'cost_price',
            'sell_price',
            'min_stock',
            'max_stock',
            'category_id',
            'variant',
            'remarks',
            'is_visible'
        ];
        
        public function createOrUpdate($itemData, $id = null) {
            $retVal = null;
            $_fillables = $this->getFillablesOnly($itemData);
            $item = $this->getItemByUniqueKey($itemData['sku'], $itemData['name']);

            if (!is_null($id)) {
                if($item && ($item->id != $id)) {
                    $this->addError("SKU Or Name Already exists");
                    return false;
                }
                $retVal = parent::update($_fillables, $id);
            } else {
                if($item) {
                    $this->addError("SKU Or Name Already exists");
                    return false;
                }
                $retVal = parent::store($_fillables);
            }

            return $retVal;
        }

        public function getImages($id) {
            $attachModel = model('AttachmentModel');
            return $attachModel->all([
                'global_id' => $id,
                'global_key' => CategoryService::ITEM
            ]);
        }


        private function getItemByUniqueKey($sku,$name) {
            return parent::single([
                'sku' => [
                    'condition' => 'equal',
                    'value' => $sku,
                    'concatinator' => 'OR'
                ],
                'name' => [
                    'condition' => 'equal',
                    'value' => $name
                ],
            ]);
        }

         /**
         * override Model:get
         */
        public function get($id) {
            $productQuantitySQL = $this->_productTotalStockSQLSnippet();
            $this->db->query(
                "SELECT item.* , stock.total_stock as total_stock 
                    FROM {$this->table} as item 
                    LEFT JOIN (
                        $productQuantitySQL
                    ) as stock 
                    ON stock.item_id = item.id
                    WHERE id = '{$id}'"
            );

            return $this->db->single();
        }

        /**
         * override Model:all
         */
        public function all($where = null , $order_by = null , $limit = null) {
            $productQuantitySQL = $this->_productTotalStockSQLSnippet();

            if(!is_null($where)) {
                $where = " WHERE " . parent::conditionConvert($where);
            }

            if(!is_null($order_by)) {
                $order_by = " ORDER BY {$order_by} ";
            }

            $this->db->query(
                "SELECT item.*, ifnull(stock.total_stock, 'No Stock') as total_stock
                    FROM {$this->table} as item 
                    LEFT JOIN ($productQuantitySQL) as stock
                    ON stock.item_id = item.id
                    {$where} {$order_by} {$limit}"
            );

            return $this->db->resultSet();
        }

        private function _productTotalStockSQLSnippet() {
            return "SELECT SUM(quantity) as total_stock ,item_id
            FROM stocks 
            GROUP BY item_id";
        }

        public function totalItem() {
            $this->db->query(
                "SELECT count(id) as total_item
                    FROM {$this->table}"
            );
            return $this->db->single()->total_item ?? 0;
        }
    }
