<?php

namespace Admin\DigitWhiz\Mastery;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Admin\DigitWhiz\Mastery\Entity as DigitWhizMastery;
use Admin\DigitWhiz\Mastery\Table as DigitWhizMasteryTable;
use Admin\DigitWhiz\Mastery\Service as DigitWhizMasteryService;

class Factory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null) {

        $service = null;

        switch($requestedName) {
            case 'DigitWhizMasteryTableGateway':
                $service = $this->createTableGateway($serviceLocator);
                break;
            case 'DigitWhizMasteryTable':
                $service = $this->createTable($serviceLocator);
                break;
            case 'DigitWhizMasteryService':
                $service = $this->createModelService($serviceLocator);
                break;
        }

        return $service;

    }

    protected function createTableGateway(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new DigitWhizMastery());
        return new TableGateway('digitwhiz_mastery', $dbAdapter, null, $resultSetPrototype);
    }

    protected function createModelService(ServiceLocatorInterface $serviceLocator) {
        return new DigitWhizMasteryService($serviceLocator->get('DigitWhizMasteryTable'));
    }

    protected function createTable(ServiceLocatorInterface $serviceLocator) {
        $tableGateway = $serviceLocator->get('DigitWhizMasteryTableGateway');
        $table = new DigitWhizMasteryTable($tableGateway);
        return $table;
    }
}

