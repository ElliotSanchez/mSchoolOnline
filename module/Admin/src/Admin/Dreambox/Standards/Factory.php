<?php

namespace Admin\Dreambox\Standards;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Admin\Dreambox\Standards\Entity as DreamboxStandards;
use Admin\Dreambox\Standards\Table as DreamboxStandardsTable;
use Admin\Dreambox\Standards\Service as DreamboxStandardsService;

class Factory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null) {

        $service = null;

        switch($requestedName) {
            case 'DreamboxStandardsTableGateway':
                $service = $this->createTableGateway($serviceLocator);
                break;
            case 'DreamboxStandardsTable':
                $service = $this->createTable($serviceLocator);
                break;
            case 'DreamboxStandardsService':
                $service = $this->createModelService($serviceLocator);
                break;
        }

        return $service;

    }

    protected function createTableGateway(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new DreamboxStandards());
        return new TableGateway('dreambox_standards', $dbAdapter, null, $resultSetPrototype);
    }

    protected function createModelService(ServiceLocatorInterface $serviceLocator) {
        return new DreamboxStandardsService($serviceLocator->get('DreamboxStandardsTable'));
    }

    protected function createTable(ServiceLocatorInterface $serviceLocator) {
        $tableGateway = $serviceLocator->get('DreamboxStandardsTableGateway');
        $table = new DreamboxStandardsTable($tableGateway);
        return $table;
    }
}

