<?php
	namespace app\core;
	
	class Config{
		
		static private $path = '../tmp/';
		static private $fileName = 'options.json'; 
		static private $options;
				
		static public function Init(){
			self::Load();
			if(!self::hasConfig()){
				self::AddBaseValue();
			}
		} 
		
		static public function AddBaseValue(){	
			self::Set('viewDir', '/view');
			self::Set('layoutDir', '/view/Shared');
		}
		
		static public function Clear(){
			self::$options = array();
			self::AddBaseValue();
			self::Save();
		} 
			
		static public function Save(){
			var_dump(self::$options);
			if(!is_dir(self::$path)){
				mkdir(self::$path, 0777, true);
			}
			file_put_contents(self::$path . self::$fileName, json_encode(self::$options));
		}
		
		static public function Load(){
			if(!file_exists(self::$path . self::$fileName)){
				self::$options = array();
			}
			else{
				self::$options = json_decode(file_get_contents(self::$path . self::$fileName));
			}
		}
		
		static public function Get($name){
			return self::$options[$name];
		}
		
		static public function Set($name, $value){
			self::$options[$name] = $value;
		}
		
		static public function hasConfig(){
			return count(self::$options) > 0;
		}
		
	}