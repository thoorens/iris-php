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

/* 
 * This new class will be able to render a month
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * @todo : verify the utility of this and suppress it in all layouts
 */
class Month extends \Iris\views\helpers\_ViewHelper implements \Calendar\Subhelpers\iTimeRenderer {

    //use \Iris\Subhelpers\tSubhelperLink;

    public function help(){
        return $this;
    }
    
    /**
     * The name of the associated subhelper
     * 
     * @var string
     */
    private $_subhelperName = '\Calendar\Subhelpers\MonthlySchedule';

    /*
     * iRenderer interface methods :
     */

    /**
     * 
     * O V E R W R I T E    W I T H  C A U T I O N ! !
     * 
     * @param mixed[] $arg1
     * @param type $arg2
     * @return string
     */
    public function render(array $arg1, $arg2) {
        //$cell = 'td onclick="alert(\'%s\');" title="Cliquer pour voir le jour"';
        list($lines, $base) = $arg1;
        $string = '<table class="show"><tr>';
        $title = array_shift($lines);
        //$string .= implode("</th>\n<th>", \Iris\Time\_Period::GetWeekDays(3, $base));
        $string .= $this->_titleLine($title);
        $string .= "</tr><tr>\n";
        foreach ($lines as $line) {
            $result[] = implode("\n", $line);
        }
        $string .= implode("</tr>\n<tr>\n", $result);
        $string .= "</td></tr></table>";
        return $string;
    }

    private function _titleLine($titles){
        $string = '<th>';
        foreach($titles as $title){
            /* @var $title \Iris\Time\Date */
            $string .= $title->getDayOfWeek('3')."</th>\n<th>";
        }
        return $string."</th>\n";
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
            $value = $events->showMonth($this);
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
    public function collision($events){
        return $events->eventNumber().' events';
    }
    
    public function eventDisplay($event){
        return $event->display();
    }

    public function renderDate($events, $nbEvent) {
        
    }
}

