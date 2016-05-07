<?php
return array(
		'controllers' => array(
				'invokables' => array(
						'Translate\Controller\Language' => 'Translate\Controller\LanguageController',
						'Translate\Controller\Translate' => 'Translate\Controller\TranslateController',
				),
		),
		'di' => array(
				'instance' => array(
						'alias' => array(
								'language' => 'Translate\Controller\LanguageController', 
								'translate' => 'Translate\Controller\TranslateController'
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
						'languages' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/admin/languages[/:action][/:id]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												//'id'     => '[0-9]+',
												'id'     => '[0-9,]+',
										),
										'defaults' => array(
												'controller' => 'Translate\Controller\Language',
												'action'     => 'index',
										),
								),
						),
						'translations' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/admin/translations[/:action][/:language][/:id]',
										'constraints' => array(
												'action' 	=> '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     	=> '[0-9,]+',
												'language'  => '[0-9,]+',
										),
										'defaults' => array(
												'controller' => 'Translate\Controller\Translate',
												'action'     => 'index',
										),
								),
						),
				),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'translate' => __DIR__ . '/../view',
				),
				'strategies' => array(
						'ViewJsonStrategy',
				),
		),
		/*'translator' => array(
				'translation_file_patterns' => array(
						array(
								'type'     => 'phparray',
								'base_dir' => __DIR__ . '/data/translate',
								'pattern'  => '%s.php',
								'text_domain' => __NAMESPACE__,
						),
				),
		),*/
);
