<?php
	namespace app\core;
	
	class ErrorInfo{
			
		const UNDEFINED_ERROR = 0;
		
		const FIELD_EMPTY = 1;
		const FIELD_NOT_CORRECT = 2;
		const FIELD_OUT_OF_RANGE = 3;
		const FIELD_OUT_OF_RANGE_STR = 4;
		
		
		public static function GetMessage($code, $data = array()){
			if(!isset(self::$messages[$code])){
				$code = 0;
			}
			
			$result = self::$messages[$code];
			if(count($data) > 0){
				$patterns = array();
				foreach($data as $key => $item){
					$patterns[] = '{'.$key.'}';
				}
				$result = str_replace($patterns, $data, $result);
			}
			
			return $result;
		}
		
		public function GetMetaErrorItem($code, $data = array(), $field = ''){
			return array('code' => $code, 'context' => $field, 'msg' => ErrorInfo::GetMessage($code, $data));
		}
		
		private static $messages = array(
			SELF::UNDEFINED_ERROR => 'Произошла неопределенная ошибка!',
			SELF::FIELD_EMPTY => 'Поле "{0}" не может быть пустым!',
			SELF::FIELD_NOT_CORRECT => 'Поле "{0}" имеет некорректное значение!',
			SELF::FIELD_OUT_OF_RANGE_STR => 'Некорректное кол-во символов в поле "{0}"! Минимум: {1} - Максимум: {2}!',
			SELF::FIELD_OUT_OF_RANGE => 'Выход за границы диапазона в поле "{0}"! Минимум: {1} - Максимум: {2}!',
		);
		
	}