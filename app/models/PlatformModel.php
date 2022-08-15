<?php

    class PlatformModel extends Model
    {
        public $table = 'platforms';

        public $_fillables = [
            'platform_name',
            'contact_number',
            'is_active'
        ];
        
        public function createOrUpdate($platformData, $id = null) {
            $platformData['_token'] = 'reference';
            return parent::createOrUpdate($platformData, $id);
        }

        public function getCustomers($parentId) {
            if(!isset($this->customerModel)) {
                $this->customerModel = model('CustomerModel');
            }

            $customers = $this->customerModel->getList([
                'where' => [
                    'cx.parent_id' => $parentId
                ]
            ]);

            return $customers;
        }
    }