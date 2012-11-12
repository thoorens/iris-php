<?php

namespace Dojo\views\helpers;

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
 * Description of Grid
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 * @todo This helper is not functionnal: Dojo documentation is not very easy to 
 * understand 
 */
class Calendar extends _DojoHelper {

    public function help() {
        throw new \Iris\Exceptions\NotSupportedException('To be implemented later');
        return <<<STOP
       <div data-dojo-type="dojox/calendar/Calendar"
     data-dojo-props="date: new Date(2012, 0, 1), dateInterval:'week', dateIntervalSteps:2"
     style="position:relative;width:600px;height:500px"></div> 
STOP;
    }

    protected function _init() {
        $source = $this->_manager->getSource();
        $this->_manager->addStyle("$source/dojox/grid/resources/claroGrid.css");
        //$this->_manager->addRequisite('dojo._base.lang');
        $this->_manager->addRequisite('dojox.calendar.Calendar');
        $this->_manager->addRequisite('dojo.parser');
    }

}

?>
