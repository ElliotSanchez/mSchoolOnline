<?php

namespace MSchool\Pathway;

use Admin\Resource\Service as ResourceService;

class Service
{

    protected $resourceService;

    public function __construct(ResourceService $resourceService) {
        $this->resourceService = $resourceService;
    }

    public function getStudentPathwayForDate() {

        $container = new Container();

        // STEP 1
        $step1 = new Step($this->resourceService->get(1), null);
        $container->addStep($step1);

        // STEP 2
        $step2 = new Step($this->resourceService->get(19), null);
        $container->addStep($step2);

        // STEP 3
        $step3 = new Step($this->resourceService->get(20), null);
        $container->addStep($step3);

        return $container;

    }

}