<?php
namespace Admin;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
 use Admin\Model\Career;
 use Admin\Model\CareerTable;
 use Admin\Model\Category;
 use Admin\Model\CategoryTable;
 use Admin\Model\Course;
 use Admin\Model\CourseTable;
 use Admin\Model\Skill;
 use Admin\Model\SkillTable;
 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
             'Zend\Loader\ClassMapAutoloader' => array(
                 __DIR__ . '/autoload_classmap.php',
             ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
     // Add this method:
     public function getServiceConfig()
     {
        return array(
            'factories' => array(
                'Admin\Model\CareerTable' =>  function($sm) {
                    $tableGateway = $sm->get('CareerTableGateway');
                    $table = new CareerTable($tableGateway);
                    return $table;
                },
                'CareerTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Career());
                    return new TableGateway('career', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\CategoryTable' =>  function($sm) {
                    $tableGateway = $sm->get('CategoryTableGateway');
                    $table = new CategoryTable($tableGateway);
                    return $table;
                },
                'CategoryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Category());
                    return new TableGateway('category', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\CourseTable' =>  function($sm) {
                    $tableGateway = $sm->get('CourseTableGateway');
                    $table = new CourseTable($tableGateway);
                    return $table;
                },
                'CourseTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Course());
                    return new TableGateway('course', $dbAdapter, null, $resultSetPrototype);
                },
                'Admin\Model\SkillTable' =>  function($sm) {
                    $tableGateway = $sm->get('SkillTableGateway');
                    $table = new SkillTable($tableGateway);
                    return $table;
                },
                'SkillTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Skill());
                    return new TableGateway('skill', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
     }
}
