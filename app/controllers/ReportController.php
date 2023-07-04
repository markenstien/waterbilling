<?php 
	use Services\UserService;
	use Services\ReportService;

	load(['UserService', 'ReportService'], SERVICES);
	class ReportController extends Controller
	{

		public function __construct() {
			parent::__construct();

			$this->modelTransaction = model('TransactionModel');
			$this->modelPayment = model('PaymentModel');
			$this->modelCustomer = model('CustomerModel');

			$this->seviceReport = new ReportService();
		}

		public function index() {
			$req = request()->inputs();

			if(!empty($req['btn_report'])) {
				if(isEqual(whoIs('user_type'), UserService::TYPE_PLATFORM)) {
					//additional queries
					$payments = $this->modelPayment->getAll([
						'where' => [
							'date(payment.created_at)' => [
								'condition' => 'between',
								'value' => [$req['start_date'], $req['end_date']]
							],
							'pl.id' => [
								'condition' => 'equal',
								'value' => whoIs('parent_id')
							]
						]
					]);
					
					$customers = $this->modelCustomer->all();

					$this->seviceReport->setPayments($payments);

					$summary = $this->seviceReport->generateSummary();

					$this->data['summary'] = $summary;

				} elseif(isEqual(whoIs('user_type'), UserService::TYPE_VENDOR)) {
					//do vendor queries

					$transactions = $this->modelTransaction->all([
						'created_at' => [
							'condition' => 'between',
							'value' => [$req['start_date'], $req['end_date']]
						]
					]);

					$payments = $this->modelPayment->getAll([
						'where' => [
							'date(payment.created_at)' => [
								'condition' => 'between',
								'value' => [$req['start_date'], $req['end_date']]
							]
						]
					]);

					$customers = $this->modelCustomer->all();

					$this->seviceReport->setPayments($payments);

					$summary = $this->seviceReport->generateSummary();

					$this->data['summary'] = $summary;
				}
			}

			return $this->view('report/index', $this->data);
		}
	}