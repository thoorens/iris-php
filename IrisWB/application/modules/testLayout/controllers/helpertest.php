<?php



namespace modules\testLayout\controllers;

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
 * In work bench tests some layout configurations
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class helpertest extends _testLayout{
    
    /**
     * This array is going to be used directly in the Conjugate helper
     * 
     * @var array
     */
    public $endings = [
        '','o' , 's', '', 'mos', 'is', 'n'
    ];
    
    public $title = \NULL;
    
    public function indexAction(){
        for($i=1;$i<6;$i++){
            $numbers[] = $this->ordinals($i);
        }
        $this->__numbers = $numbers;
    }               
    
    /**
     * This action uses a controller helper which has access to 
     * the public variables Endings and Title
     */
    public function complexAction(){
        for($i=1;$i<7;$i++){
            $words[$i] = $this->conjugate('hablar',$i);
            $words2[$i] = $this->conjugate('comer',$i);
        }
        $this->__title = $this->title;
        $this->__words = $words;
        $this->preRender('helpertest_complex');
        $this->__words = $words2;
    }
 
    /**
     * Returns the word with the last letters deleted
     * This method will be used in the Conjugate helper
     * 
     * @param  $word
     * @param type $letters
     * @return type
     */
    public function dropLast($word, $letters = 1){
        return substr($word,0,strlen($word)-$letters);
    }
}

