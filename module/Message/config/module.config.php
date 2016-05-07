<?php
return array(
		'controllers' => array(
				'invokables' => array(
						'Message\Controller\Index' => 'Message\Controller\IndexController',
						'Message\Controller\Api' => 'Message\Controller\ApiController',
				),
		),
		'di' => array(
				'instance' => array(
						'alias' => array(
								'index' => 'Message\Controller\IndexController', 
								'api' => 'Message\Controller\MessageController', 
						),
				)
		),
		'router' => array(
				'routes' => array(
						'messages' => array(
								'type'    => 'literal',
								'options' => array(
										'route'    => '/admin/messages',
										'defaults' => array(
												'controller' => 'Message\Controller\Index',
												'action'     => 'index',
										),
								),
						),
						'api' => array(
								'type'    => 'segment',
								'options' => array(
										'route'    => '/admin/messages/api[/:id]',
										'constraints' => array(
												'id'     	=> '[0-9,]+',
										),
										'defaults' => array(
												'controller' => 'Message\Controller\Api',
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
);
