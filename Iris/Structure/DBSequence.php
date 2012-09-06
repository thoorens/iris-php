<?php

namespace Iris\Structure;

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
 * Manage a sequence of tests (this class does not respect MVC paradigm
 * by mixing control and view elements)
 * 
 */
class DBSequence extends \Iris\Structure\_Sequence implements iExplanationProvider {

    /**
     *
     * @param array $sequence (ignored)
     */
    public function __construct($sequence = array()) {
        $router = \Iris\Engine\Router::GetInstance();
        $url = $router->getAnalyzedURI();
        $this->setCurrent("/$url");
    }

    protected function _getURL3($url, $defaultLabel) {
        if (is_null($url)) {
            return $this->_noLink();
        }
        $item = \models\TSequence::GetItem($url);
        if (is_null($item)) {
            $label = $defaultLabel;
            $description = 'No description';
            return \NULL;
        }
        else {
            $description = $item->Description;
            $label0 = $item->Label;
            $label = is_null($label0) ? $defaultLabel : $label0;
        }
        return array($label, $url, $description);
    }

    /**
     * Returns the URL of the first element 
     * 
     * @return string
     * @param type $name Description 
     */
    public function getFirst() {
        return \models\TSequence::GetFirst();
    }

    /**
     * Returns the URL of the last element
     * 
     * @return string
     */
    public function getLast() {
        return \models\TSequence::GetLast();
    }

    /**
     * Returns the URL or an 3 item array corresponding
     * to the next element after the current one
     * 
     * @return string/array
     */
    public function getNext($array = TRUE) {
        return \models\TSequence::GetNext($this->_currentURL, $array);
    }

    /**
     * Returns the URL or an 3 item array corresponding
     * to the previous element after the current one
     * 
     * @return string/array
     */
    public function getPrevious($array = TRUE) {
        return \models\TSequence::GetPrevious($this->_currentURL, $array);
    }

    /**
     * Sets the current index
     * 
     * @param type $url 
     */
    public function setCurrent($url) {
        $this->_currentURL = $url;
    }

    public function getStructuredSequence() {
        return \models\TSequence::GetStructuredSequence();
    }

    /**
     * Required by inheritance, but useless
     * @return string
     */
    protected function _getNext() {
        return $this->getNext(\FALSE);
    }

    /**
     * Required by inheritance, but useless
     * @return string
     */
    protected function _getPrevious() {
        die('ok');
        return $this->getPrevious(\FALSE);
    }

    /**
     * 
     * @return \Iris\Structure\iExplanationProvider
     */
    protected function _getExplanationProvider() {
        return $this;
    }

    public function getMessage($view) {
        
    }

}

?>
