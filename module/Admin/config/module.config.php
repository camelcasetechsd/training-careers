<?php
 return array(
     'controllers' => array(
         'invokables' => array(
             'Admin\Controller\Dashboard' => 'Admin\Controller\DashboardController',
             'Admin\Controller\Career' => 'Admin\Controller\CareerController',
             'Admin\Controller\Category' => 'Admin\Controller\CategoryController',
             'Admin\Controller\Course' => 'Admin\Controller\CourseController',
         ),
     ),
     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'career' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/admin/career[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Admin\Controller\Career',
                         'action'     => 'index',
                     ),
                 ),
             ),
             'category' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/admin/category[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Admin\Controller\Category',
                         'action'     => 'index',
                     ),
                 ),
             ),
             'course' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/admin/course[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Admin\Controller\Course',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             'dashboard' => __DIR__ . '/../view/',
             'career' => __DIR__ . '/../view/',
             'category' => __DIR__ . '/../view/',
             'course' => __DIR__ . '/../view/',
         ),
     ),
 );