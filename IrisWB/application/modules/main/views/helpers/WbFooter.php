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

    /**
     * Corresponds to \ILO\views\helpers\GoInternal::ADMIN + \ILO\views\helpers\GoInternal::RESET
     */
    const BUTTONS = 5;

    public function help($buttons = self::BUTTONS) {
        $sequence = \wbClasses\DBSequence::GetInstance();
        if (\Iris\SysConfig\Settings::$MD5Signature) {
            $signature = ' - ' . $this->callViewHelper('signature')->display();
            $buttonMD5 = $this->callViewHelper('signature')->saveButton();
            $buttonCode = $this->callViewHelper('button', $this->_codeButton($sequence))->__toString();
        }
        else {
            $signature = $buttonMD5 = $buttonCode = '';
        }
        $language = $this->callViewHelper('language');
        $html = "<b>Layout :</b> " . $this->callViewHelper('ILO_layoutName');
        $html .= $signature;
        $html .= '<br/>';
        $html .= $language->getCaution();
        $html .= $this->callViewHelper('ILO_goInternal', $buttons);
        $html .= $buttonMD5; // possibly no shown
        $html .= $buttonCode; // possibly no shown
        $previous = $sequence->getPrevious();
        $html .= $this->callViewHelper('button', $previous);
        $html .= $this->callViewHelper('button', 'TOC', '/index/toc', 'Table of tests');
        $next = $sequence->getNext();
        $html .= $this->callViewHelper('button', $next);
        $html .= $language->setTags('btn')->render(['de','en','es','fr','it','nl']);
        return $html;
    }

    /**
     * Returns, if possible, an array link to the code presentation
     * of the present screen.
     * 
     * @param \Iris\Structure\_Sequence $sequence
     * @return array
     */
    private function _codeButton($sequence) {
        $current = $sequence->getCurrent();
        if (is_null($current)) {
            return \Iris\Subhelpers\Link::GetNoLinkLabel();
        }
        $current[0] = 'Show code';
        $current[1] = '/show/code' . $current[1];
        $current[2] = 'See the code for ' . $current[2];
        return $current;
    }

}
