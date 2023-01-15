<?php

    use Form\PaymentForm;
    use Form\PaymentOnlineForm;
    use Services\OrderService;
    use Services\TransactionService;

    load(['PaymentForm','PaymentOnlineForm'], APPROOT.DS.'form');
    load(['OrderService', 'TransactionService'], APPROOT.DS.'services');
    class TransactionController extends Controller
    {
        
        public function __construct()
        {
            parent::__construct();
            $this->paymentForm = new PaymentForm();
            $this->paymentOnlineForm = new PaymentOnlineForm();
            $this->container = model('ContainerModel');
            $this->transaction = model('TransactionModel');
        }
        
        public function index() {
            if (!authPropCheck($this->_userService::ACCESS_VENDOR_MANAGEMENT)) {

                if(authPropCheck('customer')) {
                    $this->data['containers'] = $this->container->getList([
                        'where' => [
                            'customer_id' => $this->data['whoIs']->id
                        ]
                    ]);
                } else {
                    $this->data['containers'] = $this->container->getList([
                        'where' => [
                            'platform_id' => $this->data['whoIs']->parent_id
                        ]
                    ]);
                }
                
            } else {
                $this->data['containers'] = $this->container->getList();
            }
            $this->data['action'] = [
                'delivery' => TransactionService::DELIVERY,
                'pick_up' => TransactionService::PICKUP
            ];
            return $this->view('transaction/index', $this->data);
        }

        public function deliverOrPickup(){
            $request = request()->inputs();

            if(isEqual($request['action'], TransactionService::DELIVERY)) {
                $action_taken = TransactionService::DELIVERY;
            }else{
                $action_taken = TransactionService::PICKUP;
            }
            $container = $this->container->get($request['id']);
            
            $res = $this->transaction->pickUpOrDelivery($container, $action_taken);

            if($res) {
                Flash::set("{$action_taken} Success");
                return redirect(_route('payment:create', null, [
                    'customerId' => $container->customer_id
                ]));
            } else {
                Flash::set($this->transaction->getErrorString(), 'danger');
                return request()->return();
            }
        }
        /**
         * purchasing action
         */
        public function purchase() {
           
        }

        public function purchaseResetSession(){
            csrfValidate();
            $this->model->resetPurchaseSession();
            return redirect(_route('transaction:purchase'));
        }


        public function savePayment() {
            $request = request()->inputs();
            if(isSubmitted()) {
                $errors = [];
                if($request['payment_method'] === 'ONLINE') {
                    /**
                     * organization
                     * external reference and account_number should be valid
                     */
                    $checkVals = ['organization','external_reference','account_number'];

                    foreach($checkVals as $key => $row) {
                        if(empty($request[$row])) {
                            $errors[] = "{$row}";
                        }
                    }

                    if(!empty($errors)) {
                        Flash::set(implode(',', $errors) . "should not be empty if payment method is ONLINE",'danger');
                        return request()->return();
                    }
                }


                $session = $this->model->getCurrentSessionId();
                $items = $this->model->getCurrentSession();
                $itemSummary = $this->model->getItemSummary($items);

                if(empty($items)) {
                    Flash::set("There are no orders found!");
                    return request()->return();
                }

                $orderData = [
                    'customer_name' => $request['payer_name'],
                    'mobile_number' => $request['mobile_number'],
                    'address' => $request['address'],
                    'remarks' => $request['remarks'],
                    'gross_amount' => $itemSummary['grossAmount'],
                    'net_amount' => $itemSummary['netAmount'],
                    'discount_amount' => $itemSummary['discountAmount'],
                    'remarks' => $request['remarks'],
                    'id' => $session
                ];

                $paymentData = [
                    'order_id' => $session,
                    'amount' => $itemSummary['netAmount'],
                    'payment_method' => $request['payment_method'],
                    'account_number' => $request['account_number'],
                    'organization' => $request['organization'],
                    'external_reference' => $request['external_reference'],
                    'remarks' => $request['remarks'],
                ];

                $result = $this->order->placeAndPay($orderData, $paymentData);
                
                if($result) {
                    OrderService::endPurchaseSession();
                    OrderService::startPurchaseSession();//reset order
                    
                    Flash::set($this->order->getMessageString());
                    return redirect(_route('receipt:order', $session));
                } 
            }
        }
    }