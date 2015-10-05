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
 * This permits to have custom rendering for a month
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class WeekDemo extends \Calendar\views\helpers\Week {

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


