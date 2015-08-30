<?php

namespace Calendar\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A new class to display a week with its events
 * 
 */
class Week extends \Iris\views\helpers\_ViewHelper implements \Calendar\Subhelpers\iTimeRenderer {

    use \Iris\Subhelpers\tSubhelperLink;

    /**
     * The name of the associated subhelper
     * 
     * @var string
     */
    private $_subhelperName = '\Calendar\Subhelpers\WeeklySchedule';

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

