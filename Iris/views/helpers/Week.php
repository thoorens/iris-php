<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP.
 *
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IRIS-PHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IRIS-PHP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright 2012 Jacques THOORENS
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * @todo : verify the utility of this and suppress it in all layouts
 */

/**
 * A way to manage script and style references after all the page
 * has been generated. help() place an html comment and UpdateHeader()
 * replaces it by the necessary style and script loading
 * @todo change this stupid description
 * 
 */
class Week extends \Iris\views\helpers\_ViewHelper implements \Iris\Time\iTimeRenderer {

    use \Iris\Subhelpers\tSubhelperLink;

    /**
     * The name of the associated subhelper
     * 
     * @var string
     */
    private $_subhelperName = '\Iris\Time\WeeklySchedule';

    /*
     * iRenderer interface methods :
     */

    public function render(array $arg1, $arg2) {
        list($eventGroups, $base) = $arg1;
        $string = '<table class="show">';

        /* @var $events \Iris\Time\Events */
        foreach ($eventGroups as $events) {
            $nbEvents = $events->eventNumber();
            $string .= $this->renderDate($events, $nbEvents);
            switch ($nbEvents) {
                case 0:
                    $string .= "<td>" . $events->render() . '</td></tr>';
                    break;
                case 1:
                default:
                    $tr = '';
                    for ($r = 0; $r < $nbEvents; $r++) {
                        $string .= "$tr<td>" . $events->eventNumber() . "</td>\n</tr>\n";
                        $tr = "<tr>";
                    }
                    break;
            }
        }
        $string .= "</table>";
        return $string;
    }

    public function _renderEvent($events) {
        
    }

    public function _renderMultiEvents($events) {
        
    }

    /*
     * iTimeRenderer interface methods :
     */

    /**
     * 
     * O V E R W R I T E    W I T H  C A U T I O N ! !
     * 
     * @param events $events
     * @param type $link
     * @param string $class
     * @return type
     */
    public function renderCell($events, $link, $class) {
        if (!is_object($events)) {
            $value = '?';
        }
        else {
            $value = $events->showWeek($this);
        }
        if ($class != '') {
            $class = ' class ="' . $class . '" ';
        }
        return "<td $class $link>" . $value . "</td>";
    }

    /**
     * 
     * @param \Iris\Time\Events $events
     * @return type
     */
    public function collision($events) {
        return $events->eventNumber() . ' events';
    }

    public function eventDisplay($event) {
        return $event->display();
    }

    /**
     * Manages the multi events column title
     * @param \Iris\Time\Events $events
     * @return string
     */
    public function renderDate($events, $nbEvent) {
        $rowspan = $nbEvent>1 ? " rowspan=\"$nbEvent\" ":"";
        $string = "<tr>\n<th width=\"20%\" $rowspan>\n";
        $string .= $events->getDate()->toString("D d") . "</th>\n";
        return $string;
    }

}

