<?php

namespace Admin\ThinkThroughMath\Student;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Admin\ThinkThroughMath\Student\Entity as ThinkThroughMathStudent;
use Admin\ThinkThroughMath\Student\Table as ThinkThroughMathStudentTable;
use Admin\ThinkThroughMath\Student\Service as ThinkThroughMathStudentService;

class Factory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return in_array($requestedName, [
            'ThinkThroughMathStudentTableGateway',
            'ThinkThroughMathStudentService',
            'ThinkThroughMathStudentTable'
        ]);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {

        $service = null;

        switch($requestedName) {
            case 'ThinkThroughMathStudentTableGateway':
                $service = $this->createTableGateway($serviceLocator);
                break;
            case 'ThinkThroughMathStudentTable':
                $service = $this->createTable($serviceLocator);
                break;
            case 'ThinkThroughMathStudentService':
                $service = $this->createService($serviceLocator);
                break;
        }

        return $service;

    }

    protected function createTableGateway(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new ThinkThroughMathStudent());
        return new TableGateway('ttm_student', $dbAdapter, null, $resultSetPrototype);
    }

    protected function createService(ServiceLocatorInterface $serviceLocator) {
        return new ThinkThroughMathStudentService($serviceLocator->get('ThinkThroughMathStudentTable'));
    }

    protected function createTable(ServiceLocatorInterface $serviceLocator) {
        $tableGateway = $serviceLocator->get('ThinkThroughMathStudentTableGateway');
        $table = new ThinkThroughMathStudentTable($tableGateway);
        return $table;
    }
}

