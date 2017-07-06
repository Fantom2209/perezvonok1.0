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
			SELF::UNDEFINED_ERROR => '��������� �������������� ������!',
			SELF::FIELD_EMPTY => '���� {field} �� ����� ���� ������!',
			SELF::FIELD_NOT_CORRECT => '���� {field} ����� ������������ ��������!',
			SELF::FIELD_OUT_OF_RANGE_STR => '������������ ���-�� �������� � ���� {field}! �������: {min} - ��������: {max}!',
		);
		
	}