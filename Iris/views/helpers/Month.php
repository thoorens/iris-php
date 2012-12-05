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
 * 
 * @todo change this stupid description

 */
class Month extends \Iris\views\helpers\_ViewHelper implements \Iris\Time\iTimeRenderer {

    use \Iris\Subhelpers\tSubhelperLink;

    /**
     * The name of the associated subhelper
     * 
     * @var string
     */
    private $_subhelperName = '\Iris\Time\MonthlySchedule';

    /*
     * iRenderer interface methods :
     */

    /**
     * 
     * O V E R W R I T E    W I T H  C A U T I O N ! !
     * 
     * @param array $arg1
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

