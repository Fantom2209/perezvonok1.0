<?php
	namespace app\data;
	use \app\core\Config;
	class User extends \app\core\Model{


	    public function LoginFree($login){
	        return 0 == $this->GetCount($login, 'login');
        }

        public function EmailFree($email){
            return 0 == $this->GetCount($email, 'email');
        }

	    public function HashPassword($pass){
	        return MD5(MD5($pass . Config::APP_SECRET));
        }
	}