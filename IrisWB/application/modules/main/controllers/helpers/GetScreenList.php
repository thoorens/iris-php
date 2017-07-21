<?php

namespace Iris\controllers\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */



/**
 * A helper making some come calculation to fill part of the page
 * 
 */
class GetScreenList extends _ControllerHelper {

    /**
     * 
     * @return type
     */
    public function help($page=\NULL) {
        /* @var $sequence \wbClasses\DBSequence */
        $sequence = \Iris\Engine\Memory::Get('sequence', \wbClasses\DBSequence::GetInstance());
        $list = $sequence->getStructuredSequence($page);
        foreach ($list as $key => $value) {
            if (is_array($value)) {
                array_walk($value, array($this, '_keepDescription'));
                $newList[$key] = $value;
            }
            else {
                $newList[$key] = $this->_keepDescription($value);
            }
        }
        return $newList;
    }

    private function _keepDescription(&$value, $dummy = \NULL) {
        list($description, $dum) = explode('|', $value . '|');
        $value = $description;
        return $value;
    }

}
