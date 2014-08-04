<?php

namespace Admin\CoachSignup;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\CoachSignup\Entity as CoachSignup;
use Admin\CoachSignup\Table as CoachSignupTable;
use Admin\Account\Service as AccountService;

class Service extends ServiceAbstract {

    protected $accountService;

    public function __construct(CoachSignupTable $table, AccountService $accountService) {

        parent::__construct($table);

        $this->accountService = $accountService;

    }

    public function create($data) {

        if (empty($data['username'])) $data['username'] = $data['email'];

        $entity = new CoachSignup();

        $entity->create($data);

        $entity = $this->table->save($entity);

        return $entity;

    }

    public function signup(array $data) {

        $coachSignup = $this->create($data);

        $this->accountService->signForDefaultAccount($coachSignup);

    }

}