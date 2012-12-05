<?php

namespace iris\views\helpers;

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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * @todo : verify the utility of this and suppress it in all layouts
 */

/**
 * Add a md5 signature of the page
 */
class Signature extends \Iris\views\helpers\_ViewHelper {

    protected static $_Singleton = \TRUE;
    private static $_Name = '';

    public function help($name = \NULL) {
        if(!is_null($name)){
        self::$_Name = $name;
        }
        return $this;
    }

    
    
    public function display() {
        if($this->_OldMd5()==''){
            return '';
        }
        $name = static::$_Name;
        return "<b>MD5 finger print<b> : <span id=\"$name\"></span>";
    }

    public function saveButton(){
        if($this->_OldMd5()==''){
            return '';
        }
        $name = self::$_Name;
        $button = '<button class="norm"  title="%s" onclick="" id="%s">%s</button>';
        return sprintf($button,'Save the present MD5 signature',"b_$name",'Save MD5');
    }
    
    
    public static function computeMD5($text) {
        $componentId = self::$_Name;
        if ($componentId == '') {
            $javascriptCode = '';
        }
        else {
            $md5 = md5($text);
            $url = self::_URL();
            $link = 'javascript:location.href=\''."/manager/md5/save$url/$md5"."'";
            if ($md5 == self::_OldMd5()) {
                $class = 'iris_md5_ok';
            }
            else {
                $class = 'iris_md5_bad';
            }
            $javascriptCode = <<< JS
<script>
    md5fp = dojo.byId('$componentId');
    md5fp.innerHTML = '<i>$md5</i>';
    dojo.addClass('$componentId','$class');
    dojo.attr( "b_$componentId",'onclick',"$link");  
</script>

JS;
        }
        return $javascriptCode;
    }

    private static function _OldMd5() {
        $tSequences = new \models\TSequence();
        $screen = $tSequences->fetchRow('URL=', self::_URL());
        if(is_null($screen)){
            return '';
        }
        return $screen->Md5;
    }

    
    private static function _URL(){
        $response = \Iris\Engine\Response::GetCurrentInstance();
        $module = $response->getModuleName();
        $controller = $response->getControllerName();
        $action = $response->getActionName();
        return "/$module/$controller/$action";

    }
}

