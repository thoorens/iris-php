<?php

namespace Iris\Structure;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Manage a sequence of tests (this class does not respect MVC paradigm
 * by mixing control and view elements)
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
 */
class DBSequence extends \Iris\Structure\_Sequence implements iExplanationProvider {

    /**
     *
     * @param mixed[] $sequence (ignored)
     */
    public function __construct($sequence = array()) {
        $router = \Iris\Engine\Router::GetInstance();
        $url = $router->getAnalyzedURI();
        $this->setCurrent("/$url");
    }

    /**
     * Obtains the description corresponding to the URL
     * an array of 3 elements acceptable by Button helper<ul>
     * <li>label
     * <li>url
     * <li>small description</ul>
     * 
     * @param string $url
     * @return string
     */
    protected function _getURL3($url, $defaultLabel) {
        if (is_null($url)) {
            return $this->_noLink();
        }
        $item = \models_internal\TSequences::GetItem($url);
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
        return \models_internal\TSequences::GetFirst();
    }

    /**
     * Returns the URL of the last element
     * 
     * @return string
     */
    public function getLast() {
        return \models_internal\TSequences::GetLast();
    }

    /**
     * Returns the URL or an 3 item array corresponding
     * to the next element after the current one
     * 
     * @return string/array
     */
    public function getNext($array = TRUE) {
        return \models_internal\TSequences::GetNext($this->_currentURL, $array);
    }

    /**
     * Returns the URL or an 3 item array corresponding
     * to the previous element after the current one
     * 
     * @return string/array
     */
    public function getPrevious($array = TRUE) {
        return \models_internal\TSequences::GetPrevious($this->_currentURL, $array);
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
        return \models_internal\TSequences::GetStructuredSequence();
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
        return $this->getPrevious(\FALSE);
    }

    /**
     * 
     * @return \Iris\Structure\iExplanationProvider
     */
    protected function _getExplanationProvider() {
        return $this;
    }

    /**
     * Return a message adapted to the current displayed view.
     * 
     * @param \Iris\MVC\View $view
     */
    public function getMessage($view) {
        $url = $this->getCurrent()[1];
        if (is_null($url)) {
            return '';
        }
        $row = \models_internal\TSequences::GetItem($url);
        return $row->FR;
    }

}


