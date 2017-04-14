<?php
namespace modules\draft\controllers;

/**
 * This class is intended to contain draft fragments generated with option dojo_tab
 * Please copy its content before add changes
 */
class demo extends \modules\_application {

    function indexAction(){
            $default = 'first';
        $position = \Dojo\views\helpers\TabContainer::TOP; //or BOTTOM|LEFT|RIGHT
        $this->callViewHelper('dojo_tabContainer', "container")
                ->setDefault($default)
                ->setPosition($position)
                ->setItems([
                    "first" => 'First label',
                    "second" => 'Second label',
        ]);       

    }
    
}
