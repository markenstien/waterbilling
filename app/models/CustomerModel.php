<?php 

    class CustomerModel extends Model
    {
        public $table = 'customers';
        public $_fillables = [
            'full_name',
            'address_id',
            'parent_id'
        ];
        
        public function get($id) {
            $customer = $this->getList([
                'where' => [
                    'cx.id' => $id
                ]
            ]);

            return $customer ? $customer[0]: false;
        }

        public function getList($params = null) {
            $where = null;
            $order = null;
            $limit = null;

            if(isset($params['where'])) {
                $where = " WHERE ".parent::conditionConvert($params['where']);
            }
            if(isset($params['order'])) {
                $order = " ORDER BY ".$params['order'];
            }
            if(isset($params['limit'])) {
                $limit = " LIMIT {$params['limit']}";
            }

            $this->db->query(
                "SELECT adrs.*, cx.*, cx.id as customer_id,
                    concat(adrs.house_number, ' ',  '(' , sc.abbr , ')', adrs.street, ', Brgy. ', adrs.barangay, ' ',adrs.city,'.') as full_address, 
                    sc.abbr as adrs_str_abbr, pl.platform_name as platform_name, pl.id as platform_id

                    FROM {$this->table} as cx 
                    LEFT JOIN address as adrs
                    ON adrs.id = cx.address_id

                    LEFT JOIN platforms as pl
                    ON pl.id = cx.parent_id

                    LEFT JOIN address_source as sc
                    ON sc.id = adrs.street_id
                    
                    {$where} {$order} {$limit}"
            );
            return $this->db->resultSet();
        }

        public function getContainers($id) {
            if(!isset($this->containerModel)) {
                $this->containerModel = model('ContainerModel');
            }

            return $this->containerModel->all([
                'customer_id' => $id
            ]);
        }
    }