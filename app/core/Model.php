<?php
	namespace app\core;
	use \app\helpers\Loger;


	class Model{
		protected $pdo;
		protected $table;
		protected $prefix;
		
		protected $operationalData;
		protected $where;
		protected $orderby;
		protected $limit;
		protected $bindings;
		
		protected $result;
		protected $sql;
		
		protected $errors;
		
		public function __construct(){
			$this->prefix = Config::DB_PREFIX;
			$this->table = end(explode(Config::PATH_SEPARATOR,get_class($this)));
			try {
                $this->pdo = new \PDO('mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME, Config::DB_USER, Config::DB_PASSWORD);
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }
            catch(\PDOException $e){
                Loger::Write($e->getMessage(), $e->getCode());
                $this->AddError(ErrorInfo::DB_CONNECT);
            }
		}
				
		public function __destruct(){
			$this->pdo = null;
		}
		
		
		/* выборка */
		
		public function Select($fields = array()){
			$sql = '';
			foreach($fields as $item){
				if(!empty($sql)){
					$sql .= ', ';
 				}
				if(is_array($item)){
					$sql .= '`'.$item['table'].'`.`'.$item['field'].'`' . (!empty($item['label'])? ' AS `'.$item['label'].'`' : '');	
				}
				else{
					$sql .= '`'.$item.'`';
				}
			}
			if(empty($sql)){
				$sql = '*';
			}
			$this->sql = 'SELECT ' . $sql . ' FROM ' . $this->prefix . $this->table;
			return $this;
		}
		
		public function Binding($type, $table, $fieldMain, $fieldDop){
			if(!empty($table) && !empty($fieldMain) && !empty($fieldDop)){
				$this->bindings[] = $type.' JOIN `'.$this->prefix .$table.'` ON `'.$this->prefix.$this->table.'`.`'.$fieldMain.'` = `'.$this->prefix.$table.'`.`'.$fieldDop.'`';
			}
			
			return $this;
		}
		
		public function Where($pattern, $fields = array()){
			if(!empty($pattern)){
				$this->where = ' WHERE ' . $pattern;
				$this->operationalData = $fields;
			}
			return $this;
		}
		
		public function OrderBy($fields, $code = 1){
			
			if(count($fields) == 0){
				return;
			}
			
			foreach($fields as $item){
				if(!empty($this->orderby)){
					$this->orderby .= ', ';
				}
				$this->orderby .= $item;
			}
						
			$this->orderby = ' ORDER BY ' . $this->orderby . ' ';
			
			switch($code){
				case 1:
					$this->orderby .= 'ASC';
					break;
				case 2:
					$this->orderby .= 'DESC';
					break;
			}
			
			return $this;
		}
		
		public function Limit($start = null, $count = null){
			if($start !== null){
				$this->limit = ' LIMIT ' . $start;
				if($count){
					$this->limit .= ', ' . $count;
				}
			}
				
			return $this;
		}
		
		// собрать запрос на выборку если она состоит из частей
		public function Build(){
			$bindings = '';
			if($this->bindings){
				foreach($this->bindings as $item){
					$bindings .= ' ' . $item;
				}
			}
						
			$this->sql .= $bindings . $this->where . $this->orderby . $this->limit;
			$this->PrepareQuery();
			return $this;
		}
		
		public function Run($clear = false){

		    try{
                if($this->result){
                    $this->result->execute($this->operationalData);
                }
            }
		    catch(\PDOException $e){
                Loger::Write($e->getMessage(), $e->getCode());
                $this->AddError(ErrorInfo::DB_QUERY);
            }

			if($clear){
				$this->Clear();
			}
			
			return $this;
		}
		
		public function Clear(){
			$this->operationalData = array();
			$this->where = '';
			$this->orderby = '';
			$this->limit = '';
			$this->bindings = array();
			$this->result = null;
			$this->sql = '';
		}
		
		public function GetAll(){
			return $this->result ? $this->result->fetchAll(\PDO::FETCH_ASSOC) : null;
		}
		
		public function GetNext(){
		    return $this->result ? $this->result->fetch(\PDO::FETCH_ASSOC) : null;
		}
		
		public function GetLast(){
			$x = null;
		    if($this->result) {
                $x = (array)$this->result->fetchAll(\PDO::FETCH_ASSOC);
                $x = end($x);
            }
			return $x;
		} 
		
		public function GetCount($val = null, $field = 'id'){
			$sql = 'SELECT COUNT(`'.$field.'`) AS `count` FROM `'.$this->prefix.$this->table.'`';
			
			if($val){
				$sql .= ' WHERE `'.$field.'` = ?';
				$this->SetOperData(array($val));
			}
			
			return $this->Query($sql)->Run()->GetAll()[0]['count'];
		}
		
		/* добавить / обновить / удалить */
				
		public function Insert($data = array()){
			if(count($data) == 0){
				return null;
			}
			$column = '';
			$pattern = '';
			$values = array();
			foreach($data as $key => $val){
				if(!empty($column)){
					$column .= ', ';
					$pattern .= ', ';
				}
				$column .= '`'.$key.'`';
				$pattern .= '?';
				$values[] = $val;
			}
			
			$this->sql = 'INSERT INTO `'.$this->prefix.$this->table.'` ('.$column.') VALUES ('.$pattern.')';
            $this->PrepareQuery();
			$this->SetOperData($values);

			return $this;
		}
		
		public function Update($data = array(), $wherePattern = '', $whereData = array()){
			if(count($data) == 0){
				return null;
			}
			$sql = '';
			$values = array();
			foreach($data as $key => $val){
				if(!empty($sql)){
					$sql .= ', ';
				}
				$sql .= '`'.$key.'` = ?';
				$values[] = $val;
			}
			
			$this->sql = 'UPDATE `'.$this->prefix.$this->table.'` SET ' . $sql . (!empty($wherePattern)? ' WHERE ' . $wherePattern : '');
            $this->PrepareQuery();
			$this->SetOperData(array_merge($values, $whereData));
			return $this;
		}
		
		public function Delete(){
			$this->sql = 'DELETE FROM `'.$this->prefix.$this->table.'`';
			return $this;
		}
		
		/* прямой запрос */
		public function Query($sql){
			$this->sql = $sql;
            $this->PrepareQuery();
			return $this;
		}
		
		/* привязать оперативные данные для подстановки */
		public function SetOperData($data){
			$this->operationalData = $data;
			return $this;
		}
		
		public function GetLastId(){
			try {
                $this->pdo->lastInsertId();
            }
            catch(\PDOException $e){
                Loger::Write($e->getMessage(), $e->getCode());
                if($this->IsSuccess()) {
                    $this->AddError(ErrorInfo::DB_QUERY);
                }
            }
		}

		public function IsSuccess(){
		    return count($this->errors) == 0;
        }

        public function ErrorReporting(){
		    $e = $this->errors;
		    $this->errors = array();
		    return $e;
        }

        public function AddError($code){
            $this->errors[] = $code;
        }

        private function PrepareQuery(){
            try{
                if($this->pdo){
                    $this->result = $this->pdo->prepare($this->sql);
                }
                else{
                    $this->result = null;
                }
            }
            catch(\PDOException $e){
                Loger::Write($e->getMessage(), $e->getCode());
                if($this->IsSuccess()){
                    $this->AddError(ErrorInfo::DB_PREPARE_QUERY);
                }
                $this->result = null;
            }
        }
	}