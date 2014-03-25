<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AccountsController extends AbstractActionController
{
    public function indexAction()
    {

        $this->layout()->pageTitle = 'Accounts';

        return new ViewModel(array(
            'accounts' => $this->getServiceLocator()->get('AccountService')->all(),
        ));
    }

    public function viewAction()
    {
        $this->layout()->pageTitle = 'Account';

        return new ViewModel([
            'account' => $this->getAccount(),
        ]);
    }

    public function schoolsAction() {

        $account = $this->getAccount();

        $schools = $this->getServiceLocator()->get('SchoolService')->getForAccount($account);

        $this->layout()->pageTitle = 'Account > Schools';

        return new ViewModel(array(
            'schools' => $schools,
            'account' => $account,
        ));

    }

    public function teachersAction() {

        $teachers = $this->getServiceLocator()->get('TeacherService')->all();

        $this->layout()->pageTitle = 'Account > Schools';

        return new ViewModel([
            'account' => $this->getAccount(),
            'teachers' => $teachers,
        ]);
    }

    public function studentsAction() {

        $account = $this->getAccount();

        $students = $this->getServiceLocator()->get('StudentService')->getForAccount($account);

        $this->layout()->pageTitle = 'Account > Students';

        return new ViewModel([
            'account' => $account,
            'students' => $students,
        ]);

    }

    public function addAction()
    {

        $form = $this->getServiceLocator()->get('AccountAddForm');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $account = $this->getServiceLocator()->get('AccountService')->create($form->getData());
                $this->flashMessenger()->addSuccessMessage('Added Account');
                $this->redirect()->toUrl('/account/'.$account->id);
            }

        }

        $this->layout()->pageTitle = 'Accounts > Add';

        return new ViewModel([
            'form' => $form,
        ]);

    }

    public function editAction()
    {

        $accountService = $this->getServiceLocator()->get('AccountService');

        $account = $accountService->get($this->params('id'));

        $form = $this->getServiceLocator()->get('AccountEditForm');

        $form->bind($account);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $account = $form->getData();
                $accountService->save($account);

                $this->flashMessenger()->addSuccessMessage('Updated Account');
                $this->redirect()->toUrl('/account/'.$account->id);
            }

        }

        $this->layout()->pageTitle = 'Account > Edit';

        return new ViewModel([
            'account' => $account,
            'form' => $form,
        ]);

    }

    public function schoolAddAction()
    {
        $account = $this->getAccount();

        $form = $this->getServiceLocator()->get('SchoolAddForm');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();
                $data['account_id'] = $account->id;
                $school = $this->getServiceLocator()->get('SchoolService')->create($data);
                $this->flashMessenger()->addSuccessMessage('Added School');
                $this->redirect()->toRoute('admin/school_edit', array('a_id' => $account->id, 's_id' => $school->id));
            }

        }

        $this->layout()->pageTitle = 'Account > Schools > Add';

        return new ViewModel([
            'account' => $account,
            'form' => $form,
        ]);

    }

    public function schoolEditAction()
    {

        $accountService = $this->getServiceLocator()->get('AccountService');
        $schoolService = $this->getServiceLocator()->get('SchoolService');

        $account = $accountService->get($this->params('a_id'));
        $school = $schoolService->get($this->params('s_id'));

        $form = $this->getServiceLocator()->get('SchoolEditForm');

        $form->bind($school);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $school = $form->getData();

                $school->accountId = $account->id; // TODO FIX THIS

                $schoolService->save($school);

                $this->flashMessenger()->addSuccessMessage('Updated School');
                $this->redirect()->toRoute('admin/school_edit', array('a_id' => $account->id, 's_id' => $school->id));
            }

        }

        $this->layout()->pageTitle = 'Account > Schools > Edit';

        return new ViewModel([
            'account' => $account,
            'form' => $form,
        ]);

    }

    // SCHOOLS
    public function teacherAddAction()
    {
        $account = $this->getAccount();

        $form = $this->getServiceLocator()->get('TeacherAddForm');

        $form->setAccount($account);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();

                $school = $this->getServiceLocator()->get('TeacherService')->create($data);
                $this->flashMessenger()->addSuccessMessage('Added Teacher');
                $this->redirect()->toRoute('admin/teacher_edit', array('a_id' => $account->id, 't_id' => $school->id));
            }

        }

        $this->layout()->pageTitle = 'Account > School > Teacher > Add';

        return new ViewModel([
            'account' => $account,
            'form' => $form,
        ]);

    }

    public function teacherEditAction()
    {

        $accountService = $this->getServiceLocator()->get('AccountService');
        $teacherService = $this->getServiceLocator()->get('TeacherService');

        $account = $accountService->get($this->params('a_id'));
        $teacher = $teacherService->get($this->params('t_id'));

        $form = $this->getServiceLocator()->get('TeacherEditForm');

        $form->setAccount($account);
        $form->bind($teacher);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $teacher = $form->getData();

                $password = $form->getInputFilter()->getRawValue('password');
                if (strlen($password))
                    $teacher->setPassword($password);

                $teacherService->save($teacher);

                $this->flashMessenger()->addSuccessMessage('Updated Teacher');
                $this->redirect()->toRoute('admin/teacher_edit', array('a_id' => $account->id, 't_id' => $teacher->id));
            }

        }

        $this->layout()->pageTitle = 'Account > School > Teacher > Edit';

        return new ViewModel([
            'account' => $account,
            'form' => $form,
        ]);

    }

    // STUDENTS
    public function studentAddAction()
    {
        $account = $this->getAccount();

        $form = $this->getServiceLocator()->get('StudentAddForm');

        $form->setAccount($account);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();

                $student = $this->getServiceLocator()->get('StudentService')->create($data);
                $this->flashMessenger()->addSuccessMessage('Added Student');
                $this->redirect()->toRoute('admin/student_edit', array('a_id' => $account->id, 's_id' => $student->id));
            }

        }

        $this->layout()->pageTitle = 'Account > School > Student > Add';

        return new ViewModel([
            'account' => $account,
            'form' => $form,
        ]);

    }

    public function studentEditAction()
    {

        $accountService = $this->getServiceLocator()->get('AccountService');
        $studentService = $this->getServiceLocator()->get('StudentService');

        $account = $accountService->get($this->params('a_id'));
        $student = $studentService->get($this->params('s_id'));

        $form = $this->getServiceLocator()->get('StudentEditForm');

        $form->setAccount($account);
        $form->bind($student);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $student = $form->getData();

                $password = $form->getInputFilter()->getRawValue('password');

                if (strlen($password))
                    $student->setPassword($password);

                $studentService->save($student);

                $this->flashMessenger()->addSuccessMessage('Updated Student');
                $this->redirect()->toRoute('admin/student_edit', array('a_id' => $account->id, 's_id' => $student->id));
            } else {
                //print_r($form->getMessages());
            }

        }

        $this->layout()->pageTitle = 'Account > School > Teacher > Edit';

        return new ViewModel([
            'account' => $account,
            'form' => $form,
        ]);

    }

    // CLASSES
    public function mclassesAction() {

        $account = $this->getAccount();

        $classes = $this->getServiceLocator()->get('MclassService')->getForAccount($account);

        $this->layout()->pageTitle = 'Account > School > Classes';

        return new ViewModel([
            'account' => $account,
            'classes' => $classes,
        ]);

    }

    public function mclassAddAction()
    {
        $account = $this->getAccount();

        $form = $this->getServiceLocator()->get('MclassAddForm');

        $form->setAccount($account);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();

                $mclass = $this->getServiceLocator()->get('MclassService')->create($data);
                $this->flashMessenger()->addSuccessMessage('Added Class');
                $this->redirect()->toRoute('admin/mclass_edit', array('a_id' => $account->id, 'm_id' => $mclass->id));
            }

        }

        $this->layout()->pageTitle = 'Account > School > Class > Add';

        return new ViewModel([
            'account' => $account,
            'form' => $form,
        ]);

    }

    public function mclassEditAction()
    {

        $accountService = $this->getServiceLocator()->get('AccountService');
        $mclassService = $this->getServiceLocator()->get('MclassService');

        $account = $accountService->get($this->params('a_id'));
        $mclass = $mclassService->get($this->params('m_id'));

        $form = $this->getServiceLocator()->get('MclassEditForm');

        $form->setAccount($account);
        $form->bind($mclass);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                $mclass = $form->getData();

                $mclassService->save($mclass);

                $this->flashMessenger()->addSuccessMessage('Updated Class');
                $this->redirect()->toRoute('admin/mclass_edit', array('a_id' => $account->id, 'm_id' => $mclass->id));
            } else {
                //print_r($form->getMessages());
            }

        }

        $this->layout()->pageTitle = 'Account > School > Class > Edit';

        return new ViewModel([
            'account' => $account,
            'form' => $form,
        ]);

    }

    protected function getAccount() {

        $accountService = $this->getServiceLocator()->get('AccountService');

        return $accountService->get($this->params('id'));

    }

}
