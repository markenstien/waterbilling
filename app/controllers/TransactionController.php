<?php

    use Form\PaymentForm;
    use Form\PaymentOnlineForm;
    use Form\PurchaseItemForm;
    use Services\OrderService;

    load(['PurchaseItemForm','PaymentForm','PaymentOnlineForm'], APPROOT.DS.'form');
    load(['OrderService'], APPROOT.DS.'services');
    class TransactionController extends Controller
    {
        
        public function __construct()
        {
            $this->model = model('OrderItemModel');
            $this->paymentForm = new PaymentForm();
            $this->paymentOnlineForm = new PaymentOnlineForm();

            $this->order = model('OrderModel');
        }

        /**
         * purchasing action
         */
        public function purchase() {
            $purchaseSession = OrderService::getPurchaseSession();

            if (empty($purchaseSession)) {
                OrderService::startPurchaseSession();
            }

            $request = request()->inputs();
            if (isSubmitted()) {
                $res = $this->model->addOrUpdatePurchaseItem($request, $request['id'] ?? null);
                if (!$res) {
                    Flash::set($this->model->getErrorString(), 'danger');
                } else {
                    Flash::set("Item added");
                }
                return redirect(_route('transaction:purchase'));
            }

            $items = $this->model->getCurrentSession();
            $purchaseItemForm = new PurchaseItemForm();

            if (isset($request['action'], $request['id'])) {
                if ($request['action'] == 'edit_item') {
                    $item = $this->model->get($request['id']);
                    $purchaseItemForm->setValueObject($item);
                    $purchaseItemForm->addItem($item->item_id);
                }

                if($request['action'] == 'delete_item') {
                    $this->model->deleteItem($request['id']);
                    Flash::set("Item Removed");
                    return redirect(_route('transaction:purchase'));
                }
            }
            $this->data['items'] = $items;
            $this->data['session'] = OrderService::getPurchaseSession();
            //get total
            $totalAmountToPay = $this->model->getItemTotal($items);
            $this->paymentForm->setValue('amount',$totalAmountToPay);
            $this->data['totalAmountToPay'] = $totalAmountToPay;
            $this->data['purchase_item_form'] = $purchaseItemForm;
            $this->data['paymentForm'] = $this->paymentForm;
            $this->data['paymentOnlineForm'] = $this->paymentOnlineForm;
            return $this->view('transaction/purchase',$this->data);
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