<?php

namespace Dojo\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A code container is a strutured object containing a head, a code
 * repository and a tail. Each part of the structure may be a string,
 * an array or another code container.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class CodeContainer {

    /**
     * The main container for the code
     * @var string[]
     */
    private $_codePieces = array();
    /**
     * The header part of the container (may contain init code or a function signature)
     * @var mixed
     */
    private $_header = '';
    /**
     * The final part of the container (will often contain closing } or ))
     * @var mixed
     */
    private $_tail = '';

    /**
     * Initializes the head part of the container
     * @param mixed $header
     * @return \Dojo\Engine\CodeContainer for fluent interface
     */
    public function setHeader($header) {
        $this->_header = $header;
        return $this;
    }

    /**
     * Initializes the tail part of the container
     * 
     * @param string/mixed $tail
     * @return \Dojo\Engine\CodeContainer
     */
    public function setTail($tail) {
        $this->_tail = $tail;
        return $this;
    }

    /**
     * Adds an named piece of code corresponding to a functionality. If it exists
     * does nothing.
     * 
     * @param string $name the name of the piece
     * @param string $code a multi line string containing code
     */
    public function addPieceOfCode($name, $code) {
        if (!isset($this->_codePieces[$name])) {
            $this->_codePieces[$name] = $code;
        }
    }

    /**
     * Returns a named piece of code, creating it if necessary.
     * 
     * @param string $name
     * @return CodeContainer
     */
    public function getPieceOfCode($name){
        if (!isset($this->_codePieces[$name])) {
            $code = new CodeContainer();
            $this->addPieceOfCode($name, $code);
        }
        return $this->_codePieces[$name];
    }
    
    /**
     * A wrapping function for the recursive _renderSlice
     * 
     * @return string The javascript code generated
     */
    public function render() {
        return $this->_renderContainer($this);
    }
    
    /**
     * Recursively generates code from a container
     * 
     * @param CodeContainer $container
     * @return type
     */
    private function _renderContainer($container) {
        $code = $this->_renderComponent($container->_header);
        $code .= $this->_renderComponent($container->_codePieces);
        $code .= $this->_renderComponent($container->_tail);
        return $code;
    }

    /**
     * Recursively generates a part of the container (may be complex)
     * 
     * @param mixed $component
     * @param string $alternative (FALSE, or 'if' or 'else if')
     * @return string
     */
    private function _renderComponent($component) {
        if (is_null($component)) {
            return '';
        }
        elseif (is_string($component)) {
            $code = $component . CRLF;
        }
        elseif (is_array($component)) {
            $code = '';
            foreach ($component as $part) {
                $code .= $this->_renderComponent($part);
            }
        }
        elseif($component instanceof Bubble){
            $code = $component->render(\FALSE);
        }
        else {
            $code = $this->_renderContainer($component);
        }
        return $code;
    }

    /**
     * Returns TRUE if the container has a non empty header
     * (it has been inited)
     * 
     * @return boolean
     */
    public function hasHeader() {
        return $this->_header != "";
    }

}

