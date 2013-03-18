<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace iris\views\helpers;

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
 */

/**
 * This permits to have custom rendering for a month
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class MonthDemo extends \Iris\views\helpers\Month {

    public function render(array $arg1, $arg2) {
        return parent::render($arg1, $arg2);
    }

    /**
     * 
     * @param \Iris\Time\iEvent $event
     * @return type
     */
    public function eventDisplay($event) {
        switch ($event->getType()) {
            case 1:
                $image = '<img src="/!documents/file/images/wbicons/WBIco_P.png" alt="Personal icon">';
                break;
            case 2:
                $image = '<img src="/!documents/file/images/wbicons/WBIco_W.png" alt="Work icon">';
                break;
            case 3:
                $image = '<img src="/!documents/file/images/wbicons/WBIco_F.png" alt="Fun icon">';
                break;
            case 4:
                $image = '<img src="/!documents/file/images/wbicons/WBIco_H.png" alt="Health icon">';
                break;
        }
        return $event->getDate()->toString('<b>d</b>').'&nbsp'.$image;
    }

    /**
     * 
     * @param \Iris\Time\Events $events
     * @return type
     */
    public function collision($events) {
        $counter = $events->eventNumber();
        if ($counter > 5) {
            return '<img src="/!documents/file/images/wbicons/WBIco_6.png" alt="Personal icon">';
        }
        else {
            return sprintf('<img src="/!documents/file/images/wbicons/WBIco_%1d.png" alt="Personal icon">', $counter);
        }
    }

}

?>
