<?php 

    class ContainerModel extends Model {

        public $table ='containers';
        
        public $_fillables = [
            'container_label',
            'container_type',
            'platform_id',
            'customer_id',
        ];
        
        public function createOrUpdate($platformData, $id = null)
        {
            $container = parent::single([
                'container_label' => $platformData['container_label']
            ]);

            if($container) {
                $this->addError("Container {$platformData['container_label']} Already exists");
                return false;
            }
            return parent::createOrUpdate($platformData, $id);
        }

        public function getList($params = []) {
            $this->db->query(
                "SELECT container.container_label,
                    container.container_type,
                    cx.full_name,
                    cx.id as cx_id,
                    pl.platform_name,
                    pl.id as platform_id,
                    cx.id as customer_id
                    FROM {$this->table} as container
                    LEFT JOIN customers as cx
                    ON cx.id = container.customer_id
                    LEFT JOIN platforms as pl
                    ON pl.id = cx.parent_id"
            );
            return $this->db->resultSet();
        }
    }