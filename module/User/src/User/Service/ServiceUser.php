<?php
/**
 * User Service Layer
 *
 * @category  Service
 * @package   Service_User
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace User\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
//use System\Model\SystemLog;
use Zend\Mail\Message;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\Smtp;

class ServiceUser implements ServiceLocatorAwareInterface
{
	
	protected $serviceManager;
	protected $serviceLocator;
	
	public function __construct($serviceManager) {
		$this->serviceManager = $serviceManager;
	}
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}

	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}
	
	public function saveUser($user)
	{
		return $this->serviceManager->get('User\Model\UserTable')->saveUser($user);
	}
	
	public function getUser($id)
	{
		$id = (int) $id;
		
		if($id<1)
			return false;
		
		$user = $this->serviceManager->get('User\Model\UserTable')->getUser($id);
		
		return $user;
		
	}
	
	public function getUserByLogin($login)
	{
		return $this->serviceManager->get('User\Model\UserTable')->getUserByLogin($login);
	}
	
	public function deleteUser($id)
	{
		$id = (int) $id;
	
		if($id<1)
			return false;
	
		return $this->serviceManager->get('User\Model\UserTable')->deleteUser($id);
	
	}
	
	public function deleteUsers($ids)
	{
		if (strpos($ids, ',')>0) {
			$idArray = explode(',', $ids);
		
			foreach ($idArray as $id) {
				if (!$this->deleteUser($id))
					return false;
			}
			return true;
		} else {
			return $this->deleteUser($ids);
		}
	
	}
	
	/*public function getUsers()
	{
		return $this->serviceManager->get('User\Model\UserTable')->fetchAll();
	}*/
	
	public function getUsers($page)
	{
		$paginator = $this->serviceManager->get('User\Model\UserTable')->fetchAll(true);
		$paginator->setCurrentPageNumber((int)$page);
		$paginator->setItemCountPerPage(10);
		return $paginator;
	}
	
	
	public function getLastId()
	{
		return $this->serviceManager->get('User\Model\UserTable')->getLastId();
	}
	
	public function flushRegistries()
	{
		return $this->serviceManager->get('User\Model\UserTable')->flushRegistries();
	}
	
	/*public function updateImageFile($id, $file)
	{
		$this->serviceManager->get('User\Model\UserTable')->updateImageFile($id, $file);
	}*/
	
	public function uploadImageFile($id, $file)
	{
		
		$files = $this->serviceManager->get('ServiceStorage')->uploadFile('users', $file, $id);
		
		if(count($files)>0) {
			$this->serviceManager->get('User\Model\UserTable')->updateImageFile($id, $files[0]);
			return $files[0];
		} else {
			return false;
		}
		
	}
	
	public function saveRole($role)
	{
		return $this->serviceManager->get('User\Model\RoleTable')->saveRole($role);
	}
	
	public function getRole($id)
	{
		$id = (int) $id;
	
		if($id<1)
			return false;
	
		$role = $this->serviceManager->get('User\Model\RoleTable')->getRole($id);
	
		return $role;
	
	}
	
	public function deleteRole($id)
	{
		$id = (int) $id;
	
		if($id<1)
			return false;
	
		return $this->serviceManager->get('User\Model\RoleTable')->deleteRole($id);
	
	}
	
	/*public function getRoles()
	{
		return $this->serviceManager->get('User\Model\RoleTable')->fetchAll();
	}*/
	
	public function getRoles($page = null)
	{
		
		if ($page===null)
			return $this->serviceManager->get('User\Model\RoleTable')->fetchAll();
		
		$paginator = $this->serviceManager->get('User\Model\RoleTable')->fetchAll(true);
		$paginator->setCurrentPageNumber((int)$page);
		$paginator->setItemCountPerPage(10);
		return $paginator;
		
	}
	
	public function saveLog($log)
	{
		return $this->serviceManager->get('User\Model\LogTable')->saveLog($log);
	}
	
	public function getUserForm()
	{
		return $this->serviceManager->get('User\Form\UserForm');
	}
	
	public function getRoleForm()
	{
		return $this->serviceManager->get('User\Form\RoleForm');
	}
	
	public function getLastAccessLogs()
	{
		return $this->serviceManager->get('User\Model\LogTable')->getLastAccessLogs();
	}
	
	public function sendRecoveryPassword($login, $email) {
		
		$userTable = $this->serviceManager->get('User\Model\UserTable');
		$user = $userTable->getUserByLogin($login);
		
		if (!$user)
			return false;

		//print $user->email; exit();
		if ($user->email!=$email)
			return false;
		
		$message = new \Zend\Mail\Message();
		
		$content = file_get_contents('public/email/recovery_password.html');
		$content = str_replace('{#COMPANY#}', 'Extend Tecnologia', $content);
		$content = str_replace('{#NAME#}', $user->name, $content);
		$content = str_replace('{#DATE#}', date('d/m/Y H:i:s'), $content);
		
		$request = $this->serviceManager->get('Request');
		$uri = $request->getUri();
		$scheme = $uri->getScheme();
		$host = $uri->getHost();
		$base = sprintf('%s://%s', $scheme, $host);

		$url = $base . '/admin/reset/' . $userTable->getUserHash($user->id);
		$content = str_replace('{#URL#}', $url, $content);
		
		$html = new MimePart($content);
		$html->type = 'text/html';
		
		$body = new MimeMessage();
		$body->setParts(array($html));
		
		$message->setBody($body);
		$message->setFrom('postmaster@extendtecnologia.com.br');
		$message->addTo($user->email);
		$message->setSubject('Recovery Password');
		
		$smtpOptions = new \Zend\Mail\Transport\SmtpOptions();
		
		$smtpOptions->setHost('mail.extendtecnologia.com.br')
				    ->setPort(587)
					->setConnectionClass('login')
					->setName('mail.extendtecnologia.com.br')
					->setConnectionConfig(array(
											'username' => 'postmaster@extendtecnologia.com.br',
											'password' => 'jk*$,++!,"%!',
											'ssl' => 'tls',
					));
		
		$transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
		$transport->send($message);

		return true;
		
	}
	
	public function resetPassword($uid, $password, $passwordConfirm) {
		
		if(!$uid||$password!=$passwordConfirm)
			return false;
		
		$userTable = $this->serviceManager->get('User\Model\UserTable');
		$row = $userTable->getUserByHash($uid);
		
		$user = new \User\Model\User;
		$user->exchangeArray((array)$row);
		
		$user->password = $password;
		$userTable->saveUser($user);
		
		return true;
		
	}
    
}
