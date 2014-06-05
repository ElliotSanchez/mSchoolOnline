<?php

namespace Admin\Student;

use Admin\ModelAbstract\ServiceAbstract as ServiceAbstract;
use Admin\Student\Entity as Student;
use Admin\Account\Entity as Account;
use Admin\School\Entity as School;

class Service extends ServiceAbstract {

    public function create($data) {

        $student = new Student();

        $student->create($data);

        $student = $this->table->save($student);

        return $student;

    }

    public function getForUsername($username) {

        return $this->table->fetchWith(['username' => $username])->current();

    }

    public function getForUsernameWithAccount($username, Account $account) {

        return $this->table->fetchWith(['username' => $username, 'account_id' => $account->id])->current();

    }

    public function getWithStudentNumber($number) {

        return $this->table->fetchWith(array('number' => $number))->current();

    }

    public function getForAccount(Account $account) {

        return $this->table->fetchWith(array('account_id' => $account->id));

    }

    public function getForSchool(School $school) {

        return $this->table->fetchWith(array('school_id' => $school->id));

    }

    public function importStudentsFromFile($filename, $account, $school) {

        $objPHPExcel = \PHPExcel_IOFactory::load($filename);

        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

        $NUMBER_COL = 'A';
        $FIRST_NAME_COL = 'B';
        $LAST_NAME_COL = 'C';
        $DOB_COL = 'D';
        $GENDER_COL = 'E';
        $GRADE_LEVEL_COL = 'F';
        $ETHNICITY_COL = 'G';
        $IEP_COL = 'H';

        foreach ($rowIterator as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            if(1 == $row->getRowIndex ()) continue; //skip first row

            $data = array();

            $data['account_id'] = $account->id;
            $data['school_id'] = $school->id;

            foreach ($cellIterator as $cell) {

                switch ($cell->getColumn()) {
                    case $NUMBER_COL:       $data['number'] = $cell->getValue();
                        break;
                    case $FIRST_NAME_COL:   $data['first_name'] = $cell->getValue();
                                            break;
                    case $LAST_NAME_COL:    $data['last_name'] = $cell->getValue();
                                            break;
                    case $DOB_COL:          $data['dob'] = $cell->getValue();
                                            break;
                    case $GENDER_COL:       $data['gender'] = $cell->getValue();
                                            break;
                    case $GRADE_LEVEL_COL:  $data['grade_level'] = $cell->getValue();
                                            break;
                    case $ETHNICITY_COL:    $data['ethnicity'] = $cell->getValue();
                                            break;
                    case $IEP_COL:          $data['iep'] = $cell->getValue();
                                            break;
                    default:                break;
                }

            }

            $student = $this->create($data);
            $student->username = $this->generateUsernameFor($student);
            $student->setPassword($this->generatePasswordFor($student));

            $this->save($student);

            if (!$student->number) {
                $student->number = $student->id;
                $this->save($student);
            }

        }

    }

    public function generateUsernameFor(Student $student) {
        $username =  strtolower($student->firstName.$student->lastName);

        $username = str_replace(' ', '', $username);

        if ($this->usernameExists($username)) {
            $username .= $student->number;
        }

        return $username;

    }

    public function usernameExists($username) {
        return count($this->table->fetchWith(array('username' => $username)));
    }

    public function generateMnameFor(Student $student) {
        $buildName = function($student) {
            $firstInitial = substr($student->firstName, 0, 1);
            $lastInitial = substr($student->lastName, 0, 1);
            $random = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4) . substr(str_shuffle("0123456789"), 0, 4);
            $mname = $firstInitial . $lastInitial . $random;
            return strtolower($mname);
        };

        $mname = $buildName($student);

        // TRY IT THREE TIMES
        if ($this->mnameExists($mname)) {
            $mname = $buildName($student);

            if ($this->mnameExists($mname)) {
                $mname = $buildName($student);

                if ($this->mnameExists($mname)) {
                    $mname = $buildName($student);
                } else {
                    $mname .= '_'.$student->number; // JUST MAKE SOMETHING UNIQUE
                }
            }
        }

        return $mname;

    }

    public function mnameExists($mname) {
        return $this->table->fetchWith(array('mname' => $mname))->count();
    }

    public function generatePasswordFor(Student $student) {

        $numbers = substr(str_shuffle("0123456789"), 0, 2);

        $mpassword = $this->getRandomAdjective() . $this->getRandomNoun() . $numbers;

        return $mpassword;

    }

    private function getRandomNoun() {

        $nouns = ['bean','beet','corn','dhal','kale','kiwi','leek','lime','okra',
            'pear','plum','apple','chard','chive','grape','guava','lemon','mango',
            'melon','olive','onion','peach','pecan','prune','almond','carrot',
            'cashew','celery','cherry','citrus','daikon','fennel','garlic','lentil',
            'lychee','orange','oyster','papaya','peanut','pepper','potato','radish',
            'raisin','squash','tomato','turnip','walnut','bear','calf','colt','deer',
            'fawn','foal','goat','hare','ibex','lamb','lion','lynx','mule','orca',
            'oxen','seal','wolf','bison','camel','horse','hyena','koala','lemur',
            'moose','mouse','otter','panda','puppy','sheep','tiger','whale','zebra',
            'alpaca','badger','bobcat','gopher','impala','jaguar','marmot','monkey',
            'ocelot','rabbit','turtle','walrus','wombat','duck','gull','hawk','lark',
            'loon','swan','tern','crane','eagle','egret','finch','goose','heron',
            'macaw','quail','robin','stork','canary','condor','falcon','grouse',
            'magpie','osprey','parrot','pigeon','puffin','toucan','turkey','moth',
            'wasp','snail','aspen','comet','bike','salad','bottle','swing','slide',
            'ball','racket','cake','cheese','icing','bread','taco','kitten','trout',
            'shark','perch','skate','prawn','shrimp','manta','guppy','bass','carp',
            'pike','tuna','boxer','hound','poodle','beagle','basset','azalea',
            'baobab','bonsai','gingko','spruce','willow','palm','pine','teak',
            'rice','paint','brick','paper','marble','drum','guitar','song','chair',
            'desk','pencil','sofa','shelf','book','plate','spoon','sugar','milk',
            'pillow','bucket','shovel','train','boat','glider','beach','field',
            'barn','farm','bridge','street','road','tree','plant','cactus',
            'butter','acorn','lego','brush','mirror'];

        return $nouns[array_rand($nouns)];

    }

    private function getRandomAdjective() {

        $nouns = ['blue','cyan','gold','gray','jade','lime','navy','pink','plum',
            'rose','rosy','ruby','sage','black','brown','coral','dusky',
            'green','hazel','ivory','lilac','mocha','olive','peach','pearl',
            'snowy','taupe','topaz','white','auburn','blonde','bronze','cherry',
            'copper','golden','indigo','maroon','orange','purple','silver',
            'violet','yellow','calm','clever','cute','dizzy','eager','fancy',
            'funny','joyous','shiny','silly','super','wild','tall','small',
            'fast','slow','soft','fuzzy','yummy','sweet','goofy','lucky',
            'tricky','teeny','petite','jolly','strong','brave','noisy','cool',
            'groovy','magic','quiet','quirky','pale','rainy','sunny','cloudy',
            'hazy','foggy','snowy','tame','zesty','windy','wise'];

        return $nouns[array_rand($nouns)];

    }

}