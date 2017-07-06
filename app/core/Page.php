<?php
	namespace app\core;
	
	class Page{
		
		protected $view;
		protected $data;
		protected $params;
		
		public function __construct(){
			$this->view = new View();
		}
		
		public function Get($name){
			return $this->data[$name];
		}
		
		public function Set($name, $value){
			return $this->data[$name] = $value;
		}
		
		public function SetParams($params){
			$this->params = $params;
		}
		
		public function Param($index){
			return $this->params[$index];
		}
		
		public function GenerateError($code){
			$this->Redirect('Error', 'Index', array($code));
		}
		
		public function NotFound(){
			$this->Redirect('Error', 'Index', array(404));
		}
		
		public function Redirect($controller = 'Home', $action = 'Index', $param = array()){
			$url = '/' . $controller . '/' . $action . '/';
			foreach($param as $val){
				if(!empty($val)){
					$url .= $val . '/';
				}
			}
			$this->RedirectUrl($url);
		}
		
		public function RedirectUrl($url = '/Home/Index/'){
			header('Location: ' . $url); 
			exit(); 
		}
		
		public function Show($controller, $action){
			
			if(!$this->view->HasLayout()){
				$this->view->Set('layout', __DIR__ . '\\..\\view\\Shared\\mainLayout.php');
			}		
			
			if(!$this->view->HasTeamplate()){
				$this->view->Set('teamplate', __DIR__ . '\\..\\view\\' . $controller . '\\'. $action .'.php');
				
			}
			
			require_once($this->view->Get('layout'));
		}
		
	}