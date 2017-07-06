<?php
	namespace app\core;
	
	class ErrorInfo{
			
		const UNDEFINED_ERROR = 0;
		
		const FIELD_EMPTY = 1;
		const FIELD_NOT_CORRECT = 2;
		const FIELD_OUT_OF_RANGE = 3;
		
		
		public static function GetMessage($code){
			if(!isset($message[$code])){
				$code = 0;
			}
			
			return $message[$code];
		}
		
		$mssages = array(
			SELF::UNDEFINED_ERROR => 'Произошла неопределенная ошибка!',
			SELF::FIELD_EMPTY => 'Поле {field} не может быть пустым!',
			SELF::FIELD_NOT_CORRECT => 'Поле {field} имеет некорректное значение!',
			SELF::FIELD_OUT_OF_RANGE_STR => 'Некорректное кол-во символов в поле {field}! Минимум: {min} - Максимум: {max}!',
		);
		
	}