<?php
namespace Calendar\Subhelpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A special type of renderer adding four other methods<ul>
 * <li> a way to render a unique cell
 * <li> a way to treat a collision
 * <li> a way to display an event
 * <li> a way to render a date
 * </ul>
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
interface iTimeRenderer extends \Iris\Subhelpers\iRenderer {
    
    public function renderCell($events, $link, $class);
    public function collision($events);
    public function eventDisplay($events);
    public function renderDate($events, $nbEvent);
}

