<?php
	namespace app\helpers;
	
	class Ajax{
		
		private $code;
		private $status;
		private $format;
		private $data;
		private $body;
		
		public function __construct(){
			$this->format = 'GetJSON';
			$this->code = 200;
			$this->status = 'success';
		}
		
		public function SetFormat($format){
			$this->format = 'Get'.$format;
		}
		
		public function SetData($data, $status){
			$this->data['content'] = $data;
			$this->status = $status;
			switch($status){
				case 'success':
					$this->code = 200;
					break;
				case 'error':
					$this->code = 500;
					break;
			}
		}
						
		public function GetResponse(){
			$this->data['code'] = $this->code;
			$this->data['status'] = $this->status;
			$this->{$this->format}();
			if($this->body){
				echo $this->body;
			}
			else{
				echo '{"code": 500, status:"error"}';
			}
			exit;
		}
		
		public function GetJSON(){
			$result = json_encode($this->data);
			if(json_last_error() == JSON_ERROR_NONE){
				$this->body = $result;
			}
			else{
				$this->body = null;
			}
		}
		
	}