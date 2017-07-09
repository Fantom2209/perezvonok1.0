<?php
	namespace app\core;
	
	class ErrorInfo{
			
		const UNDEFINED_ERROR = 0;
		
		const FIELD_EMPTY = 1;
		const FIELD_NOT_CORRECT = 2;
		const FIELD_OUT_OF_RANGE = 3;
		const FIELD_OUT_OF_RANGE_STR = 4;
		const FIELD_EASY_PASSWORD = 5;
		CONST FIELD_CONFIRM_PASSWORD_NOT_CORRECT = 6;
		CONST FIELD_LOGIN_NOT_FREE = 7;
		CONST FIELD_EMAIL_NOT_FREE = 8;

		
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


		// контекст (названия поля и т.д.) всегда передаеться под нулевым индексом
		public static function GetMetaErrorItem($code, $data = array()){
			return array('code' => $code, 'context' => isset($data[0])?$data[0]:'', 'msg' => self::GetMessage($code, $data));
		}
		
		private static $messages = array(
			self::UNDEFINED_ERROR => 'Произошла неопределенная ошибка!',
			self::FIELD_EMPTY => 'Поле "{0}" не может быть пустым!',
			self::FIELD_NOT_CORRECT => 'Поле "{0}" имеет некорректное значение!',
			self::FIELD_OUT_OF_RANGE_STR => 'Некорректное кол-во символов в поле "{0}"! Минимум: {1} - Максимум: {2}!',
			self::FIELD_OUT_OF_RANGE => 'Выход за границы диапазона в поле "{0}"! Минимум: {1} - Максимум: {2}!',
            self::FIELD_EASY_PASSWORD => 'Слишком простой пароль, используйте латинские буквы + цифры!',
            self::FIELD_CONFIRM_PASSWORD_NOT_CORRECT => 'Пароли не совпадают!',
            self::FIELD_LOGIN_NOT_FREE => 'Аккаунт с таким логином уже существует!',
            self::FIELD_EMAIL_NOT_FREE => 'Аккаунт с таким email`ом уже существует!',
		);
		
	}