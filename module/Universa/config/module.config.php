<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Universa;

return [
    'router'                    => array(
        'routes' => array(
            'home' => array(
                'type'          => 'Literal',
                'options'       => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Universa\Controller',
                        'controller'    => 'Person',
                        'action'     => 'index',
                    ),
                ),
            ),
            'universa' => array(
                'type'          => 'Literal',
                'options'       => array(
                    'route'    => '/universa',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Universa\Controller',
                        'controller'    => 'Person',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'       => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults'    => array(),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'service_manager'           => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases'            => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    
    'doctrine'                  => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default'             => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
    ),
    'controllers' => [
        'factories' => [
            'Universa\Controller\PersonController' => function ($container) {
                return new Controller\PersonController(
                    null,
                    $container->get('Doctrine\ORM\EntityManager'),
                    $container->get('config')
                );
            },
        ],
        'aliases' => [
            'Universa\Controller\Person' => 'Universa\Controller\PersonController',
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'universa/person/index' => __DIR__ . '/../view/universa/person/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'console'                   => array(
        'router' => array(
            'routes' => array(),
        ),
    ),
];
