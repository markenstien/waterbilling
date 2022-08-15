<?php
	class DashboardController extends Controller
	{
		public function __construct()
		{
			$this->user_model = model('UserModel');
		}

		public function index()
		{
			$this->data['page_title'] = 'Dashboard';
			return $this->view('dashboard/index', $this->data);
		}
	}