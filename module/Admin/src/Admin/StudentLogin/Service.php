<?php

namespace Admin\StudentLogin;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\StudentLogin\Entity as StudentLogin;
use Admin\Student\Entity as Student;

class Service extends ServiceAbstract {

    public function create($data) {

        $studentLogin = new StudentLogin();

        $studentLogin->create($data);

        $studentLogin = $this->table->save($studentLogin);

        return $studentLogin;

    }

    public function recordLogin(Student $student, \DateTime $loginDateTime) {

        $login = $this->create(array(
            'student_id' => $student->id,
            'login_at' => $loginDateTime->format('Y-m-d H:i:s'),
        ));

        return $login;
    }

}