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

		public function setCustomers($customers) {
			$this->_customers = $customers;
			return $this;
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


			//custoemrs
			$customerByStations = [];
			$customerByBarangays = [];

			foreach($this->_customers as $key => $row) {
				//platform
				if(!isset($customerByStations[$row->platform_id])) {
					$customerByStations[$row->platform_id] = [
						'name' => $row->platform_name,
						'platform_reference' => $row->platform_reference,
						'customers' => []
					];
					$customerByStations[$row->platform_id]['customers'][] = $row;
				} else {
					$customerByStations[$row->platform_id]['customers'][] = $row;
				}

				//barangays
				if(!isset($customerByBarangays[$row->cx_street])) {
					$customerByBarangays[$row->cx_street]['platform_name'] = $row->platform_name;
					$customerByBarangays[$row->cx_street]['street_name'] = $row->cx_street;
					$customerByBarangays[$row->cx_street]['platform_reference'] = $row->platform_reference;
					$customerByBarangays[$row->cx_street]['customers'] = [];
					$customerByBarangays[$row->cx_street]['customers'][] = $row;
				} else {
					$customerByBarangays[$row->cx_street]['customers'][] = $row;
				}
			}
			
			return [
				'profitReport' => [
					'byStations' => $profitByStations,
					'byBarangays' => $profitByBarangays
				],
				'customerReport' => [
					'byStations' => $customerByStations,
					'byBarangays' => $customerByBarangays
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