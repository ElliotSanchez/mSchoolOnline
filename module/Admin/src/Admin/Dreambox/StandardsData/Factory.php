<?php

namespace Admin\Dreambox\StandardsData;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Admin\Dreambox\StandardsData\Entity as DreamboxStandardsData;
use Admin\Dreambox\StandardsData\Table as DreamboxStandardsDataTable;
use Admin\Dreambox\StandardsData\Service as DreamboxStandardsDataService;

class Factory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null) {

        $service = null;

        switch($requestedName) {
            case 'DreamboxStandardsDataTableGateway':
                $service = $this->createTableGateway($serviceLocator);
                break;
            case 'DreamboxStandardsDataTable':
                $service = $this->createTable($serviceLocator);
                break;
            case 'DreamboxStandardsDataService':
                $service = $this->createModelService($serviceLocator);
                break;
        }

        return $service;

    }

    protected function createTableGateway(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new DreamboxStandardsData());
        return new TableGateway('dreambox_standards_data', $dbAdapter, null, $resultSetPrototype);
    }

    protected function createModelService(ServiceLocatorInterface $serviceLocator) {
        return new DreamboxStandardsDataService($serviceLocator->get('DreamboxStandardsDataTable'));
    }

    protected function createTable(ServiceLocatorInterface $serviceLocator) {
        $tableGateway = $serviceLocator->get('DreamboxStandardsDataTableGateway');
        $table = new DreamboxStandardsDataTable($tableGateway);
        return $table;
    }
}

