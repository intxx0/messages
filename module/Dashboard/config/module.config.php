<?php
return array(
		'controllers' => array(
				'invokables' => array(
						'Dashboard\Controller\Index' => 'Dashboard\Controller\IndexController',
				),
		),
		// The following section is new and should be added to your file
		'router' => array(
				'routes' => array(
						'dashboard' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/admin/dashboard',
										'defaults' => array(
												'controller' => 'Dashboard\Controller\Index',
												'action'     => 'index',
										)
								)
						),
						'admin' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route'    => '/admin',
										'defaults' => array(
												'controller' => 'Dashboard\Controller\Index',
												'action'     => 'index',
										)
								)
						),
				),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'dashboard' => __DIR__ . '/../view',
				),
		),
);