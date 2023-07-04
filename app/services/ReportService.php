<?php 
	namespace Services;

	/*
	*create the following summary
	*Profit,Transactions,Customer summary
	*(top 10 most profitable stations, top 10 most active platform, top 10 with highest customers)
	*(top 10 most profitable barangay, top 10 most active barangay, top 10 with highest customers barangay based)
	*/
	class ReportService 
	{

		private $_payments;
		public function setTransactions() {

		}

		public function setPayments($payments) {
			$this->_payments = $payments;
			return $this;
		}	

		public function setCustomers() {

		}

		public function generateSummary() 
		{	

			$retVal = [
				'profit' => [
					'perStations' => [
						'stationId' => [
							'profit' => 100,
							'stationName' => 'something'
						],
						'stationId' => [
							'profit' => 100,
							'stationName' => 'abcd'
						]
					],
					'perBarangay' => []
				],
				'transactions' => [
					'perStations' => [],
					'perBarangay' => []
				],
				'customer' => [
					'perStations' => [],
					'perBarangay' => []
				],
			];

			//profit

			$profitByStations = [];
			$profitByBarangays = [];
			foreach($this->_payments as $key => $row) {
				##PROFIT START
				//stations
				if(!isset($profitByStations[$row->platform_id])) {
					$profitByStations[$row->platform_id] = [
						'profit' => $row->amount,
						'platform_reference' => $row->reference,
						'platform_name' => $row->platform_name,
						'barangay' => $row->cx_street
					];
				} else {
					$profitByStations[$row->platform_id]['profit'] += $row->amount;
				}

				//barangays
				if(!isset($profitByBarangays[$row->cx_street])) {
					$profitByBarangays[$row->cx_street] = [
						'profit' => $row->amount,
						'platform_reference' => $row->reference,
						'platform_name' => $row->platform_name,
						'barangay' => $row->cx_street
					];
				} else {
					$profitByBarangays[$row->cx_street]['profit'] += $row->amount;
				}
				##PROFIT END
			}

			arsort($profitByStations);
			arsort($profitByBarangays);
			
			return [
				'profitReport' => [
					'byStations' => $profitByStations,
					'byBarangays' => $profitByBarangays
				]
			];
		}

		private function _calcProfit() {

		}

		private function _groupBarangay() {

		}

		private function _groupByStation() {

		}
	}