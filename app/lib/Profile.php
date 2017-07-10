<?php
    namespace app\lib;
    use \app\data\User;

    class Profile extends \app\core\Page{

        public function Index(){
            //var_dump(User::IsAuthorized());
            $this->Set('content','Главная профиля');
            $this->Set('UserId',User::ActiveUserInfo('UserId'));
        }

    }