<?php
	namespace app\lib;
	
	class Error extends \app\core\Page{
					
		public function Index(){
			$this->view->Set('layout', __DIR__ . '\\..\\view\\Shared\\errorLayout.php');
			$this->Set('title','Произошла ошибка');
			$this->Set('content','Ошибка с кодом: ' . $this->Param(0));
		}

	}