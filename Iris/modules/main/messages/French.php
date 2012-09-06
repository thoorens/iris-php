<?php



namespace modules\main\messages;

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
 * This class will contains explanations for typical error (in French)
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class French extends \Iris\Translation\_Messages{
    
    public function noComment(){
        return '';
    }
    
    public function no_cont(){
        return $this->_format('titre|ligne1<br/>ligne2');
    }
    
    private function _format($message){
        if($message=='')
            return '';
        list($title,$content) = explode('|',$message);
        $text = "<h1>$title</h1>";
        $text .= $content;
        return $text;
    }
    
    public function controllerError(){
        $text = 'Contrôleur non touvé|';
        $text .= <<<CONTROLLER
Parmi les explications, on peut proposer <ul>
    <li>un contrôleur inexistant (erreur dans la ligne d'URL)</li>
    <li>un contrôleur mal placé (mauvais module)</li>
    <li>un nom incorrect ou un namespace incorrect</li>

</ul>
CONTROLLER;
        return $this->_format($text);
    }
    
    public function __call($name, $arguments) {
        return $this->_format("$name|Ce message n'a pas encore de description");
    }

}

?>
