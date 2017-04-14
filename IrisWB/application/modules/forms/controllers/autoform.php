<?php

namespace modules\forms\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * This is part of the WorkBench fragment
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * This is a test for Autoform class
 */
class autoform extends _forms {

    const TYPE = 0;
    const LABEL = 1;

    protected function _init() {
        $this->setViewScriptName('common/auto');
        //\Iris\SysConfig\Settings::$DefaultFormClass = '\\Dojo\\Forms\\FormFactory';
        $this->__base = '/forms/autoform/';
    }

    /**
     * @deprecated
     */
    public function indexAction() {
        $em = \models_internal\TAutoform::Verify();
        $tAutoform = \models\TAutoform::GetEntity($em);
        $form = new \Iris\Forms\AutoForm($tAutoform);
        $form->setSubmitLabel('Valider');
        $form->setSubmitMessage('Validate', 'send');
        $this->__form = $form->render();
    }

    /**
     * @deprecated
     */
    public function oldAction() {
        $forms = \Iris\Engine\Memory::Get('param_forms', \NULL);
        $ff = \Dojo\Forms\FormFactory::GetDefaultFormFactory();
        $form = $ff->createForm('test');
        if (!is_null($forms)) {
            /* @var $params \Iris\SysConfig\Config */
            $params = $forms['customers'];
            foreach ($params as $name => $field) {
                $params = explode('!', $field . '!!!!!!');
                switch ($params[self::TYPE]) {
                    case 'T':
                        //print $params[self::TYPE];
                        /* @var $element Forms\Elements\InputElement */
                        $element = $ff->createText($name);
                        //$element->
                        $element->addTo($form);
                        break;
                }
            }
            $this->__form = $form->render();
        }
        // this Title var is required by the default layout defined in _db
        $this->__Title = $this->callViewHelper('welcome', 1);
    }

    /**
     * 
     * @param type $actionNumber
     * @deprecated
     */
    public function testAction($actionNumber = 1) {
        $maker = \Iris\Forms\FormMaker::MetadataForm('test');
        switch ($actionNumber) {
            // the form may be rendered by the maker object
            case 1:
                $maker->setSubmitValue('Envoyer');
                $this->__form = $maker->formRender();
                break;
            // the form is extracted before, but treatments in the maker can continue
            case 2:
                $form = $maker->getForm();
                $factory = $maker->getFormFactory();
                $element = $factory->createReset('Reset');
                $element->setValue('Reset');
                $form->addElement($element);
                $maker->getSubmitButton()->setValue('Envoyer');
                $this->__form = $form->render();
                //$this->__form = $form->render();
                break;
        }
    }

    /**
     * Tests and illustrates a form made by hand (HTML)
     * 
     * @param string $dbType Can force a change of entity manager type
     */
    public function HTMLHandAction($dbType = 'default') {
        //i_dnd(get_browser($_SERVER["HTTP_USER_AGENT"]));
        //i_d($_SERVER["HTTP_USER_AGENT"]);
        $factoryType = \Iris\Forms\_FormMaker::HTML;
        $this->_handAction($dbType, $factoryType);
    }

    /**
     * Tests and illustrates a form made by hand (Dojo)
     * 
     * @param string $dbType Can force a change of entity manager type
     */
    public function DojoHandAction($dbType = 'default') {
        $factoryType = \Iris\Forms\_FormMaker::DOJO;
        $this->_handAction($dbType, $factoryType);
    }

    /**
     * 
     * @param type $dbType
     * @param type $factoryType
     */
    protected function _handAction($dbType, $factoryType) {
        $this->_verifyDbType($dbType);
        $maker = \Iris\Forms\_FormMaker::HandMadeForm('form', $factoryType);
        $form = $maker->getForm(\FALSE);
        $form->setLayout(new \Iris\Forms\TabLayout());
        $factory = $maker->getFormFactory();
        // text
        $text = $factory->createText('Name')
                ->setLabel('Nom du client:')
                ->setTitle('Simple text');
        $maker->addElement($text);
        // password
        $factory->createPassword('password')
                ->setLabel('Mot de passe:')
                ->setTitle('Simple password')
                ->addTo($form); // same result as addElement
        //$maker->addElement($text);
        // hidden
        $factory->createHidden('Hidden')
                ->setLabel('Nom du client:')
                ->setTitle('Zone cachée')
                ->addTo($form);
        // Date
        $factory->createDate('Date')
                ->setLabel('Date de naissance:')
                ->setTitle('Simple date')
                ->addTo($form);
        // Time
        $factory->createTime('Time')
                ->setLabel('Heure du rendez-vous:')
                ->setTitle('Tiùe')
                ->addTo($form);
        // DateTime
        $factory->createDateTime('DateTime')
                ->setLabel('Jour et heure local du rendez-vous:')
                ->setTitle('Date Time Local')
                ->addTo($form);
        // DateTimeLocal
        $factory->createDateTimeLocal('DTLocal')
                ->setLabel('Jour et heure du rendez-vous:')
                ->setTitle('Simple text')
                ->addTo($form);
        // URL
        $factory->createUrl('Url')
                ->setLabel('Web site:')
                ->setTitle('Url')
                ->addTo($form);
        // Email
        $factory->createEmail('Email')
                ->setLabel('Customer email:')
                ->setTitle('Email')
                ->addTo($form);
        // color
        $factory->createColor('Color')
                ->setLabel('Choisir la couleur:')
                ->setTitle('Color chooser')
                ->addTo($form);
        // Email
        $factory->createNumber('Number')
                ->setLabel('Nombre de voiture:')
                ->setTitle('Number')
                ->addTo($form);
        // week
        $factory->createTel('Tel')
                ->setLabel('Heure du rendez-vous:')
                ->setTitle('Simple text')
                ->addTo($form);
        // tel
        $factory->createRange('Range')
                ->setLabel('Heure du rendez-vous:')
                ->setTitle('Simple text')
                ->addTo($form);
        // Email
//        $factory->createEmail('Time')
//                ->setLabel('Heure du rendez-vous:')
//                ->setTitle('Simple text')
//                ->addTo($form);
//        

        $this->__form = $maker->formRender();
    }

