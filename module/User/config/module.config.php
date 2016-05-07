<?php
return array(
		'controllers' => array(
				'invokables' => array(
						'User\Controller\User' => 'User\Controller\UserController',
						'User\Controller\Auth' => 'User\Controller\AuthController',
						'User\Controller\Role' => 'User\Controller\RoleController',
						'User\Controller\Permission' => 'User\Controller\PermissionController',
				),
		),
		'di' => array(
				'instance' => array(
						'alias' => array(
								'user' => 'User\Controller\UserController', 
								'auth' => 'User\Controller\AuthController'
						),
						'user' => array(
								'parameters' => array(
										'broker' => 'Zend\Mvc\Controller\PluginBroker'
								)
						),
						/*'User\Event\Authentication' => array(
								'parameters' => array(
										'userAuthenticationPlugin' => 'User\Controller\Plugin\UserAuthentication',
										'aclClass'                 => 'User\Acl\Acl'
								)
						),*/
						'User\Acl\Acl' => array(
								'parameters' => array(
										'config' => include __DIR__ . '/acl.config.php'
								)
						),
						'User\Controller\Plugin\UserAuthentication' => array(
								'parameters' => array(
										'authAdapter' => 'Zend\Authentication\Adapter\DbTable'
								)
						),
						'Zend\Authentication\Adapter\DbTable' => array(
								'parameters' => array(
										'zendDb' => 'Zend\Db\Adapter\Mysqli',
										'tableName' => 'tbl_users',
										'identityColumn' => 'login',
										'credentialColumn' => 'password',
										'credentialTreatment' => 'MD5(CONCAT(?, "secretKey"))'
								)
						),
						/*'Zend\Db\Adapter\Mysqli' => array(
								'parameters' => array(
										'config' => array(
												'host' => 'localhost',
												'username' => 'username',
												'password' => 'password',
												'dbname' => 'dbname',
												'charset' => 'utf-8'
										)
								)
						),*/
						'Zend\Mvc\Controller\PluginLoader' => array(
								'parameters' => array(
										'map' => array(
												'userAuthentication' => 'User\Controller\Plugin\UserAuthentication'
										)
								)
						),
						/*'Zend\View\PhpRenderer' => array(
								'parameters' => array(
										'options' => array(
												'script_paths' => array(
														'user' => __DIR__ . '/../views'
												)
										)
								)
						)*/
				)
		),
		// The following section is new and should be added to your file
		'router' => array(
				'routes' => array(
						'users' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/admin/users[/:action][/:id]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												//'id'     => '[0-9]+',
												'id'     => '[0-9,]+',
										),
										'defaults' => array(
												'controller' => 'User\Controller\User',
												'action'     => 'index',
										),
								),
						),
						'roles' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/admin/groups[/:action][/:id]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[0-9,]+',
										),
										'defaults' => array(
												'controller' => 'User\Controller\Role',
												'action'     => 'index',
										),
								),
						),
						'permissions' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/admin/permissions[/:action][/:id]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[0-9,]+',
										),
										'defaults' => array(
												'controller' => 'User\Controller\Permission',
												'action'     => 'index',
										),
								),
						),
						'login' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/admin/login',
										'defaults' => array(
												'controller' => 'User\Controller\Auth',
												'action'     => 'login',
										)
								)
						),
						'logout' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/admin/logout',
										'defaults' => array(
												'controller' => 'User\Controller\Auth',
												'action'     => 'logout',
										)
								)
						),
						'forgot' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/admin/forgot',
										'defaults' => array(
												'controller' => 'User\Controller\Auth',
												'action'     => 'forgot',
										)
								)
						),
						'reset' => array(
								/*'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/admin/reset',
										'defaults' => array(
												'controller' => 'User\Controller\Auth',
												'action'     => 'reset',
										)
								)*/
								'type'    => 'segment',
								'options' => array(
										'route'    => '/admin/reset[/:uid]',
										/*'constraints' => array(
												'uid' => '[a-zA-Z][a-zA-Z0-9_-]*',
										),*/
										'defaults' => array(
												'controller' => 'User\Controller\Auth',
												'action'     => 'reset',
										),
								),
						),
				),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'user' => __DIR__ . '/../view',
				),
				'strategies' => array(
						'ViewJsonStrategy',
				),
		),
);
