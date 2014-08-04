<?php

namespace Admin\Account;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Account\Entity as Account;
use Admin\Account\Table as AccountTable;
use Admin\School\Service as SchoolService;
use Admin\Teacher\Service as TeacherService;
use Admin\Mclass\Service as MClassService;
use Admin\CoachSignup\Entity as CoachSignup;

class Service extends ServiceAbstract {

    protected $schoolService;
    protected $teacherService;
    protected $mclassService;

    public function __construct(AccountTable $table, SchoolService $schoolService, TeacherService $teacherService, MClassService $mclassService) {

        parent::__construct($table);

        $this->schoolService = $schoolService;
        $this->teacherService = $teacherService;
        $this->mclassService = $mclassService;

    }

    public function create($data) {

        $account = new Account();

        $account->create($data);

        $account = $this->table->save($account);

        return $account;

    }

    public function getAccountWithSubdomain($subdomain) {
        return $this->table->fetchWith(array('subdomain' => $subdomain))->current();
    }

    public function getDefaultAccount() {
        return $this->table->fetchWith(array('is_default' => 1))->current();
    }

    public function signForDefaultAccount(CoachSignup $coachSignup) {

        $defaultAccount = $this->getDefaultAccount();
        $accountSchools = $this->schoolService->getForAccount($defaultAccount);

        $defaultSchool = $accountSchools->current();

        // CREATE COACH AT DEFAULT ACCOUNT AND SCHOOL
        $coach = $this->teacherService->create([
            'username' => $coachSignup->username,
            'password' => '',
            'email' => $coachSignup->email,
            'first_name' => $coachSignup->firstName,
            'last_name' =>$coachSignup->lastName ,
            'is_school_admin' => 0,
            'account_id' => $defaultAccount->id,
            'school_id' => $defaultSchool->id,
        ]);

        $coach->setPasswordHash($coachSignup->password);

        $this->teacherService->save($coach);

        // ASSIGN TO ALL CLASSES
        $mclasses = $this->mclassService->getForSchool($defaultSchool);

        foreach ($mclasses as $mclass) {
            $this->mclassService->addTeacherToMclass($coach, $mclass);
        }

        return $coach;

    }

}