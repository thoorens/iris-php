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
    protected $_currentLanguage = \NULL;
    protected $_baseLanguageController = "/lang";

    public function help($flags = \NULL) {
        if ($this->_currentLanguage == \NULL) {
            $this->_currentLanguage = \Iris\Engine\Superglobal::GetSession('Language', \Iris\SysConfig\Settings::$DefaultLanguage);
        }
        return $this;
    }

    public function render($flags) {
        $html = "<$this->_tags>";
        foreach ($flags as $flag) {
            switch ($this->_tags) {
                case 'ul':
                    $html .=$this->_renderUl($flag);
                    break;
                case 'btn':
                    return $this->_renderButtons($flags);
                default :
                    $html .= $this->_renderTable($flag);
                    break;
            }
        }
        $html .= "</$this->_tags>";
        return $html;
    }

    public function getCaution(){
        if(\Iris\Engine\Memory::Get('GoodDescription')){
            return '';
        }
        $message = "";
        switch ($this->_currentLanguage) {
            case 'fr':
                $message = "Cette page n'a pas été traduite en français";
                break;
            case 'en':
                $message = 'This page has not been translated into English';
                break;
            case 'de':
                $message = "Diese Seite wurde nicht auf Deutsch übersetzt";
                break;
            case 'it':
                $message = "Questa pagina non è stato tradotto in italiano";
                break;
            case 'es':
                $message = 'Esta página no ha sido traducida al español';
                break;
            case 'nl':
                $message = "Deze pagina is niet vertaald in het Nederlands";
                break;
        }
        if($message != ''){
            $message = "$message<br>";
        }
        return $message;
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
        if ($line % $this->_perLine == 0) {
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

    protected function _renderButtons($flags) {
        $html = '';
        $languages = [
            'de' => ['de' => 'Deutsch', 'en' => 'Englisch', 'es' => 'Spanisch',
                'fr' => 'französisch', 'it' => 'Italienisch', 'nl' => 'Holländisch'],
            'en' => ['de' => 'german', 'en' => 'english', 'es' => 'spanish',
                'fr' => 'french', 'it' => 'italian', 'nl' => 'dutch'],
            'es' => ['de' => 'alemán', 'en' => 'inglés', 'es' => 'español',
                'fr' => 'frances', 'it' => 'italiano', 'nl' => 'holandés'],
            'fr' => ['de' => 'allemand', 'en' => 'anglais', 'es' => 'espagnol',
                'fr' => 'français', 'it' => 'italien', 'nl' => 'néerlandais'],
            'it' => ['de' => 'tedesco', 'en' => 'inglese', 'es' => 'spagnolo',
                'fr' => 'francese', 'it' => 'italiano', 'nl' => 'olandese'],
            'nl' => ['de' => 'Duits', 'en' => 'Engels', 'es' => 'Spaans',
                'fr' => 'Frans', 'it' => 'Italiaans', 'nl' => 'Nederlands'],
        ];
        switch ($this->_currentLanguage) {
            case 'de':
                $current = "Aktuelle Sprache : Deutsch";
                $other = "Die Seite auf ";
                break;
            case 'es':
                $current = "Idioma actual: Español";
                $other = "El sitio en ";
                break;
            case 'fr':
                $current = "Langue en cours : Français";
                $other = "Le site en ";
                break;
            case 'it':
                $current = "Lingua corrente : Italiano";
                $other = "Il sito in ";
                break;
            case 'nl':
                $current = "Huidige taal: Nederlands";
                $other = "De site in het ";
                break;
            default:
                $current = "Current language : English";
                $other = "The site in ";
                break;
        }
        foreach ($flags as $flag) {
            $otherName = $other . $languages[$this->_currentLanguage][$flag];
            if ($flag == $this->_currentLanguage) {
                $same = \Iris\Engine\Response::GetDefaultInstance()->getURL();
                $button = $this->callViewHelper('button', ['!', $same, $current]);
                $html .= $button->image('/!documents/file/images/locale/' . $flag . '_act.png');
            }
            else {
                $button = $this->callViewHelper('button', ['!', '/main/lang/' . $flag, $otherName]);
                $html .= $button->image('/!documents/file/images/locale/' . $flag . '.png');
            }
        }
        return $html;
    }

    protected function _languageName($flag) {
        $names = [
            'fr' => 'french',
            'en' => 'english',
            'it' => 'italian',
            'es' => 'español',
            'nl' => 'neederlands',
            'de' => 'deutsch',
        ];
        return $this->_($names[$flag]);
    }

    protected function _showCurrent($flag) {
        return $this->callViewHelper('image', '/!documents/file/images/locale/' . $flag . '_act.png', $this->_('Current language : ') . $this->_languageName($flag));
    }

    protected function _switchTo($flag) {
        $ref = $this->_baseLanguageController . "/$flag";
        $ref .= \Iris\Engine\Response::GetDefaultInstance()->getURL(\NULL, \TRUE);
        return $this->callViewHelper('link', '', $ref, $this->_('Choose language ') . $this->_languageName($flag))->image("/!documents/file/images/locale/$flag.png")->__toString();
    }

}
