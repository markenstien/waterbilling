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
            $this->modelCustomer = model('CustomerModel');
            $this->transaction = model('TransactionModel');
            $this->customerMeta = model('CustomerMetaModel');
            $this->modelPlatform = model('PlatformModel');
        }

        public function create() {
            $request = request()->inputs();
            $message = '';
            if (isSubmitted()) {
                $post = request()->posts();
                $paymentStatus = isEqual(whoIs('user_type'), 'customer') ? 'Pending': 'Approved';

                $customer = $this->modelCustomer->get($request['customer_id']);
                $paytmentMethod = null;

                $paymentData = [
                    'customer_id' => $post['customer_id'],
                    'amount' => $post['amount'],
                    'created_at' => nowMilitary(),
                    'created_by' => whoIs('id'),
                    'parent_id' => $post['parent_id']
                ];
                if (isset($post['btn_cash'])) 
                {
                    $paytmentMethod = PaymentService::METHOD_CASH;
                    $paymentData['approval_status'] = $paymentStatus;
                    $paymentData['approval_date'] = nowMilitary();
                
                } elseif (isset($post['btn_gcash'])) {
                    if (empty($post['gcash_reference']) || empty($post['mobile_number'])) {
                        Flash::set("GCASH REFERENCE OR MOBILE NUMBER is required if payment is GCASH", 'danger');
                        return request()->return();
                    } else {

                        $result = $this->model->validateReferenceAndNumber(
                            $post['gcash_reference'], $post['mobile_number']
                        );
                        
                        if(!$result) {
                            Flash::set($this->model->getErrorString(), 'danger');
                            return request()->return();
                        }
                    }
                    $paytmentMethod = PaymentService::METHOD_GCASH;
                    $paymentData['payment_reference'] = $post['gcash_reference'];
                    $paymentData['payer_account'] = $post['mobile_number'];
                    $paymentData['approval_status'] = $paymentStatus;
                }elseif(isset($post['btn_redeem'])) {
                    $paytmentMethod = PaymentService::METHOD_POINTS;
                    $paymentData['approval_status'] = $paymentStatus;
                    $paymentData['approval_date'] = nowMilitary();

                    if($post['points'] < PaymentService::POINT_ACCCEPTED) {
                        Flash::set("Not enough points");
                        return request()->return();
                    }
                    $paymentData['amount'] = $post['points'];
                }
                
                $paymentData['payment_method'] = $paytmentMethod;
                $result = $this->model->create($paymentData);
                if($result) {

                    if(isEqual($paymentStatus,'approved')) {
                        $this->transaction->store([
                            'parent_key' => TransactionService::PAYMENT,
                            'parent_id' => $result,
                            'customer_id' => $post['customer_id'],
                            'amount' => amountConvert($paymentData['amount'], 'ADD'),
                            'platform_id' => $customer->platform_id,
                            'user_id' => whoIs('id'),
                            'created_at' => nowMilitary()
                        ]);
                    } else {
                        $message = "Payment pending for admin confirmation, payment will automatically reflect once payment is verified";
                    }

                    if (isEqual($paytmentMethod, PaymentService::METHOD_POINTS)) {
                        $this->customerMeta->savePoint($post['customer_id'], amountConvert($post['points'],'DEDUCT'));
                        //check balance
                        $amountToPay = $this->transaction->getTotalByCustomer($post['customer_id']);
                        Flash::set("Points redeemd successfully amount deducted({$post['points']})");
                        if($amountToPay <= 0) {
                            return redirect(_route('payment:create',null,['customerId' => $post['customer_id']]));
                        }
                    } else {
                        $this->customerMeta->savePoint($post['customer_id'], 1);
                    }
                }

                Flash::set(TransactionService::PAYMENT . ' '.$message . ' Successfull');
                return redirect(_route('transaction:index'));
            }

            $customerId = $request['customerId'];
            $amountToPay = $this->transaction->getTotalByCustomer($customerId);
            $customer = $this->modelCustomer->get($customerId);

            $this->data['amountToPay'] = $amountToPay;
            $this->data['customer'] = $customer;
            $this->data['paymentServicePointAccepted'] = PaymentService::POINT_ACCCEPTED;
            $this->data['parentId'] = whoIs('parent_id');
            $this->data['platform'] = $this->modelPlatform->get(whoIs('parent_id'));
            
            return $this->view('payment/create', $this->data);
        }
        public function index() {
            $order = 'FIELD(approval_status, "pending","approved", "declined")';

            if(authPropCheck($this->_userService::TYPE_CUSTOMER,'user_type')) {
                $condition['payment.customer_id'] = $this->data['whoIs']->id;
                $this->data['payments'] = $this->model->getAll([
                    'where' => $condition,
                    'order' => $order
                ]);

            }elseif(authPropCheck($this->_userService::TYPE_PLATFORM,'user_type')) {
                $condition['payment.parent_id'] = $this->data['whoIs']->parent_id;
                $this->data['payments'] = $this->model->getAll([
                    'where' => $condition,
                    'order' => $order
                ]);
            }
            else{
                $this->data['payments'] = $this->model->getAll(['order' => $order]);
            }
            return $this->view('payment/index', $this->data);
        }

        public function show($id) {
            $this->data['payment'] = $this->model->get($id);
            return $this->view('payment/show', $this->data);
        }

        public function approve($id) {
            $isOkay = $this->model->update([
                'approval_by' => whoIs('id'),
                'approval_status' => 'approved',
                'approval_date' => nowMilitary(),
            ], $id);


            if($isOkay) {
                $payment = $this->model->get($id);

                $this->transaction->store([
                    'parent_key' => TransactionService::PAYMENT,
                    'parent_id' => $id,
                    'customer_id' => $payment->customer_id,
                    'amount' => amountConvert($payment->amount, 'ADD'),
                    'platform_id' => $payment->parent_id,
                    'user_id' => whoIs('id'),
                    'created_at' => nowMilitary()
                ]);
            }
            Flash::set("Payment approved");
            return request()->return();
        }

        public function decline($id) {
            $this->model->update([
                'approval_status' => 'declined',
            ], $id);

            Flash::set("Payment Declined");
            return request()->return();
        }
    }