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
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This is a test for Autoform class
 */
class autoform extends _forms {

    protected function _init() {
        $this->setViewScriptName('common/all');
        \Iris\SysConfig\Settings::$DefaultFormClass = '\\Dojo\\Forms\\FormFactory';
    }

    public function indexAction() {
        \models_internal\TAutoform::Create();
        $tAutoform = \models_internal\TAutoform::GetEntity();
        $form = new \Iris\Forms\AutoForm($tAutoform);
        $form->setSubmitLabel('Validate');
        $this->__form = $form->render();
    }

}
