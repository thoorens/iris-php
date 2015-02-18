<?php

namespace Iris\MVC;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A view which manages a small part of the page. It can be reused
 * or used in a loop
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Loop extends \Iris\MVC\Partial {

    /**
     * Type of view
     * 
     * @var string
     */
    protected static $_ViewType = 'loop';

    /*
     * Returns the name of the directory where to find the script
     * corresponding to the partial
     * 
     * @return string
     */

    public function viewDirectory() {
        return "scripts/";
    }

    public function render($dummy = NULL, $absolute = \FALSE) {
        ob_start();
        foreach ($this->_properties as $key => $propertie) {
            if (!is_array($propertie)) {
                $propertie = $this->_properties;
            }
            $partial = new Partial($this->_viewScriptName, $propertie, $key);
            echo $partial->render();
        }
        return ob_get_clean();
    }

}
