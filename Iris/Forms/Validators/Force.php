<?php



namespace Iris\Forms\Validators;

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
 */

/**
 * A validator for forcing special characters in input
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Force extends _Validator {
    const NONE = 0;
    const ALPHA = 1;
    const EXTENDEDALPHA = 3; // ALPHA included
    const NUM = 4;
    const UPPER = 8;
    const LOWER = 16;
    const FORCE = 32;

    const ALPHA_LIST = 'abcdefghijklmnopqrstuvwxyz';
    const NUMLIST = '0123456789';

    protected $_baseChar;
    protected $_charList;

    public function __construct($baseChar, $charList='') {
        $this->_baseChar = $baseChar;
        $this->_charList = $charList;
    }

    protected function _localValidate(&$value) {
        $multiChar = new \Iris\Translation\UTF8($value);
        // if necessary force upper or lower
        if ($this->_baseChar & self::FORCE) {
            if ($this->_baseChar & self::UPPER) {
                $multiChar->toUpper();
            }
            elseif ($this->_baseChar & self::LOWER) {
                $multiChar->toLower();
            }
            else {
                throw new \Iris\Exceptions\FormException('FORCE must be added to LOWER or UPPER in Force validator');
            }
            $value = $multiChar->__toString();
        }
        $list = $this->_charList;
        $multiChar->reInit($value);
        if($this->_baseChar & self::EXTENDEDALPHA){
            $multiChar->noAccent();
        }
        if($this->_baseChar & self::ALPHA){
            if($this->_baseChar & self::LOWER){
                $list .= self::ALPHA_LIST;
            }elseif($this->_baseChar & self::UPPER){
                $list .= strtoupper(self::ALPHA_LIST);
            }else{
                $list .= self::ALPHA_LIST.strtoupper(self::ALPHA_LIST);
            }
        }
        if($this->_baseChar & self::NUM){
            $list .= self::NUMLIST;
        }
        $multiChar->rewind();
        $allValid = TRUE;
        
        while($multiChar->valid() and $allValid){
            $char = $multiChar->current();
            $allValid = (mb_strpos($list,$char,0,'utf-8')!==FALSE);
            $multiChar->next();
        }
        if(!$allValid){
            $this->_element->setError($this->_('Refused character : ',TRUE).$char);
        }
        return $allValid;
    }

}

?>
