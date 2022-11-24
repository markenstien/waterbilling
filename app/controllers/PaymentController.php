<?php

    use Services\PaymentService;
    use Services\TransactionService;

    load(['PaymentService','TransactionService'],SERVICES);
    class PaymentController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->model = model('PaymentModel');
            $this->customer = model('CustomerModel');
            $this->transaction = model('TransactionModel');
            $this->customerMeta = model('CustomerMetaModel');
        }

        public function create() {
            $request = request()->inputs();
            if (isSubmitted()) {
                $customer = $this->customer->get($request['customer_id']);
                $paytmentMethod = null;

                $paymentData = [
                    'customer_id' => $request['customer_id'],
                    'amount' => $request['amount'],
                    'created_at' => nowMilitary(),
                    'created_by' => whoIs('id')
                ];
                if (isset($request['btn_cash'])) 
                {
                    $paytmentMethod = PaymentService::METHOD_CASH;
                    $paymentData['approval_status'] = 'approved';
                    $paymentData['approval_date'] = nowMilitary();
                
                } elseif (isset($request['btn_gcash'])) {
                    if (empty($request['gcash_reference']) || empty($request['mobile_number'])) {
                        Flash::set("GCASH REFERENCE OR MOBILE NUMBER is required if payment is GCASH", 'danger');
                        return request()->return();
                    } else {

                        $result = $this->model->validateReferenceAndNumber(
                            $request['gcash_reference'], $request['mobile_number']
                        );
                        
                        if(!$result) {
                            Flash::set($this->model->getErrorString(), 'danger');
                            return request()->return();
                        }
                    }
                    $paytmentMethod = PaymentService::METHOD_GCASH;
                    $paymentData['payment_reference'] = $request['gcash_reference'];
                    $paymentData['payer_account'] = $request['mobile_number'];
                    $paymentData['approval_status'] = 'pending';
                }elseif(isset($request['btn_redeem'])) {
                    $paytmentMethod = PaymentService::METHOD_POINTS;
                    $paymentData['approval_status'] = 'approved';
                    $paymentData['approval_date'] = nowMilitary();

                    if($request['points'] < PaymentService::POINT_ACCCEPTED) {
                        Flash::set("Not enough points");
                        return request()->return();
                    }
                    $paymentData['amount'] = $request['points'];
                }
                
                $paymentData['payment_method'] = $paytmentMethod;
                $result = $this->model->create($paymentData);
                if($result) {
                    $this->transaction->store([
                        'parent_key' => TransactionService::PAYMENT,
                        'parent_id' => $result,
                        'customer_id' => $request['customer_id'],
                        'amount' => amountConvert($paymentData['amount'], 'ADD'),
                        'platform_id' => $customer->platform_id,
                        'user_id' => whoIs('id'),
                        'created_at' => nowMilitary()
                    ]);

                    if (isEqual($paytmentMethod, PaymentService::METHOD_POINTS)) {
                        $this->customerMeta->savePoint($request['customer_id'], amountConvert($request['points'],'DEDUCT'));
                        //check balance
                        $amountToPay = $this->transaction->getTotalByCustomer($request['customer_id']);
                        Flash::set("Points redeemd successfully amount deducted({$request['points']})");
                        if($amountToPay <= 0) {
                            return redirect(_route('payment:create',null,['customerId' => $request['customer_id']]));
                        }
                    } else {
                        $this->customerMeta->savePoint($request['customer_id'], 1);
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
            $this->data['paymentServicePointAccepted'] = PaymentService::POINT_ACCCEPTED;
            return $this->view('payment/create', $this->data);
        }
        public function index() {
            $this->data['payments'] = $this->model->all();
            return $this->view('payment/index', $this->data);
        }

        public function show($id) {
            $this->data['payment'] = $this->model->get($id);
            return $this->view('payment/show', $this->data);
        }
    }