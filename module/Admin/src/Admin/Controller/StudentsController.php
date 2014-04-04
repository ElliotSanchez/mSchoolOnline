<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class StudentsController extends AbstractActionController
{
    public function sequencesAction()
    {

        $studentService = $this->getServiceLocator()->get('StudentService');

        $student = $studentService->get($this->params('id'));

        $sequenceService = $this->getServiceLocator()->get('SequenceService');

        $sequenceOverview = $sequenceService->getStudentSequenceOverview($student);

        $this->layout()->pageTitle = $student->getFullName();

        $sequenceGroups = array();
        $currSequenceId = null;

        foreach ($sequenceOverview as $row) {
            if (!isset($sequenceGroups[$row['sequence_id']])) {
                $sequenceGroups[$row['sequence_id']] = array();
            }
            $sequenceGroups[$row['sequence_id']][] = $row;
        }

        return new ViewModel(array(
            'sequenceGroups' => $sequenceGroups,
        ));
    }

}
