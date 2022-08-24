<?php

use Services\TransactionService;

    class PaymentController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->model = model('PaymentModel');
            $this->customer = model('CustomerModel');
            $this->transaction = model('TransactionModel');
        }

        public function create() {
            $request = request()->inputs();
            if (isSubmitted()) {
                $customer = $this->customer->get($request['customer_id']);
                
                if (isset($request['btn_cash'])) {
                    $result = $this->model->create([
                        'customer_id' => $request['customer_id'],
                        'amount' => $request['amount'],
                        'created_at' => nowMilitary(),
                        'payment_method' => 'cash'
                    ]);

                    if($result) {
                        $this->transaction->store([
                            'parent_key' => TransactionService::PAYMENT,
                            'parent_id' => $result,
                            'customer_id' => $request['customer_id'],
                            'amount' => amountConvert($request['amount'], 'ADD'),
                            'platform_id' => $customer->platform_id,
                            'user_id' => whoIs('id'),
                            'created_at' => nowMilitary()
                        ]);
                    }
                }

                Flash::set(TransactionService::PAYMENT . ' Successfull');
                return redirect(_route('transaction:index'));
            }

            $customerId = $request['customerId'];
            $amountToPay = $this->transaction->getTotalByCustomer($customerId);
            $customer = $this->customer->get($customerId);
            $this->data['amountToPay'] = $amountToPay;
            $this->data['customer'] = $customer;

            return $this->view('payment/create', $this->data);
        }
        public function index() {
            $this->data['payments'] = $this->model->all(['is_removed' => false, 'id desc']);
            return $this->view('payment/index', $this->data);
        }

        public function show($id) {
            $this->data['payment'] = $this->model->get($id);
            return $this->view('payment/show', $this->data);
        }
    }