<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

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
        //i_d($sequence->getCodeDescription());
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
        $html .= $language->setTags('btn')->render(['de', 'en', 'es', 'fr', 'it', 'nl']);
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
        //i_d($sequence);
        $current = $sequence->getCurrent();
        if (is_null($current)) {
            $current =  \Iris\Subhelpers\Link::GetNoLinkLabel();
        }
        else {
            $current[0] = 'Show code';
            $current[1] = '/show/code' . $current[1];
            $current[2] = 'See the code for ' . $current[2];
        }
        return $current;
    }

}
