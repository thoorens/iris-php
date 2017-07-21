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
 * Manages a sequence of pages in an array. Each item is
 *      'url' => 'comment|label'
 * If no label, assumes "Continue".
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Sequence {

    /**
     * Specifies the type of sequence used in WB (initially an ArraySequence, now a DBSequence)
     * The var has not been put in Settings because this class is only used in Workbench.
     * 
     * @var string
     */
    public static $DefaultSequenceType = '\\Iris\\Structure\\DBSequence'; 
    
    /**
     * Conserves the current URL
     * @var string
     */
    protected $_currentURL;

    /**
     * Returns the unique instance of the class, after creating it if necessary
     * 
     * @staticvar _Sequence $instance
     * @return _Sequence
     */
    public static function GetInstance() {
        static $instance = \NULL;
        if (is_null($instance)) {
            $class = static::$DefaultSequenceType;
            $instance = new $class();
        }
        return $instance;
    }

    /**
     * Gives an array of all sequence with sections 
     * and possibly subsections.
     */
    public abstract function getStructuredSequence($page);

    /**
     * Returns the URL of the first element 
     * 
     * @return string 
     */
    public abstract function getFirst();

    /**
     * Returns the URL of the last element
     * 
     * @return string
     */
    public abstract function getLast();

    /**
     * Obtains the description correspondong to the URL
     * an array of 3 elements acceptable by Button helper<ul>
     * <li>label
     * <li>url
     * <li>small description</ul>
     * 
     * @param string $url
     * @return string
     */
    protected abstract function _getURL3($url, $defaultLabel);

    /**
     * Sets the current URL to the first of the sequence
     */
    public function goFirst() {
        $this->_currentURL = $this->getFirs();
    }

    /**
     *  Sets the current URL to the last of the sequence
     */
    public function goLast() {
        $this->_currentURL = $this->getLast();
    }

    /**
     * Returns an array that Button help will display
     * as a null string
     * 
     * @return array
     */
    protected function _noLink() {
        return \Iris\views\helpers\Button::$NoLink;
    }

    /**
     * Returns the URL or an 3 item array corresponding
     * to the next element after the current one
     * 
     * @return string/array
     */
    public function getNext($array=TRUE) {
        $url = $this->_getNext();
        if (!$array)
            return $url;
        else
            return $this->_getURL3($url, 'Next');
    }

    /**
     * Returns the URL or an 3 item array corresponding
     * to the previous element after the current one
     * 
     * @return string/array
     */
    public function getPrevious($array=TRUE) {
        $url = $this->_getPrevious();
        if (!$array)
            return $url;
        else
            return $this->_getURL3($url, 'Previous');
    }

    /**
     * Internally gets the url of the previous element in sequence
     */
    protected abstract function _getPrevious();

    /**
     * Internally gets url the url of the next element in sequence
     */
    protected abstract function _getNext();
    
    public abstract function getCodeDescription();

    /**
     * Gets the current value 
     * ( an array of label - url - description)
     * 
     * @return array
     */
    public function getCurrent() {
        return $this->_getURL3($this->_currentURL, 'Current');
    }

    /**
     * Sets the current index
     * 
     * @param type $url 
     */
    public abstract function setCurrent($url);

    
    /**
     * Returns the description field of the current value
     * 
     * @return string
     */
    public function getCurrentDesc() {
        $current = $this->getCurrent();
        return $current[2];
    }

    /**
     * Gets the complete description of the current test
     * 
     * @param \Iris\VMC\View $view
     * @return string 
     */
    public function getContext($view){
        $context = $this->_getExplanationProvider();
        return $context->getMessage($view);
    }
     
    /**
     * 
     * @return \Iris\Structure\iExplanationProvider
     */
    protected abstract function _getExplanationProvider();
    
    
    /**
     * Return a message adapted to the current displayed view.
     * 
     * @param \Iris\MVC\View $view
     */
    public abstract function getMessage($view);
}

