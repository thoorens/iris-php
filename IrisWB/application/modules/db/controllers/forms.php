<?php

namespace modules\db\controllers;

/**
 * Project : srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of forms
 * 
 * @author 
 * @license 
 */
class forms extends _db {

    const TYPE = 0;
    const LABEL = 1;

    public function indexAction() {
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
                        /* @var $element Forms\Elements\InputElement */
                        $element = $ff->createText($name);
                        //$element->
                        $element->addTo($form);
                        break;
                }
            }
            echo $form->render();
        }
        die('ok');
        // this Title var is required by the default layout defined in _db
        $this->__Title = $this->callViewHelper('welcome', 1);
    }
    
    

}
