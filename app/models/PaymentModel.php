<?php

    class PaymentModel extends Model
    {
        public $table = 'payments';
        public $_fillables = [
            'order_id',
            'reference',
            'amount',
            'payment_method',
            'payer_name',
            'mobile_number',
            'address',
            'remarks',
            'organization',
            'account_number',
            'external_reference',
            'created_by'
        ];

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