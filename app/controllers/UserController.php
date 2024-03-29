<?php 
	load(['CustomerForm','AddressForm','ContainerForm','UserForm'] , APPROOT.DS.'form');
	use Form\CustomerForm;
	use Form\AddressForm;
	use Form\ContainerForm;
	use Form\UserForm;
	use Services\UserService;

class UserController extends Controller
	{

		public function __construct()
		{
			parent::__construct();
			$this->data['form'] = new CustomerForm();
			$this->data['containerForm'] = new ContainerForm();
			$this->data['user_form'] = new UserForm();
			$this->data['address'] = new AddressForm();
			
			$this->model = model('UserModel');
			$this->transaction = model('TransactionModel');
			$this->customerModel = model('CustomerModel');
			$this->addressModel = model('AddressModel');
			$this->platformModel = model('PlatformModel');
		}

		public function createCustomer(){
			$request = request()->inputs();
			if(isSubmitted()) {
				$addressId = $this->addressModel->createOrUpdate($request['address']);
				if($addressId) {
					$request['address_id'] = $addressId;
					$customerId = $this->customerModel->createOrUpdate($request);
					if($customerId) {
						Flash::set($this->customerModel->getMessageString());
					}else{
						Flash::set($this->customerModel->getErrorString(), 'danger');
					}
					return redirect(_route('platform:show', $request['parent_id']));
				}else{
					Flash::set($this->addressModel->getErrorString(), 'danger');
					return request()->return();
				}
			}

			$this->data['isVendor'] = authPropCheck($this->_userService::ACCESS_VENDOR_MANAGEMENT);
			if(!$this->data['isVendor']) {
				$platforms = $this->platformModel->all(['id' => $this->data['whoIs']->parent_id]);
				$this->data['platformId'] = $this->data['whoIs']->parent_id;
			} else {
				$platforms = $this->platformModel->all(null,'platform_name asc');
			}
			$this->data['platforms'] = $platforms;
			return $this->view('user/create_customer',$this->data);
		}


		public function customers() {
			$req = request()->inputs();
			$condition = [];

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

			$condition['cx.is_active'] = [
				'condition' => 'equal',
				'value' => true
			];

			if(!authPropCheck($this->_userService::ACCESS_VENDOR_MANAGEMENT)) {
				$condition['cx.parent_id'] = $this->data['whoIs']->parent_id;
				$this->data['customers'] = $this->customerModel->getList([
					'where' => $condition
				]);

			}else{
				$this->data['customers'] = $this->customerModel->getList([
					'where' => $condition
				]);
			}
			return $this->view('user/customers', $this->data);
		}

		public function editCustomer($id) {
			$request = request()->inputs();

			if (isSubmitted()) {
				$addressId = $this->addressModel->createOrUpdate($request['address'], $request['address']['id']);
				$request['address_id'] = $addressId;
				$customerId = $this->customerModel->createOrUpdate($request, $request['customer_id']);

				if($customerId) {
					Flash::set($this->customerModel->getMessageString());
				}else{
					Flash::set($this->customerModel->getErrorString());
				}

				return redirect(_route('user:editCustomer', $id));
			}

			$customer = $this->customerModel->get($id);
			$this->data['platforms'] = $this->platformModel->all(null,'platform_name asc');
			$this->data['form']->setValueObject($customer);
			$this->data['form']->init([
				'url' => _route('user:editCustomer', $id)
			]);

			$this->data['address']->setValue('address[house_number]',$customer->address->house_number);
			$this->data['form']->setValue('password', '');
			if(!isEqual(whoIs('user_type'), 'customer')) {
				$this->data['address']->setValue('address[street_id]',$customer->address->street_id);
				$this->data['address']->setValue('address[barangay]',$customer->address->barangay);
				$this->data['address']->setValue('address[city]',$customer->address->city);
			} else {
				$this->data['address']->add([
					'type' => 'hidden',
					'name' => 'address[street_id]',
					'value' => $customer->address->street_id
				]);

				$this->data['address']->add([
					'type' => 'hidden',
					'name' => 'address[barangay]',
					'value' => $customer->address->barangay
				]);

				$this->data['address']->add([
					'type' => 'hidden',
					'name' => 'address[city]',
					'value' => $customer->address->city
				]);
			}

			$this->data['form']->add([
				'name' => 'password',
				'type' => 'password',
				'class' => 'form-control',
				'options' => [
					'label' => 'Password'
				]
			]);
			$this->data['address']->add([
				'name' => 'address[id]',
				'value' => $customer->address->id,
				'type' => 'hidden'
			]);
			$this->data['customer'] = $customer;
			$this->data['form']->add([
				'name' => 'customer_id',
				'value' => $id,
				'type' => 'hidden'
			]);

			return $this->view('user/edit_customer',$this->data);
		}

		public function showCustomer($customerId) {
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
		}
		public function index()
		{
			$params = request()->inputs();

			if(!authPropCheck($this->_userService::ACCESS_VENDOR_MANAGEMENT)) {
				$params['parent_id'] = $this->data['whoIs']->parent_id;
			}

			if(!empty($params))
			{
				$this->data['users'] = $this->model->getAll([
					'where' => $params
				]);
			}else{
				$this->data['users'] = $this->model->getAll( );
			}
			

			return $this->view('user/index' , $this->data);
		}

		public function create()
		{
			if(isSubmitted()) {
				$post = request()->posts();
				$user_id = $this->model->create($post , 'profile');
				if(!$user_id){
					Flash::set($this->model->getErrorString() , 'danger');
					return request()->return();
				}
				Flash::set('User Record Created');
				return redirect( _route('user:show' , $user_id , ['user_id' => $user_id]) );
			}
			$this->data['user_form'] = new UserForm('userForm');

			$this->data['isVendor'] = authPropCheck($this->_userService::ACCESS_VENDOR_MANAGEMENT);
			if(!$this->data['isVendor']) {
				$this->data['user_form']->setOptionValues('parent_id', arr_layout_keypair($this->platformModel->all(['id' => $this->data['whoIs']->parent_id]), ['id','platform_name']));
				$this->data['user_form']->setValue('parent_id', $this->data['whoIs']->parent_id);
			}

			return $this->view('user/create' , $this->data);
		}

		public function edit($id)
		{
			if(isSubmitted()) {
				$post = request()->posts();
				$post['profile'] = 'profile';
				$res = $this->model->update($post , $post['id']);

				if($res) {
					Flash::set( $this->model->getMessageString());
					return redirect( _route('user:show' , $id) );
				}else
				{
					Flash::set( $this->model->getErrorString() , 'danger');
					return request()->return();
				}
			}

			$user = $this->model->get($id);

			$this->data['id'] = $id;
			$this->data['user_form']->init([
				'url' => _route('user:edit',$id)
			]);

			$this->data['user_form']->setValueObject($user);
			$this->data['user_form']->addId($id);
			$this->data['user_form']->remove('submit');
			$this->data['user_form']->add([
				'name' => 'password',
				'type' => 'password',
				'class' => 'form-control',
				'options' => [
					'label' => 'Password'
				]
			]);

			if(!isEqual(whoIs('user_type'), 'admin'))
				$this->data['user_form']->remove('user_type');

			return $this->view('user/edit' , $this->data);
		}

		public function show($id)
		{
			$user = $this->model->get($id);
			if(!$user) {
				Flash::set(" This user no longer exists " , 'warning');
				return request()->return();
			}
			$this->data['user']  = $user;

			return $this->view('user/show' , $this->data);
		}

		public function sendCredential($id)
		{
			$this->model->sendCredential($id);
			Flash::set("Credentials has been set to the user");
			return request()->return();
		}

		public function deleteCustomer($id) {
			$res = $this->customerModel->deleteCustomer($id);
			if ($res) {
				Flash::set($this->customerModel->getMessageString());
			} else {
				Flash::set($this->customerModel->getErrorString());
			}
			return request()->return();
		}
	}