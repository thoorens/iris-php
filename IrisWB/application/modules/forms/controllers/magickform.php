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
 * @deprecated a test project about AutoFormFactory
 */
class magickform extends _forms {

    protected function _init() {
        $this->setViewScriptName('all');
    }

    
    public function testAction($type = 0){
        $realType = $type === 0 ? \NULL : $type;
        $maker = \Iris\Forms\FormMaker::HandMadeForm('test', $realType);
        $form = $maker->getForm();
        $this->__form = $form->render();
    }
    
    
    /**
     * A simple standard test for MagickForm (in html mode)
     * 
     * @param boolean $testDupSubmit
     */
    public function htmlAction($testDupSubmit = \FALSE) {
        /* @var $server \Iris\controllers\helpers\MagickServer */
        $server = $this->magickServer();
        $server->testDupSubmit = $testDupSubmit;
        $form = $server->magick_action();
        $this->setViewScriptName('all');
        $this->__form = $form->render();
    }

    /**
     * A simple standard test for MagickForm (in dojo mode)
     * 
     * @param boolean $testDupSubmit
     */
    public function dojoAction($testDupSubmit = \FALSE) {
        /* @var $server \Iris\controllers\helpers\MagickServer */
        $server = $this->magickServer();
        $server->testDupSubmit = $testDupSubmit;
        $server->dojoMode = \TRUE;
        $form = $server->magick_action();
        $this->setViewScriptName('all');
        $this->__form = $form->render();
    }

    /**
     * A simple standard test for MagickForm (in html mode)
     * 
     * @param boolean $testDupSubmit
     */
    public function htmlAnnotationsAction($testDupSubmit = \FALSE) {
        /* @var $server \Iris\controllers\helpers\MagickServer */
        $server = $this->magickServer();
        $server->testDupSubmit = $testDupSubmit;
        $form = $server->magick_action();
        $this->setViewScriptName('all');
        $this->__form = $form->render();
    }

    /**
     * A simple standard test for MagickForm (in dojo mode)
     * 
     * @param boolean $testDupSubmit
     */
    public function dojoAnnotationsAction($testDupSubmit = \FALSE) {
        /* @var $server \Iris\controllers\helpers\MagickServer */
        $server = $this->magickServer();
        $server->testDupSubmit = $testDupSubmit;
        $server->dojoMode = \TRUE;
        $form = $server->magick_action();
        $this->setViewScriptName('all');
        $this->__form = $form->render();
    }
    
    /**
     * A simple standard test for MagickForm (in html mode)
     * 
     * @param boolean $testDupSubmit
     */
    public function htmlFileAction($testDupSubmit = \FALSE) {
        /* @var $server \Iris\controllers\helpers\MagickServer */
        $server = $this->magickServer();
        $server->testDupSubmit = $testDupSubmit;
        $server->iniFileName = '/config/form/example.ini';
        $form = $server->magick_action();
        $this->setViewScriptName('all');
        $this->__form = $form->render();
    }

    /**
     * A simple standard test for MagickForm (in dojo mode)
     * 
     * @param boolean $testDupSubmit
     */
    public function dojoFileAction($testDupSubmit = \FALSE) {
        /* @var $server \Iris\controllers\helpers\MagickServer */
        $server = $this->magickServer();
        $server->testDupSubmit = $testDupSubmit;
        $server->dojoMode = \TRUE;
        $server->iniFileName = '/config/form/example.ini';
        $file = \TRUE;
        $form = $server->magick_action();
        $this->setViewScriptName('all');
        $this->__form = $form->render();
    }

}
