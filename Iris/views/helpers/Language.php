<?php

namespace Iris\views\helpers;

/**
 * Displays language icons for language choice
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * 
 */
class Language extends _ViewHelper {

    protected $_tags = 'ul';
    protected $_perLine = 5;
    protected $_currentLanguage;
    protected $_baseLanguageController = "/lang";

    public function help($flags = \NULL) {
        if(is_null($flags)){
            return $this;
        }
        else{
            return $this->render($flags);
        }
    }
    
    
    public function render($flags){
        $this->_currentLanguage = \Iris\Translation\_Translator::GetCurrentLanguage();
        $html = "<$this->_tags>";
        foreach($flags as $flag){
            if($this->_tags == 'ul'){
                $html .=$this->_renderUl($flag);
            }
            else{
                $html .= $this->_renderTable($flag);
            }
        }
        $html .= "</$this->_tags>";
        return $html;
    }

    public function setTags($tags) {
        $this->_tags = $tags;
        return $this;
    }

    public function setPerLine($perLine) {
        $this->_perLine = $perLine;
        return $this;
    }

    public function setCurrentLanguage($currentLanguage) {
        $this->_currentLanguage = $currentLanguage;
        return $this;
    }

        
    private function _renderTable($flag) {
        static $line = 0;
        if($line % $this->_perLine == 0){
            $image;
        }
        if ($flag == $this->_currentLanguage) {
            $imageLink = $this->_showCurrent($flag);
        }
        else {
            $imageLink = $this->_switchTo($flag);
        }
        return "<li>$imageLink</li>";
    }
    
    
    private function _renderUl($flag) {
        if ($flag == $this->_currentLanguage) {
            $imageLink = $this->_showCurrent($flag);
        }
        else {
            $imageLink = $this->_switchTo($flag);
        }
        return "<li>$imageLink</li>";
    }
    
    protected function _languageName($flag){
         $names = [
             'fr' => 'french',
             'en' => 'english',
             'it'=> 'italian',
         ];
         return $this->_($names[$flag]);
    }
    
    protected function _showCurrent($flag){
        return $this->callViewHelper('image','/!documents/file/images/locale/'.$flag.'_act.png',$this->_('Current language : ').$this->_languageName($flag));
    }
    
    protected function _switchTo($flag){
        $ref = $this->_baseLanguageController."/$flag";
        $ref .= \Iris\Engine\Response::GetDefaultInstance()->getURL(\NULL, \TRUE);
        return $this->callViewHelper('link', '', $ref , $this->_('Choose language ').$this->_languageName($flag))->image("/!documents/file/images/locale/$flag.png")->__toString();
    }

}

