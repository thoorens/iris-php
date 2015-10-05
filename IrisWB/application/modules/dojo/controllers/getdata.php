<?php

namespace modules\dojo\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * 
 */
class getdata extends \Iris\MVC\_AjaxController {

    protected $_hasACL = \FALSE;

    /**
     * Recuperates a JSON file for a slideshow demo
     */
    public function imagesAction() {
        $this->setAjaxType(self::JSON);
        $images = array('items' => $this->ExampleImages());
        $this->_renderLiteral(json_encode($images));
    }

}
