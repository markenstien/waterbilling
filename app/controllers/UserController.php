<?php 
	load(['CustomerForm','AddressForm','ContainerForm','UserForm'] , APPROOT.DS.'form');
	use Form\CustomerForm;
	use Form\AddressForm;
	use Form\ContainerForm;
	use Form\UserForm;

	class UserController extends Controller
	{

		public function __construct()
		{
			parent::__construct();
			$this->data['form'] = new CustomerForm();
			$this->data['address'] = new AddressForm();
			$this->data['containerForm'] = new ContainerForm();
			$this->model = model('UserModel');
			
			$this->customerModel = model('CustomerModel');
			$this->addressModel = model('AddressModel');
			$this->platformModel = model('PlatformModel');
		}

		public function createCustomer(){
			$request = request()->inputs();
			if(isSubmitted()) {
				// dd($request); 
				$addressId = $this->addressModel->createOrUpdate($request['address']);
				if($addressId) {
					$request['address_id'] = $addressId;
					$customerId = $this->customerModel->createOrUpdate($request);

					return redirect(_route('user:showCustomer', $customerId));
				}else{
					Flash::set($this->addressModel->getErrorString(), 'danger');
					return request()->return();
				}
			}

			$this->data['platforms'] = $this->platformModel->all(null,'platform_name asc');
			return $this->view('user/create_customer',$this->data);
		}


		public function customers() {
			$this->data['customers'] = $this->customerModel->getList();
			return $this->view('user/customers', $this->data);
			// return $th
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

			return $this->view('user/show_customer', $this->data);
		}
		public function index()
		{
			$params = request()->inputs();

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
					Flash::set( $this->model->getErrorString() , 'danger');
					return request()->return();
				}

				Flash::set('User Record Created');
				if( isEqual($post['user_type'] , 'patient') )
				{
					Flash::set('Patient Record Created');
					return redirect(_route('patient-record:create' , null , ['user_id' => $user_id]));
				}

				return redirect( _route('user:show' , $user_id , ['user_id' => $user_id]) );
			}
			$this->data['user_form'] = new UserForm('userForm');

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
	}