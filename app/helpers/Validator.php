<?php
	namespace app\helpers;
	use \app\core\ErrorInfo;
	
	class Validator{
		private $mode;
		private $field;
		
		private $errors;
				
		public function __construct(){
			
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
		
		private function Check_Email(){
			
		}
		
		private function Check_Phone(){
			
		}
		
		private function Check_DefaultText($data){
			if(empty($data)){
				$this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_EMPTY, array($this->field), $this->field); 
			}
			
			if(mb_strlen($data) < 2 || mb_strlen($data) >= 255){
				$this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_OUT_OF_RANGE_STR, array($this->field, 2, 255), $this->field); 
			}
		}
		
		private function Check_UNumberShort($data){
			$data = intval($data); 
			if(!$data){
				$this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->field), $this->field); 
			}
			elseif($data < 1 || $data > 255){
				$this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_OUT_OF_RANGE, array($this->field, 1, 255), $this->field); 
			}
		}
		
		private function Check_Password(){
			
		}
	}