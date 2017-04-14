<?php

namespace modules\main\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

class auto extends _main {


    public function indexAction(){
        $position = \Dojo\views\helpers\TabContainer::TOP;
        $default = 0;
        $this->callViewHelper('dojo_tabContainer', "container")
                ->setDefault($default)
                // the dimensions should be placed in the view script
                ->setDim(250, 450)
                ->setPosition($position)
                ->setItems([
                    "label1" => 'First tab',
                    "label2" => 'Second tab',
        ]);
    }
    
}
