<?php

/**
 * File for User Authentication Controller Class
 *
 * @category  User
 * @package   Auth_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController,
	User\Form\LoginForm, 
	User\Form\ForgotPasswordForm,
	User\Form\ResetPasswordForm,
	User\Model\Log, 
	Zend\Authentication\Adapter\DbTable as AuthAdapter, 
	Zend\Session\Container;

class AuthController extends AbstractActionController
{
	
	//public $userAuthentication = null;
	
	public function init() {
	
		//$this->userAuthentication = $this->getLocator()->get('User\Controller\Plugin\UserAuthentication');
	
	}
	
    /**
     * Index Action
     */
    public function indexAction()
    {
        //@todo - Implement indexAction
    }

    /**
     * Login Action
     *
     * @return array
     */
    public function loginAction()
    {
    	
    	if($this->userAuthentication()->hasIdentity())
    		$this->redirect()->toRoute('admin');
    	   
    	$this->layout('layout/login');
    	
        $form = new LoginForm();
        $request = $this->getRequest();
        
        $form->setData($request->getPost());
        
        if ($request->isPost() && $form->isValid()) {
        	
        	$login = $form->get('login')->getValue();
        	$password = $form->get('password')->getValue();
        	$connected = $this->params()->fromPost('connected', null);
        	
        	if(!$login||!$password) {
        		$this->flashMessenger()->setNamespace('login-form')->addMessage('Please enter your login and password.');
        	} else {
	        	$sm = $this->getServiceLocator();
	        	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	        	
	        	$this->userAuthentication()->setAuthAdapter(new AuthAdapter($dbAdapter, 'tbl_users',
	        			'login',
	        			'password',
	        			'SHA1(?) AND status = "1"'
	        	));        	
	        	        	
	            $authAdapter = $this->userAuthentication()->getAuthAdapter();
	
	            $authAdapter->setIdentity($login);
	            $authAdapter->setCredential($password);
	            
	            $result = $this->userAuthentication()->getAuthService()->authenticate($authAdapter);
	            
	            $serviceSystem = $this->getService('ServiceSystem');
	            
	            switch($result->getCode()) {
	            	case \Zend\Authentication\Result::FAILURE:
	            		$this->flashMessenger()->setNamespace('login-form')->addMessage('Authentication failure.');
	            		$serviceSystem->systemLog("Authentication failure. [login={$login}]");
	            		break;
	            	case \Zend\Authentication\Result::FAILURE_IDENTITY_NOT_FOUND:
	            		$this->flashMessenger()->setNamespace('login-form')->addMessage('User not found.');
	            		$serviceSystem->systemLog("User authentication not found. [login={$login}]");
	            		break;
	            	case \Zend\Authentication\Result::FAILURE_IDENTITY_AMBIGUOUS:
	            		$this->flashMessenger()->setNamespace('login-form')->addMessage('Ambiguous user.');
	            		$serviceSystem->systemLog("Ambiguous user authentication. [login={$login}]");
	            		break;            		
	            	case \Zend\Authentication\Result::FAILURE_CREDENTIAL_INVALID:
	            		$this->flashMessenger()->setNamespace('login-form')->addMessage('Invalid password.');
	            		$serviceSystem->systemLog("Invalid authentication password. [login={$login}]");
	            		break;            		
	           		case \Zend\Authentication\Result::FAILURE_UNCATEGORIZED:
	           			$this->flashMessenger()->setNamespace('login-form')->addMessage('Unknown failure.');
	           			$serviceSystem->systemLog("Unknown authentication failure. [login={$login}]");
	           			break;           			
	      			case \Zend\Authentication\Result::SUCCESS:
	      				
	      				$session = new Container('base');
	      				
	      				$service = $this->getService('ServiceUser');
	      				
	      				//$user = $service->getUserByLogin($login);
	      				//$user = $user->toArray()[0]; /* For PHP 5.4 */
	      				//$userRowset = $user->toArray();
	      				//$user = $userRowset[0];
	      				$user = $service->getUserByLogin($login);
	      				
	      				$log  = new Log();
	      				$log->id_user = $user['id'];
	      				$log->ip	  = $_SERVER['REMOTE_ADDR'];
	      				
	      				$service->saveLog($log);
	      				
	      				$settingLogo = $this->getService('ServiceSystem')->getSettingByName('system_theme_logo_admin');
	      				$logo = end(explode('/', $settingLogo->value));

	      				$settingTitle = $this->getService('ServiceSystem')->getSettingByName('system_theme_title');
	      				 
	      				$session->offsetSet('logo', $logo);
	      				$session->offsetSet('user', $user);
	      				$session->offsetSet('title', $settingTitle->value);
	      				
	      				if($connected) {
	      					$cookie = new  \Zend\Http\Header\SetCookie('uid',md5($user['login'] . $user['password']), time() + 365 * 60 * 60 * 24,'/');
	      					$this->getResponse()->getHeaders()->addHeader($cookie);
	      				}
	      				
	      				$serviceSystem->systemLog("User authenticated. [login={$login}@{$_SERVER[REMOTE_ADDR]}]");
	      				//$form->get('connected')->setChecked(true);
	      				 
	      				return $this->redirect()->toRoute('admin');
	            }
        	}
        	
        }
        
        $setting = $this->getService('ServiceSystem')->getSettingByName('system_theme_logo_login');
        
        return array('logoImage' => end(explode('/', $setting->value)), 'loginForm' => $form, 'flashMessages' => $this->flashMessenger()->getCurrentMessages());
    }

    /**
     * Logout Action
     */
    public function logoutAction()
    {
    	$this->userAuthentication()->getAuthService()->clearIdentity();
    	$this->flashMessenger()->setNamespace('login-form')->addMessage('Logout successfull.');
    	$session = new Container('base');
    	$user = $session->offsetGet('user');
    	$this->getService('ServiceSystem')->systemLog("Logout successfull. [login={$user['login']}@{$_SERVER[REMOTE_ADDR]}]");
    	return $this->redirect()->toRoute('home');
    	
    }
    
    /**
     * Forgot password Action
     */
    public function forgotAction()
    {
    	
    	$form = new ForgotPasswordForm();
    	$request = $this->getRequest();
    	
    	$form->setData($request->getPost());
    	
    	$this->layout('layout/login');
    	    	
    	if ($request->isPost() && $form->isValid()) {
    		
    		$login = $form->get('login')->getValue();
    		$email = $form->get('email')->getValue();
    		
    		$service = $this->getService('ServiceUser');
    		
    		if(!$service->sendRecoveryPassword($login, $email)) {
    			$this->flashMessenger()->setNamespace('login-form')->addMessage('Invalid Login or E-Mail.');
    			return $this->redirect()->toRoute('forgot');
    		} else {
    			$this->flashMessenger()->setNamespace('login-form')->addMessage('Please check your e-mail inbox.');
    		}
    		
    	}
    	
    	$setting = $this->getService('ServiceSystem')->getSettingByName('system_theme_logo_login');
    	
    	return array('forgotForm' => $form, 'logoImage' => end(explode('/', $setting->value)));
    	 
    }
    
    /**
     * Reset password Action
     */
    public function resetAction()
    {
    	 
    	$form = new ResetPasswordForm();
    	$request = $this->getRequest();
    	 
    	$form->setData($request->getPost());
    	 
    	$this->layout('layout/login');
    
    	if ($request->isPost() && $form->isValid()) {
    
    		$uid = $form->get('uid')->getValue();
    		$password = $form->get('password')->getValue();
    		$passwordConfirm = $form->get('password_confirm')->getValue();
    
    		$service = $this->getService('ServiceUser');
    
    		if(!$service->resetPassword($uid, $password, $passwordConfirm)) {
    			$this->flashMessenger()->setNamespace('login-form')->addMessage('Invalid login or password.');
    			return $this->redirect()->toRoute('forgot');
    		} else {
    			return $this->redirect()->toRoute('login');
    		}
    
    	} else {
    		$uid = $this->params('uid');
    		if(!$uid) return $this->redirect()->toRoute('login');
    		$form->get('uid')->setValue($uid);
    	}
    	 
    	$setting = $this->getService('ServiceSystem')->getSettingByName('system_theme_logo_login');
    	 
    	return array('resetForm' => $form, 'logoImage' => end(explode('/', $setting->value)));
    
    }
    
    public function getService($service)
    {
    	return $this->getServiceLocator()->get($service);
    }
    
}
