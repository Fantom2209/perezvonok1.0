<?php
	namespace app\lib;
	
	use \app\helpers\Validator;
	use \app\core\ErrorInfo;
	use \app\data\User;
	use \app\helpers\Ajax;
	
	class Account extends \app\core\Page{
		
		public function CreatePost(){
			$v = new Validator();
			$data = $this->Param('UserData');
			$v->Validate($data);
			$response = new Ajax();
			if(!$v->IsValid()){
			    $response->SetError($v->ErrorReporting());
			}
			else{
                $data = Validator::CleanKey($data);
                $user = new User();
			    if($data['password'] !== $data['confirmPass']){
                    $response->SetError(ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_CONFIRM_PASSWORD_NOT_CORRECT,array('confirmPass')));
                }
                elseif(!$user->LoginFree($data['login'])){
                    $response->SetError(ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_LOGIN_NOT_FREE,array('login')));
                }
                elseif(!$user->EmailFree($data['email'])){
                    $response->SetError(ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_EMAIL_NOT_FREE,array('email')));
                }
                else {
                    $data['idRole'] = 1;
                    $data['password'] = $user->HashPassword($data['password']);
                    unset($data['confirmPass']);
                    $user->Insert($data)->Run();
                    $response->SetRedirect(\app\helpers\Html::ActionPath('Profile','Index'));
                }
			}
			$response->GetResponse();
		}
		
		public function LoginPost(){
			
		}
		
		public function LogoutPost(){
			
		}
		
	}