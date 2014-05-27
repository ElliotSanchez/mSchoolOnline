<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UploadController extends AbstractActionController
{
    public function studentsAction()
    {

        $accountService = $this->getServiceLocator()->get('AccountService');
        $schoolService = $this->getServiceLocator()->get('SchoolService');

        $account = $accountService->get($this->params('a_id'));
        $school = $schoolService->get($this->params('s_id'));

        $form = $this->getServiceLocator()->get('StudentUploadForm');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();

                $tempFilename = $data['students-file']['tmp_name'];
                $filename = $data['students-file']['name'];

                $studentService = $this->getServiceLocator()->get('StudentService');

                $studentService->importStudentsFromFile($tempFilename, $account, $school);

                if ($tempFilename) {
                    unlink($tempFilename);
                }

                // Form is valid, save the form!
                $this->flashMessenger()->addSuccessMessage('Uploaded and processed ' . $filename);
            } else {
                $this->flashMessenger()->addErrorMessage('Upload form was invalid. Please try again.');
                foreach($form->getMessages() as $message) {
                    $this->flashMessenger()->addErrorMessage($message);
                }
            }

            return $this->redirect()->toRoute('admin/upload_students', array('a_id' => $account->id, 's_id' => $school->id));


        }

        $this->layout()->pageTitle = 'Account > School > Students > Upload';

        return new ViewModel(array(
            'account' => $account,
            'school' => $school,
            'form' => $form,
        ));

    }

    public function studentsFileAction() {

        $filename = 'mschool_student_upload_template.xlsx';

        $filePath = realpath(__DIR__ . '/../../../../../data/files/'.$filename);

        $response = new \Zend\Http\Response\Stream();
        $response->setStream(fopen($filePath, 'r'));
        $response->setStatusCode(200);

        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->addHeaderLine('Content-Disposition', 'inline; filename="' . $filename)
            ->addHeaderLine('Content-Length', filesize($filePath))
            ->addHeaderLine('Pragma', 'no-cache');
            //->addHeaderLine('Expires', '0');

        $response->setHeaders($headers);
        return $response;

    }

    // TODO RENAME THIS TO SEQUENCES
    public function pathwaysAction()
    {

        $form = $this->getServiceLocator()->get('PathwaysUploadForm');

        $request = $this->getRequest();
        if ($request->isPost()) {

            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();

                $tempFilename = $data['pathways-file']['tmp_name'];
                $filename = $data['pathways-file']['name'];

                // ********************************************************
                // PROCESS PATHWAYS
                // ********************************************************

                $pathwayService = $this->getServiceLocator()->get('SequenceService');

                $pathwayService->importSequencesFromFile($tempFilename);

                if ($tempFilename) {
                    unlink($tempFilename);
                }

                // Form is valid, save the form!
                $this->flashMessenger()->addSuccessMessage('Uploaded sequences file ' . $filename);
                return $this->redirect()->toRoute('admin/upload_pathways');
                //return $this->redirect()->toRoute('admin/pathways_preview');
            } else {
                $this->flashMessenger()->addErrorMessage('Upload form was invalid. Please try again.');
                foreach($form->getMessages() as $message) {
                    $this->flashMessenger()->addErrorMessage($message);
                }
                return $this->redirect()->toRoute('admin/upload_pathways');
            }
        }

        $this->layout()->pageTitle = 'Sequences Upload';
        $this->layout()->activeMenu = 'sequences';

        return new ViewModel(array(
            'form' => $form,
        ));

    }

    public function pathwaysFileAction() {

        $filename = 'mschool_pathways_template.xlsx';

        $filePath = realpath(__DIR__ . '/../../../../../data/files/'.$filename);

        $response = new \Zend\Http\Response\Stream();
        $response->setStream(fopen($filePath, 'r'));
        $response->setStatusCode(200);

        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->addHeaderLine('Content-Disposition', 'inline; filename="' . $filename)
            ->addHeaderLine('Content-Length', filesize($filePath))
            ->addHeaderLine('Pragma', 'no-cache');
        //->addHeaderLine('Expires', '0');

        $response->setHeaders($headers);
        return $response;

    }

    protected function getAccount() {

        $accountService = $this->getServiceLocator()->get('AccountService');

        return $accountService->get($this->params('id'));

    }

}
