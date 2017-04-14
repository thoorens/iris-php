<?php

namespace Iris\controllers\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * @todo Write the description of the class
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @deprecated replaced by FormMaker and its subclasses
 * @version :$Id:
 */
class MagickServer extends \Iris\controllers\helpers\_ControllerHelper {

    public $dojoMode = \FALSE;
    public $iniFileName = '';
    public $model = 'TAutoForm';
    public $emNumber = 1;
    public $testDupSubmit = \FALSE;

    /**
     * 
     * @return \Iris\controllers\helpers\MagickServer
     */
    public function help() {
        return $this;
    }

    /**
     * A common action 
     * for forms/autoform/magick and forms/autoform/dojoMagick
     * 
     * @param boolean $testDupSubmit determines the addition of a manual submit button
     * @param type $dojoMode
     * @return type
     */
    public function magick_action() {
        \models_internal\TAutoform::Verify();
        $em = \Iris\DB\_EntityManager::EMByNumber(1);
        $modelPath = "\\models_internal\\$this->model";
        $testEntity = $modelPath::GetEntity($em);
        if ($this->dojoMode) {
            $ff = new \Dojo\Forms\AutoFormFactory();
        }
        else {
            $ff = new \Iris\Forms\AutoFormFactory();
        }
        $form = $ff->initForm('test', $testEntity);

        if ($this->iniFileName != '') {
            $form->setFileName(IRIS_PROGRAM_PATH . $this->iniFileName);
        }
        // if a submit is manually created, an auto will not be created
        if ($this->testDupSubmit) {
            $ff->createSubmit('send')->setValue('Test')->addTo($form);
            $form->setSubmitMessage('Send');
        }
        return $form;
    }

}
