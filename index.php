<?php
	spl_autoload_extensions('.php');
	spl_autoload_register();
	
	
	
	/*$x = array('DefaultText:name' => '', 'UNumberShort:age' => 266, 'email' => '');
	use \app\helpers\Validator;
	
	$v = new Validator();
	
	$v->Validate($x);
	if(!$v->IsValid()){
		var_dump($v->ErrorReporting());
	}
	else{
		echo 'Success';
	}*/
	
	
	app\core\Application::run();
	