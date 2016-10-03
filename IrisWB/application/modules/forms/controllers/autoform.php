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
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * This is a test for Autoform class
 */
class autoform extends _forms {

    const TYPE = 0;
    const LABEL = 1;

    protected function _init() {
        $this->setViewScriptName('common/all');
        \Iris\SysConfig\Settings::$DefaultFormClass = '\\Dojo\\Forms\\FormFactory';
        print 'Old technologies';
    }

    public function indexAction() {
        \models_internal\TAutoform::Create();
        $tAutoform = \models_internal\TAutoform::GetEntity();
        $form = new \Iris\Forms\AutoForm($tAutoform);
        $form->setSubmitLabel('Valider');
        //$form->setSubmitMessage('Validate', 'send');
        $this->__form = $form->render();
    }

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

    
    
}
