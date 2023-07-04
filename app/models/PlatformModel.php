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

            $req = request()->inputs();

            $condition = [
                'cx.parent_id' => $parentId,
                'cx.is_active' => true
            ];

            if(!empty($req['filter_option'])) {
                $conditionBalance = isEqual($req['filter_option'],'with_balance');

                if($conditionBalance) {
                    $condition['vub.balance'] = [
                        'condition' => '<',
                        'value' => 0
                    ];
                }else if(isEqual($req['filter_option'],'with_points')) {
                    $condition['cm.points'] = [
                        'condition' => '>',
                        'value' => 0
                    ];
                }
            }

            $customers = $this->customerModel->getList([
                'where' => $condition
            ]);

            return $customers;
        }
    }