<?php

namespace Admin\STMath\Usage;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Admin\STMath\Usage\Entity as STMathUsage;
use Admin\STMath\Usage\Table as STMathUsageTable;
use Admin\STMath\Usage\Service as STMathUsageService;

class Factory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null) {

        $service = null;

        switch($requestedName) {
            case 'STMathUsageTableGateway':
                $service = $this->createTableGateway($serviceLocator);
                break;
            case 'STMathUsageTable':
                $service = $this->createTable($serviceLocator);
                break;
            case 'STMathUsageService':
                $service = $this->createModelService($serviceLocator);
                break;
        }

        return $service;

    }

    protected function createTableGateway(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new STMathUsage());
        return new TableGateway('stmath_usage', $dbAdapter, null, $resultSetPrototype);
    }

    protected function createModelService(ServiceLocatorInterface $serviceLocator) {
        return new STMathUsageService($serviceLocator->get('STMathUsageTable'));
    }

    protected function createTable(ServiceLocatorInterface $serviceLocator) {
        $tableGateway = $serviceLocator->get('STMathUsageTableGateway');
        $table = new STMathUsageTable($tableGateway);
        return $table;
    }
}

