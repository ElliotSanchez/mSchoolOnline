<?php

namespace Admin\DigitWhiz\Time;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Admin\DigitWhiz\Time\Entity as DigitWhizTime;
use Admin\DigitWhiz\Time\Table as DigitWhizTimeTable;
use Admin\DigitWhiz\Time\Service as DigitWhizTimeService;

class Factory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null) {

        $service = null;

        switch($requestedName) {
            case 'DigitWhizTimeTableGateway':
                $service = $this->createTableGateway($serviceLocator);
                break;
            case 'DigitWhizTimeTable':
                $service = $this->createTable($serviceLocator);
                break;
            case 'DigitWhizTimeService':
                $service = $this->createModelService($serviceLocator);
                break;
        }

        return $service;

    }

    protected function createTableGateway(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new DigitWhizTime());
        return new TableGateway('digitwhiz_time', $dbAdapter, null, $resultSetPrototype);
    }

    protected function createModelService(ServiceLocatorInterface $serviceLocator) {
        return new DigitWhizTimeService($serviceLocator->get('DigitWhizTimeTable'));
    }

    protected function createTable(ServiceLocatorInterface $serviceLocator) {
        $tableGateway = $serviceLocator->get('DigitWhizTimeTableGateway');
        $table = new DigitWhizTimeTable($tableGateway);
        return $table;
    }
}

