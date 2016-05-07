<?php
return array(
		'controllers' => array(
				'invokables' => array(
						'System\Controller\Setting' => 'System\Controller\SettingController',
						'System\Controller\Log' => 'System\Controller\LogController',
						'System\Controller\Storage' => 'System\Controller\StorageController',
						'System\Controller\Widget' => 'System\Controller\WidgetController',
						'System\Controller\Module' => 'System\Controller\ModuleController',
				),
		),
		// The following section is new and should be added to your file
		'router' => array(
				'routes' => array(
						'user-access' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/admin/logs[/:action][/:id]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[0-9]+',
										),
										'defaults' => array(
												'controller' => 'System\Controller\Logs',
												'action'     => 'access',
										),
								),
						), 
						'access-logs' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/admin/logs',
										'defaults' => array(
												'controller' => 'System\Controller\Logs',
												'action'     => 'access',
										)
								)
						),
						'system-logs' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/admin/logs',
										'defaults' => array(
												'controller' => 'System\Controller\Log',
												'action'     => 'index',
										)
								)
						),
						/*'settings' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/admin/settings',
										'defaults' => array(
												'controller' => 'System\Controller\Setting',
												'action'     => 'index',
										)
								)
						),*/
						'settings' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/admin/settings[/:action][/:id]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[0-9]+',
										),
										'defaults' => array(
												'controller' => 'System\Controller\Setting',
												'action'     => 'index',
										),
								),
						),
						'storages' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/admin/storages',
										'defaults' => array(
												'controller' => 'System\Controller\Storage',
												'action'     => 'index',
										)
								)
						),
						/*'widgets' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/admin/widgets',
										'defaults' => array(
												'controller' => 'System\Controller\Widget',
												'action'     => 'index',
										)
								)
						),*/
						'widgets' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/admin/widgets[/:action][/:id]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[0-9]+',
										),
										'defaults' => array(
												'controller' => 'System\Controller\Widget',
												'action'     => 'index',
										),
								),
						),
						'modules' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/admin/modules[/:action][/:id]',
										'constraints' => array(
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id'     => '[0-9]+',
										),
										'defaults' => array(
												'controller' => 'System\Controller\Module',
												'action'     => 'index',
										),
								),
						),
						
						
				),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'system' => __DIR__ . '/../view',
				),
		),
);