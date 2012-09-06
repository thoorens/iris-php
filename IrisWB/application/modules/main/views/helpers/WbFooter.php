<?php



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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * Footer management in workbench
 * 
 */
class WbFooter extends _ViewHelper {
    
    public function help($layoutName,$buttons = 5){
        $html = "<b>Layout :</b> $layoutName";
        $html .= '<br/>';
        $html .= $this->_view->ILO_goInternal($buttons);
        $sequence = \Iris\Structure\DBSequence::GetInstance();
        $previous = $sequence->getPrevious();
        $html .= $this->button($previous);
        $html .= $this->button('TOC','/index/toc','Table of tests');
        $next = $sequence->getNext();
        $html .= $this->button($next);
        return $html;
    }
    
}

