<?php

namespace Admin\STMath\Progress;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Admin\STMath\Progress\Entity as STMathProgress;
use Admin\STMath\Progress\Table as STMathProgressTable;
use Admin\STMath\Progress\Service as STMathProgressService;

class Factory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null) {

        $service = null;

        switch($requestedName) {
            case 'STMathProgressTableGateway':
                $service = $this->createTableGateway($serviceLocator);
                break;
            case 'STMathProgressTable':
                $service = $this->createTable($serviceLocator);
                break;
            case 'STMathProgressService':
                $service = $this->createModelService($serviceLocator);
                break;
        }

        return $service;

    }

    protected function createTableGateway(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new STMathProgress());
        return new TableGateway('stmath_progress', $dbAdapter, null, $resultSetPrototype);
    }

    protected function createModelService(ServiceLocatorInterface $serviceLocator) {
        return new STMathProgressService($serviceLocator->get('STMathProgressTable'));
    }

    protected function createTable(ServiceLocatorInterface $serviceLocator) {
        $tableGateway = $serviceLocator->get('STMathProgressTableGateway');
        $table = new STMathProgressTable($tableGateway);
        return $table;
    }
}

