<?php

namespace Admin\CoachSignup;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\CoachSignup\Entity as CoachSignup;
use Admin\CoachSignup\Table as CoachSignupTable;
use Admin\Account\Service as AccountService;

use Mailgun\Mailgun;

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

        $entity->generateConfirmationKey();

        $entity = $this->table->save($entity);

        return $entity;

    }

    public function findWithConfirmationKey($confirmationKey) {

        return $this->table->fetchWith(['confirmation_key' => $confirmationKey])->current();

    }

    public function signup(array $data) {

        $coachSignup = $this->create($data);

        $mgClient = new Mailgun(\Admin\Module::$MAILGUN_API_KEY);
        $domain = \Admin\Module::$MAILGUN_SMTP_HOST;

        $email = $coachSignup->email;
        $firstName = $coachSignup->schoolName;
        $lastName = $coachSignup->lastName;
        $schoolName = $coachSignup->schoolName;
        $confirmationUrl = 'http://mschool.lp/signup/confirmation/' . $coachSignup->confirmationKey;

        $body = "<p>" . $firstName . ", hi!</p>

        <p>We're excited to set-up an mSchool account for " . $schoolName . ".</p>

        <p>To finish your sign-up, we need to verify your email address. Please got to the link below to confirm your account.</p>

        <p><a href=\"" . $confirmationUrl . "\">" . $confirmationUrl . "</a></p>

        <p>If you did not request an account, simply delete this message.</p>

        <p>Let's get learning!</p>

        <p>The mSchool team</p>";

        $result = $mgClient->sendMessage($domain, array(
            'from'    => 'mSchool Welcome  <welcome@mschools.org>',
            'to'      => $firstName . ' ' . $lastName .  ' <' . $email . '>',
            'subject' => 'Your mSchool Account',
            'html'    => $body,
        ));

    }

    public function confirmCoachSignup(CoachSignup $coachSignup) {

        if (!$coachSignup->isConfirmed) {
            $coach = $this->accountService->signForDefaultAccount($coachSignup);
            $coachSignup->isConfirmed = true;
            $this->save($coachSignup);
            return $coach;
        }

        return false;
    }

}