    /**
     * Tests and illustrates a form made from an model class (HTML)
     * 
     * @param string $dbType Can force a change of entity manager type
     */
    public function HTMLModelAction($dbType = 'default') {
        $factoryType = \Iris\Forms\_FormMaker::HTML;
        $this->_modelAction($dbType, $factoryType);
    }

    /**
     * Tests and illustrates a form made from an model class  (Dojo)
     * 
     * @param string $dbType Can force a change of entity manager type
     */
    public function DojoModelAction($dbType = 'default') {
        $factoryType = \Iris\Forms\_FormMaker::DOJO;
        $this->_modelAction($dbType, $factoryType);
    }

    /**
     * 
     * @param type $dbType
     * @param type $factoryType
     */
    protected function _modelAction($dbType, $factoryType) {
        $this->_verifyDbType($dbType);
        $maker = \Iris\Forms\_FormMaker::EntityForm('form', $factoryType);
        $this->__form = $maker->formRender();
    }

    /**
     * Tests and illustrates a form made with an ini file (HTML)
     * 
     * @param string $dbType Can force a change of entity manager type
     */
    public function HTMLFileAction($dbType = 'default') {
        $factoryType = \Iris\Forms\_FormMaker::HTML;
        $this->_fileAction($dbType, $factoryType);
    }

    /**
     * Tests and illustrates a form made with an ini file (Dojo)
     * 
     * @param string $dbType Can force a change of entity manager type
     */
    public function DojoFileAction($dbType = 'default') {
        $factoryType = \Iris\Forms\_FormMaker::DOJO;
        $this->_fileAction($dbType, $factoryType);
    }

    /**
     * 
     * @param type $dbType
     * @param type $factoryType
     */
    protected function _fileAction($dbType, $factoryType) {
        $this->_verifyDbType($dbType);
        $maker = \Iris\Forms\_FormMaker::FileForm(IRIS_PROGRAM_PATH . '/config/form/example.ini', $factoryType);
        $this->__form = $maker->formRender();
    }

    /**
     * Tests and illustrates a form made from parameters (HTML)
     * 
     * @param string $dbType Can force a change of entity manager type
     */
    public function HTMLParamsAction($dbType = 'default') {
        $factoryType = \Iris\Forms\_FormMaker::HTML;
        $this->_paramsAction($dbType, $factoryType);
    }

    /**
     * Tests and illustrates a form made from parameters (Dojo)
     * 
     * @param string $dbType Can force a change of entity manager type
     */
    public function DojoParamsAction($dbType = 'default') {
        $factoryType = \Iris\Forms\_FormMaker::DOJO;
        $this->_paramsAction($dbType, $factoryType);
    }

    /**
     * 
     * @param type $dbType
     * @param type $factoryType
     */
    protected function _paramsAction($dbType, $factoryType) {
        $this->_verifyDbType($dbType);
        $maker = \Iris\Forms\_FormMaker::ParametricForm('form', $factoryType);
        $this->__form = $maker->formRender();
    }

    /**
     * Tests and illustrates a form made with the comments in the table (HTML)
     * 
     * @param string $dbType Can force a change of entity manager type
     */
    public function HTMLCommentsAction($dbType = 'default') {
        $factoryType = \Iris\Forms\_FormMaker::HTML;
        $this->_commentsAction($dbType, $factoryType);
    }

    /**
     * Tests and illustrates a form made with the comments in the table (Dojo)
     * 
     * @param string $dbType Can force a change of entity manager type
     */
    public function DojoCommentsAction($dbType = 'default') {
        $factoryType = \Iris\Forms\_FormMaker::DOJO;
        $this->_commentsAction($dbType, $factoryType);
    }

    /**
     * 
     * @param type $dbType
     * @param type $factoryType
     */
    protected function _commentsAction($dbType, $factoryType) {
        $this->_verifyDbType($dbType);
        if (\Iris\DB\_EntityManager::GetAlternativeEM()->getType() == 'sqlite') {
            $results = $this->storeResults();
            $results->addBadResult("<h5>SQLite does not support comments in table definition</h5>", 1);
            i_d($results);
        }
        else {
            $maker = \Iris\Forms\_FormMaker::CommentForm('form', $factoryType);
            $this->__form = $maker->formRender();
        }
    }

    /**
     * 
     * @param string $dbType
     */
    protected function _verifyDbType($dbType) {
        if ($dbType !== 'default') {
            $session = \Iris\Users\Session::GetInstance();
            $session->dbini = $dbType;
        }
    }

    public function browsersAction() {
        $b = \Iris\System\Browser::GetBrowser();
    }

}
