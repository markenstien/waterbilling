<?php

	use Form\ContainerForm;
	load(['ContainerForm'], APPROOT.DS.'form');

	class DashboardController extends Controller
	{
		public function __construct()
		{
			$this->user_model = model('UserModel');
			$this->customerModel = model('CustomerModel');
			$this->transaction = model('TransactionModel');

			$this->data['containerForm'] = new ContainerForm();
			authRequired();
		}

		public function index()
		{
			if(isEqual(whoIs('user_type'),'customer')) 
			{
				$customerId = whoIs('id');

				$customer = $this->customerModel->get($customerId);
			
				$this->data['customer'] = $customer;
				$this->data['customerId'] = $customerId;
				$this->data['containerForm']->addRouteTo(
					_route('user:showCustomer', $customerId)
				);
				$this->data['containerForm']->setValue('platform_id', $customer->parent_id);
				$this->data['containerForm']->setValue('customer_id', $customer->id);
				$this->data['containerForm']->setValue('container_label',"({$customer->adrs_str_abbr})-");
				$this->data['containers'] = $this->customerModel->getContainers($customerId);
				$this->data['balance'] = $this->transaction->getTotalByCustomer($customerId);
				return $this->view('user/show_customer', $this->data);
				
			} else {
				$this->data['page_title'] = 'Dashboard';
				return $this->view('dashboard/index', $this->data);
			}
		}
	}