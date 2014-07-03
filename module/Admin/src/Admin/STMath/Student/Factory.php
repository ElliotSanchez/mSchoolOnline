<?php

namespace Admin\STMath\Student;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Admin\STMath\Student\Entity as STMathStudent;
use Admin\STMath\Student\Table as STMathStudentTable;
use Admin\STMath\Student\Service as STMathStudentService;

class Factory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null) {

        $service = null;

        switch($requestedName) {
            case 'STMathStudentTableGateway':
                $service = $this->createTableGateway($serviceLocator);
                break;
            case 'STMathStudentTable':
                $service = $this->createTable($serviceLocator);
                break;
            case 'STMathStudentService':
                $service = $this->createModelService($serviceLocator);
                break;
        }

        return $service;

    }

    protected function createTableGateway(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new STMathStudent());
        return new TableGateway('stmath_student', $dbAdapter, null, $resultSetPrototype);
    }

    protected function createModelService(ServiceLocatorInterface $serviceLocator) {
        return new STMathStudentService($serviceLocator->get('STMathStudentTable'));
    }

    protected function createTable(ServiceLocatorInterface $serviceLocator) {
        $tableGateway = $serviceLocator->get('STMathStudentTableGateway');
        $table = new STMathStudentTable($tableGateway);
        return $table;
    }
}

