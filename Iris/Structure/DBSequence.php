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
 */
class DBSequence extends \Iris\Structure\_Sequence{

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
            $returnValue = $this->_noLink();
        }
        else {
            $item = \models_internal\TSequences::GetItem($url);
            if (is_null($item)) {
                $label = $defaultLabel;
                $description = 'No description';
                $returnValue = \NULL;
            }
            else {
                $description = $item->Description;
                $label0 = $item->Label;
                $label = is_null($label0) ? $defaultLabel : $label0;
                $returnValue = [$label, $url, $description];
            }
        }
        return $returnValue;
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

    /**
     * Retrieves all sequences and stores them in groups of arrays indexed by the group name
     * 
     * @param type $page
     * @return type
     */
    public function getStructuredSequence($page) {
        return \models_internal\TSequences::GetStructuredSequence($page);
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
            $message = '';
        }
        else {
            $row = \models_internal\TSequences::GetItem($url);
            $message = $row->FR;
        }
        return $message;
    }

    public function getCodeDescription() {
        
    }

    
}
