<?php
	namespace app\helpers;
	use \app\core\ErrorInfo;
	
	class Validator{
		private $mode;
		private $field;
		
		private $errors;
				
		public function __construct(){
			
		}
		
		public static function CleanKey($data){
			$result = array();
			foreach($data as $key => $item){
				$x = explode(':',$key);
				$x = (isset($x[1])?$x[1]:$x[0]);
				$result[$x] = $item; 
			}
			return $result;
		}
		
		public function IsValid(){
			return count($this->errors) == 0;
		}
		
		public function ErrorReporting(){
			return $this->errors;
		}
		
		public function Clean(){
			$this->errors = array();
		}
		
		public function Validate($data){
			$this->Clean();
			foreach($data as $key => $item){		
				$this->SetMode($key);
				$this->Check($item);
			}
		}
		
		public function SetMode($key){
			$k = explode(':', $key);
			if(count($k) < 2){
				$this->mode = 'Check_DefaultText';
				$this->field = $k[0];
			}
			else{
				$this->mode = 'Check_'.$k[0];
				$this->field = $k[1];
			}
		}
		
		public function Prepare($data){		
			return htmlspecialchars(strip_tags(stripslashes(trim($data))));
		}
		
		public function Check($data){
			$this->{$this->mode}($this->Prepare($data));
		}
		
		// правила
		
		private function Check_Email($data){
            if(false === preg_match('/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u', $data)) {
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->field));
            }
		}
		
		private function Check_Phone($data){
			if(false === preg_match('/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/', $data)) {
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->field));
            }
		}
		
		private function Check_DefaultText($data){
			if(mb_strlen($data) < 2 || mb_strlen($data) >= 255){
				$this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_OUT_OF_RANGE_STR, array($this->field, 2, 255));
			}
		}
		
		private function Check_UNumberShort($data){
			$data = intval($data); 
			if(!$data){
				$this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->field));
			}
			elseif($data < 1 || $data > 255){
				$this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_OUT_OF_RANGE, array($this->field, 1, 255));
			}
		}
		
		private function Check_Password($data){
			if(false === preg_match('/^[A-Za-z0-9]*$/')){
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->field));
            }

            if(mb_strlen($data) < 7 || mb_strlen($data) > 16){
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_OUT_OF_RANGE, array($this->field, 7, 16));
            }
		}
	}