<?php
	
	use Core\Token;
	load(['Token'] , CORE);
	
 	abstract class Model extends ModelCore
	 {

		private $_error = [];
		private static $instance = null;

		const MESSAGE_UPDATE_SUCCESS = "UPDATED SUCCESFULLY";
		const MESSAGE_CREATE_SUCCESS = "CREATED SUCCESFULLY";
		const MESSAGE_DELETE_SUCCESS = "DELETED SUCCESFULLY";

		const MESSAGE_DELETE_ERROR = "DELETED FAILED";
		const MESSAGE_CREATE_ERROR = "CREATE FAILED";
		const MESSAGE_UPDATE_ERROR = "UPDATE FAILED";
		
		
		public function getFillablesOnly($datas)
		{
			$return = [];

			foreach($datas as $key => $row) {
				if( isEqual($key, $this->_fillables) )
					$return[$key] = $row;
			}
			return $return;
		}

 		public function __construct()
 		{
 			$this->db = new Database(DBVENDOR , DBHOST , DBNAME , DBUSER , DBPASS);
 			$this->dbHelper = new DatabaseHelper( Database::getInstance() );
 			$this->prefix  = DB_PREFIX;
 			$this->token = new Token();
		 }

		public function store($values)
		{
			$data = [
				$this->table,
				$values
			];

			return $this->saveId($this->dbHelper->insert(...$data));
		}

		public function update($values , $id)
		{
			if (is_array($id)) {
				$where = $this->conditionConvert($id);
			} else {
				$where = "id = '{$id}'";
			}
			$data = [
				$this->table,
				$values,
				$where
			];
			
			return $this->dbHelper->update(...$data);
		}

		public function delete($id)
		{
			$data = [
				$this->table,
				"id = '{$id}'"
			];

			return $this->dbHelper->delete(...$data);
		}
		
		public function deleteByKey($keyValuePair = [])
		{
			if( empty($keyValuePair) )
				return false;

			$WHERE = null;

			$counter = 0;

			foreach($keyValuePair as $key => $value)
			{
				if( $counter > 0)
					$WHERE .= " AND ";

				$WHERE .= "{$key} = '{$value}'";

				$counter++;
			}

			$data = [
				$this->table,
				$WHERE
			];

			return $this->dbHelper->delete(...$data);
		}

		public function get($id)
		{
			$data = [
				$this->table ,
				'*',
				"id = '{$id}'"
			];

			return $this->dbHelper->single(...$data);
		}

		public function all($where = null , $order_by = null , $limit = null)
		{

			if(!is_null($where)){
				if(is_array($where)) {
					$where = $this->conditionConvert($where);
				}
			}

			$data = [
				$this->table ,
				'*',
				$where,
				$order_by,
				$limit
			];
			return $this->dbHelper->resultSet(...$data);
		}


		public function single($where = null, $fields = '*' , $orderBy = null)
		{	
			$whereString = null;

			if(!is_null($where))
				$whereString = $this->conditionConvert($where);

			$data = [
				$this->table ,
				$fields, 
				$whereString,
				$orderBy
			];
			
			return $this->dbHelper->single(...$data);
		}

		public function getAssoc($field , $where = null)
	    {
			if(is_array($where))
			$where = $this->conditionConvert($where);

			$data = [
				$this->table,
				'*',
				$where,
				"$field ASC"
			];

	      return $this->dbHelper->resultSet(...$data);
	    }

	    public function getDesc($field , $where = null)
	    {
		  if(is_array($where))
			$where = $this->conditionConvert($where);

	      $data = [
	        $this->table,
	        '*',
	        $where,
	        "$field DESC"
	      ];

	      return $this->dbHelper->resultSet(...$data);
	    }

		public function first()
		{
			$data = [
				$this->table ,
				'*',
				null,
				'id asc',
				'1'
			];
			
			return $this->dbHelper->single(...$data);
		}

		public function last()
		{
			$data = [
				$this->table ,
				'*',
				null,
				'id desc',
				'1'
			];
			
			return $this->dbHelper->single(...$data);
		}
		
		public function lastId() {
			return $this->last()->id ?? 0;
		}

    final public function dbData($data)
    {
      $this->data = $data;
    }

    final public function getdbData($property = null)
    {
      if(is_null($property))
        return $this->data;

      return $this->data->$property;
    }


	public function filter($filters)
	{
		$filterCondition = '';

		$counter = 0;

		$fields = array_keys($filters);
		foreach($fields as $key => $val)
		{
			if($counter < $key) {

				$filterCondition .= " AND ";
				$counter++;
			}

			$filterCondition .= " {$val} like '%{$filters[$val]}%'";
		}

		return $filterCondition;
	}

	final public function add_model($varname , $instance)
	{
		$this->$varname = $instance;
	}


	final protected function saveId($id)
	{
		$this->database['id'] = $id;
		return $id;
	}

	final public function getId()
	{
		if(isset($this->database['id']))
			return $this->database['id'];
		return die("Saved Id Not Found");
	}


	final public function conditionEqual($params)
	{
		$WHERE = '';

		if( is_array($params) )
		{
			$counter = 0;
			$increment = 0;

			foreach($params as $key => $row) 
			{
				if($counter < $increment){
					$WHERE .= ' AND ';
					$counter++;
				}

				$WHERE .= " $key = '{$row}'";

				$increment++;
			}

		}else
		{
			$WHERE = $params;
		}

		return $WHERE;
	}

	public function conditionConvert($params , $defaultCondition = '=')
	{
		$WHERE = '';
		$counter = 0;

		$errors = [];


		if( !is_array($params) )
			return $params;
		/*
		*convert-where default concatinator is and
		*add concat on param values to use it
		*/
		$condition_operation_concatinator = 'AND';

		foreach($params as $key => $param_value) 
		{	
			if( $counter > 0)
				$WHERE .= " {$condition_operation_concatinator} "; //add space

			/*should have a condition*/
			if( is_array($param_value) && isset($param_value['condition']) ) 
			{
				$has_condition = false;

				$condition_operation_concatinator = $param_value['concatinator'] ?? $condition_operation_concatinator;

				//check for what condition operation
				$condition = $param_value['condition'];
				$condition_values = $param_value['value'] ?? '';
				$isField = isset($param_value['is_field']) ? true : false;

				if( isEqual($condition , 'not null'))
				{
					$WHERE .= "{$key} IS NOT NULL ";
					$has_condition = true;
				}

				if( isEqual($condition , ['between' , 'not between']))
				{
					if( !is_array($condition_values) )
						return _error(["Invalid query" , $params]);
					if( count($condition_values) < 2 )
						return _error("Incorrect between condition");

					$condition = strtoupper($condition);

					list($valueA, $valueB) = $condition_values;
					if($isField) {
						$WHERE .= " {$key} {$condition} {$valueA} AND {$valueB}";
					}else{
						$WHERE .= " {$key} {$condition} '{$valueA}' AND '{$valueB}'";
					}

					$has_condition = true;
				}

				if( isEqual($condition , ['equal' , 'not equal' , 'in' , 'not in']) )
				{
					$conditionKeySign = '=';

					if( isEqual($condition , 'not equal') )
						$conditionKeySign = '!=';

					if( isEqual( $condition , 'in'))
						$conditionKeySign = ' IN ';

					if( isEqual( $condition , 'not in'))
						$conditionKeySign = ' NOT IN ';

					if( is_array($condition_values) ){
						if($isField) {
							$WHERE .= "{$key} $conditionKeySign (".implode(",",$condition_values).") ";
						}else{
							$WHERE .= "{$key} $conditionKeySign ('".implode("','",$condition_values)."') ";
						}
					}else{
						$WHERE .= "{$key} {$conditionKeySign} '{$condition_values}' ";
					}

					$has_condition = true;
				}

				/*
				*if using like
				*add '%' on value 
				*/
				if(isEqual($condition , ['>' , '>=' , '<' , '<=' , '=', 'like']) )
				{
					if($isField){
						$WHERE .= "{$key} {$condition} {$condition_values}";
					}else{
						$WHERE .= "{$key} {$condition} '{$condition_values}'";
					}
				}
				$counter++;

				continue;
			}

			if( isEqual($defaultCondition , 'like')) 
				$WHERE .= " $key {$defaultCondition} '%{$param_value}%'";

			if( isEqual($defaultCondition , '=')) 
			{
				$isNotCondition = substr( $param_value , 0 ,1); //get exlamation
				$isNotCondition = stripos($isNotCondition , '!');

				if( $isNotCondition === FALSE )
				{
					$WHERE .= " $key = '{$param_value}'";
				}else{
					
					$cleanRow = substr($param_value , 1);
					$WHERE .= " $key != '{$cleanRow}'";
				}
			}

			$counter++;
		}

		return $WHERE;
	}

	public function referenceSeries($prefix = null, $suffix = null) {
		return $this->token->createSeries($this->lastId(), 5, $prefix = null, $suffix = null);
	}

	public function createOrUpdate($platformData, $id = null) {
		$_fillables = $this->getFillablesOnly($platformData);
		if(!is_null($id)) {
			$res = $this->update($_fillables, $id);
			$res ? $this->addMessage(self::MESSAGE_UPDATE_SUCCESS):$this->addMessage(self::MESSAGE_UPDATE_ERROR);
			//update
		} else{
			if(isset($platformData['_token'])) {
				$_fillables[$platformData['_token']] = $this->referenceSeries();
			}
			$res = $this->store($_fillables);
			$res ? $this->addMessage(self::MESSAGE_CREATE_SUCCESS):$this->addMessage(self::MESSAGE_CREATE_ERROR);
			return $res;
		}
	}

 }