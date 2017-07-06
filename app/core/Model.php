<?php
	namespace app\core;
	
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
		
		protected $error;
		
		public function __construct(){
			$this->prefix = 'p_';		
			$this->table = end(explode('\\',get_class($this)));
			$host = 'localhost';
			$dbname = 'perezvonok';
			$user = 'root';
			$pass = '';
			
			$this->pdo = new \PDO('mysql:host=localhost;dbname=perezvonok', $user, $pass);
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
			$this->result = $this->pdo->prepare($this->sql);
			return $this;
		}
		
		public function Run($clear = false){
			echo $this->sql;
			$this->result->execute($this->operationalData);
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
			return $this->result->fetchAll(\PDO::FETCH_ASSOC);
		}
		
		public function GetNext(){
			return $this->result->fetch(\PDO::FETCH_ASSOC);
		}
		
		public function GetLast(){
			$x = (array)$this->result->fetchAll(\PDO::FETCH_ASSOC);
			return end($x);
		} 
		
		public function GetCount($id = null, $field = 'id'){
			$sql = 'SELECT COUNT(`'.$field.'`) AS `count` FROM `'.$this->prefix.$this->table.'`';
			
			if($id){
				$sql .= ' WHERE `id` = ?';
				$this->SetOperData(array($id));
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
			$this->result = $this->pdo->prepare($this->sql);
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
			$this->result = $this->pdo->prepare($this->sql);
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
			$this->result = $this->pdo->prepare($this->sql);
			return $this;
		}
		
		/* привязать оперативные данные для подстановки */
		public function SetOperData($data){
			$this->operationalData = $data;
			return $this;
		}
		
		public function GetLastId(){
			$this->pdo->lastInsertId();
		}
	}