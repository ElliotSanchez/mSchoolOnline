<?php

namespace Admin\ThinkThroughMath\Overview;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Admin\ThinkThroughMath\Overview\Entity as ThinkThroughMathOverview;
use Admin\ThinkThroughMath\Overview\Table as ThinkThroughMathOverviewTable;
use Admin\ThinkThroughMath\Overview\Service as ThinkThroughMathOverviewService;

class Factory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null) {

        $service = null;

        switch($requestedName) {
            case 'ThinkThroughMathOverviewTableGateway':
                $service = $this->createTableGateway($serviceLocator);
                break;
            case 'ThinkThroughMathOverviewTable':
                $service = $this->createTable($serviceLocator);
                break;
            case 'ThinkThroughMathOverviewService':
                $service = $this->createModelService($serviceLocator);
                break;
        }

        return $service;

    }

    protected function createTableGateway(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new ThinkThroughMathOverview());
        return new TableGateway('ttm_overview', $dbAdapter, null, $resultSetPrototype);
    }

    protected function createModelService(ServiceLocatorInterface $serviceLocator) {
        return new ThinkThroughMathOverviewService($serviceLocator->get('ThinkThroughMathOverviewTable'));
    }

    protected function createTable(ServiceLocatorInterface $serviceLocator) {
        $tableGateway = $serviceLocator->get('ThinkThroughMathOverviewTableGateway');
        $table = new ThinkThroughMathOverviewTable($tableGateway);
        return $table;
    }
}

