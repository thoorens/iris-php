<?php

namespace models\crud;

/**
 * This subclass is for demonstration only;
 * It changes the default system icon folder
 *
 */
class CrudIconManager extends \Iris\Subhelpers\_CrudIconManager {

    

    public function _init() {
        $this->_systemIconDir = '/!documents/file/images/icons';
        // action2 uses a special parameter
        $icon2 =new \Iris\Subhelpers\Icon('action2', 'Execute action 2| (type %P)', '%P');
        $this->insert($icon2);
        // action3 has a special URL
        $icon3 =new \Iris\Subhelpers\Icon('action3', 'Execute action 3 on %O', '%I');
        $icon3->setSpecialUrl('special/action3');
        $this->insert($icon3);
        // action4 has a special URL and a special paramater
        $icon4 =new \Iris\Subhelpers\Icon('action4', 'Execute action 4| (type %P)', '%P');
        $icon4->setSpecialUrl('special/action4');
        $this->insert($icon4);
    }
    
    public function otherIcons(){
        $this->_systemIconDir = '/images/icons';
        return $this;
    }

}
