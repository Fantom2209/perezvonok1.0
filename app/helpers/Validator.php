<?php
	namespace app\helpers;
	
	class Validator{
		private $mode;
		private $errors;
				
		public function __construct(){
			
		}
		
		public function IsValid(){
			return true;
		}
		
		public function ErrorReporting(){
			
		}
		
		public function SetMode(){
			
		}
		
		public function Check($data){
			$this->Check{$this->mode}($data);
		}
		
		private function Chech_Email(){
			
		}
		
		private function Chech_Phone(){
			
		}
		
		private function Chech_DefaultText(){
			
		}
		
		private function Check_Password(){
			
		}
	}