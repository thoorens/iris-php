<?php

namespace Dojo\Ajax;

defined('CRLF') or define('CRLF', "\n");

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A code provider for Dojo javascript
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 */
class Code implements \Iris\Design\iSingleton {

    use \Iris\Engine\tSingleton;

    public function getSynchroFunction($name) {
        
    }

    public function getSynchroSwitch($switch) {
        $values['stop'] = $switch & Synchro::BSTOP ? <<<END_OF_CASE
                  case 'stop':
                     running = 0;
                     old = msg;
                     break;
END_OF_CASE
                : '';
        $values['start'] = $switch & Synchro::BSTART ? <<<END_OF_CASE
                  case 'start':
                     running = 1; 
                     old = msg;
                     break;
END_OF_CASE
                : '';
        $values['restart'] = $switch & Synchro::BRESTART ? <<<END_OF_CASE
                  case 'restart':
                     restart();
                     break;
END_OF_CASE
                : '';
        $values['next'] = $switch & Synchro::BNEXT ? <<<END_OF_CASE
                  case 'next':
                     next();
                     break;
END_OF_CASE
                : '';
        $values['previous'] = $switch & Synchro::BPREVIOUS ? <<<END_OF_CASE
                  case 'previous':
                     previous();
                     break;
END_OF_CASE
                : '';
        return $values;
    }

}
