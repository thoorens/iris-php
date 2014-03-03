<?php

namespace modules\dojo\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of extension
 * 
 * @author jacques
 * @license not defined
 */
class extension extends _dojo {

    protected function _init() {
        $this->_setLayout('simple');
    }

    /*
     * An example of declarative use of a Dijit: just display a color palette: 
     * all code in view helper.
     * Clicking the palette display color code 
     */
    public function declarativeAction() {
    }
    
    /*
     * An example of programmatic use of a Dijit: just display a color palette: 
     * all code in view helper. 
     * Clicking the palette display color code 
     */
    public function programmatiqueAction(){
    }

    /**
     * Here the color palette modifies a part of the screen, whose ID is passed
     * as a parameter to the view helper
     */
    public function usefulAction() {
    }

    public function editorAction() {
        $ff = new \Dojo\Forms\FormFactory();
        $form = $ff->createForm('montest');
        $form->createHidden('cache')->setValue('Valeur secrete')->addTo($form);
        $form->createEditor('editor')
                ->setValeur('<b>test</b>')
                ->addTo($form)
                ->setLabel('Message:');
        $form->createSubmit('send')->setValue('Envoyer')->addTo($form);
        $this->__form = $form->render();
    }
}
