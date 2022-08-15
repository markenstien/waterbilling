<?php 

	class QueueController extends Controller
	{

		public function __construct()
		{
			parent::__construct();

			$this->model = model('QueueModel');
		}

		/*
		*staff page
		*/
		public function index()
		{

			$this->data['page_title'] = 'Queing';

			$this->data['waiting_serving'] = $this->model->getWaitingAndServing();
			$this->data['total_served'] = $this->model->getTotalServed();

			return $this->view('queue/index' , $this->data);
		}

		public function new()
		{
			$res = $this->model->new();

			if($res) {
				Flash::set($this->model->getMessageString());
			}else{
				Flash::set("Something went wrong",'danger');
			}

			return redirect(_route('queue:index'));
		}

		public function serve($id)
		{
			$this->model->update([
				'status' => 'serving'
			] , $id);

			Flash::set('Serving');
			return redirect(_route('queue:index'));
		}

		public function complete($id)
		{
			$this->model->update([
				'status' => 'completed'
			] , $id);

			Flash::set("Completed");
			return redirect(_route('queue:index'));
		}

		public function skip($id)
		{
			$this->model->update([
				'status' => 'skipped'
			] , $id);

			Flash::set("Skipped");
			return redirect(_route('queue:index'));
		}

		public function live()
		{
			$this->data['page_title'] = 'Queing';

			return $this->view('queue/live' , $this->data);
		}

		public function reset()
		{
			$this->model->reset();

			Flash::set("Reset");
			return redirect(_route('queue:index'));
		}
	}