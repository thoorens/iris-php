<?php

namespace Iris\Forms\Validators;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * The abstract class for sharing all common methods of validators.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Validator { //implements \Iris\Translation\iTranslatable{

    use \Iris\Translation\tSystemTranslatable;
    
    /**
     *
     * @var type 
     * @todo decrecated ??
     */
    protected static $_Html5 = TRUE;
    
    /**
     *
     * @var \Iris\Forms\_Element 
     */
    protected $_element;

    /**
     *
     * @var _Validator 
     */
    protected $_previous = NULL;

    public function prepareValue($value) {
        return $value;
    }

    public function validate($value) {
        if (!$this->_localValidate($value)) {
            return FALSE;
        }
        if (!is_null($this->_previous)) {
            return $this->_previous->validate($value);
        }
        return TRUE;
    }

    /**
     * @param mixed $value
     * @return boolean 
     */
    protected function _localValidate(&$value) {
        return TRUE;
    }

    public function addValidator(_Validator $validator) {
        $this->_previous = $validator;
    }

    public function setElement($element) {
        $this->_element = $element;
    }
    
    public static function SetHTML5($html5=TRUE){
        static::$_Html5 = $html5;
    }

}

