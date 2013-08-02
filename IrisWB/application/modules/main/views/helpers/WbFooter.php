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
 * @version $Id: $ */

/**
 * Footer management in workbench
 * 
 */
class WbFooter extends _ViewHelper {

    public function help($layoutName, $buttons = 5) {
        $sequence = \Iris\Structure\DBSequence::GetInstance();
        if (\Iris\SysConfig\Settings::HasMD5Signature()) {
            $signature = ' - ' . $this->callViewHelper('signature')->display();
            $buttonMD5 = $this->callViewHelper('signature')->saveButton();
            $buttonCode = $this->callViewHelper('button', $this->_codeButton($sequence));
        }
        else{
            $signature = $buttonMD5 = $buttonCode= '';
        }
        $html = "<b>Layout :</b> $layoutName";
        $html .= $signature;
        $html .= '<br/>';
        $html .= $this->callViewHelper('ILO_goInternal', $buttons);
        $html .= $buttonMD5; // possibly no shown
        $html .= $buttonCode; // possibly no shown
        $previous = $sequence->getPrevious();
        $html .= $this->callViewHelper('button', $previous);
        $html .= $this->callViewHelper('button', 'TOC', '/index/toc', 'Table of tests');
        $next = $sequence->getNext();
        $html .= $this->callViewHelper('button', $next);
        return $html;
    }

    /**
     * 
     * @param \Iris\Structure\_Sequence $sequence
     * @return array
     */
    private function _codeButton($sequence){
        $current = $sequence->getCurrent();
        if(is_null($current)){
            return \Iris\Subhelpers\Link::$NoLink;
        }
        $current[0] = 'Show code';
        $current[1] = '/show/code'.$current[1];
        $current[2] = 'See the code for '. $current[2];
        return $current;
    }
}

