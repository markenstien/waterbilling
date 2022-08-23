<?php

use function PHPSTORM_META\map;

    class PaymentModel extends Model
    {
        public $table = 'payments';
        public $_fillables = [
            'parent_id',
            'parent_key',
            'amount',
            'reference',
            'amount',
            'payment_method',
            'payment_reference',
            'approval_status',
            'approved_by',
            'created_by',
            'created_at'
        ];

        public function create($paymentData) {
            $_fillables = parent::getFillablesOnly($paymentData);
            $_fillables['reference'] = $this->generateRefence();
            $_fillables['amount'] = amountConvert($_fillables['amount'], 'ADD');
            return parent::store($_fillables);
        }   
        public function createOrUpdate($paymentData, $id = null) {
            $_fillables = parent::getFillablesOnly($paymentData);

            if (!is_null($id)) {
                return parent::update($_fillables, $id);
            } else {
                $_fillables['reference'] = $this->generateRefence();
                return parent::store($_fillables);
            }
        }

        public function getOrderPayment($id) {
            return parent::single(['order_id'=>$id]);
        }

        public function generateRefence() {
            return number_series(random_number(7));
        }
    }