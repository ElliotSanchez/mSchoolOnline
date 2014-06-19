<?php

namespace Admin\Import;

use Admin\Student\Service as StudentService;

class Importer {

    protected $dropbox;
    protected $studentService;
    protected $importer;

    public function setDropbox($dropbox) {
        $this->dropbox = $dropbox;
    }

    public function setStudentService(StudentService $studentService) {
        $this->studentService = $studentService;
    }

    public function setImporter($importer) {
        $this->importer = $importer;
    }

    public function import() {
        $this->importer->setDropbox($this->dropbox);
        $this->importer->setStudentService($this->studentService);
        $this->importer->import();
    }

}