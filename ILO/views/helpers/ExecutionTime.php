<?php
namespace ILO\views\helpers;
/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 * 
 * @copyright 2011-2015 Jacques THOORENS/*
 */

/*
 * Displays the execution time. In fact the helper
 * returns a place holder which will be replaced
 * by Head::_MakeTuning() after the page rendering 
 * is complete.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
 */
class ExecutionTime extends \Iris\views\helpers\_ViewHelper {

    
    public function help($time) {
        $timeComment = $this->_('Execution time');
        $timeLabel = $this->callViewHelper('image', '/!documents/file/images/icons/stopwatch.png', 
                'stopwatch symbol', $timeComment);
        return "$timeLabel <span id=\"iris_RTD\"><b>$time</b></span>";
    }
}

