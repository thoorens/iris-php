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
 * Initially developped for Iris Workbench it has been replaced by DBSequence.
 * It may be helpfull. The removed subclass workbench\TextSequence 
 * presented an example of use (this class is still present in Blueprint library,
 * which can be obtained on demand to the author).
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ArraySequence extends _Sequence {

    /**
     * The provider class name for having an explanation for a sequence item.
     * Should be overridden in subclass or modified by accessor.
     * 
     * @var string
     */
    protected $_explanationProvider = \NULL;
    
    /**
     * The sequence to be followed
     * 
     * @var mixed[]
     */
    private $_sequence;

    /**
     * the original sequence (the array can contain arrays)
     * 
     * @var mixed[]
     */
    private $_rawSequence;

    public function __construct($sequence = array()) {
        $this->setSequence($sequence);
        $router = \Iris\Engine\Router::GetInstance();
        $url = $router->getAnalyzedURI();
        $this->setCurrent("/$url");
    }

    /**
     * Initialise the sequence. May be a dummy one (1 element)
     * In that case, the follower has label '!!!!NONE!!!!'
     * which forces the helper Button to no display.
     * 
     * @param string[] $sequence 
     */
    public function setSequence(array $sequence) {
        $this->_rawSequence = $sequence;
        foreach ($sequence as $key => $value) {
            if (is_array($value)) {
                $this->_sequence = array_merge($this->_sequence, $value);
            }
            else {
                $this->_sequence[$key] = $value;
            }
        }
    }

    /**
     * Returns the sequence as given by the user (the array can contain arrays)
     * @return array
     */
    public function getStructuredSequence() {
        return $this->_rawSequence;
    }

    /**
     *
     * @return string 
     */
    public function getFirst() {
        $copy = $this->_sequence;
        return array_shift($copy);
    }

    /**
     *
     * @return string
     */
    public function getLast() {
        $copy = $this->_sequence;
        return array_pop($copy);
    }

    /**
     * Obtains the description correspondong to the URL
     * 
     * @param string $url
     * @return string
     */
    protected function _getURL3($url, $defaultLabel) {
        if (is_null($url)) {
            return $this->_noLink();
        }
        list($description, $label) = explode('|', $this->_sequence[$url] . "|$defaultLabel");
        return array($label, $url, $description);
    }

    protected function _getNext() {
        $index = array_keys($this->_sequence);
        $followers = $index; // a copy
        array_shift($followers);
        array_pop($index);
        $maptable = array_combine($index, $followers);
        return $this->_find($maptable);
    }

    protected function _getPrevious() {
        $index = array_keys($this->_sequence);
        $followers = $index; // a copy
        array_shift($followers);
        array_pop($index);
        $maptable = array_combine($followers, $index);
        return $this->_find($maptable);
    }

    private function _find($maptable) {
        if (!isset($maptable[$this->_currentURL])) {

            return NULL;
        }
        return $maptable[$this->_currentURL];
    }

    

    /**
     * Sets the current index
     * 
     * @param type $url 
     */
    public function setCurrent($url) {
        if (!isset($this->_sequence[$url])) {
            $url = \NULL;
        }
        $this->_currentURL = $url;
    }

    protected function _getExplanationProvider() {
        $className = $this->_explanationProvider;
        return new $className();
    }

    public function setExplanationProvider($explanationProvider) {
        $this->_explanationProvider = $explanationProvider;
    }
    

}


