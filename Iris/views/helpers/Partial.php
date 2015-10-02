<?php
namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Creates a partial view, passes a script and an array of data to it
 * and "renders" it.
 *  * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Partial extends _ViewHelper {

    
    /**
     *
     * @param type $name
     * @param type $data
     * @return type 
     */
    public function help($name, $data=array()) {
        $partialView = new \Iris\MVC\Partial($name,$data);
        return $partialView->render()."\n";
    }

    
}

