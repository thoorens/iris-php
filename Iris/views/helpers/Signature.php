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

    const URL = 0;
    const MD5 = 1;

    /**
     * The view helper is a singleton
     * 
     * @var boolean
     */
    protected static $_Singleton = \TRUE;
    /**
     * The name of the span containing the md5 signature
     * @var string
     */
    private static $_SpanID = 'iris_md5';
    /**
     * The name of the model class used to store the md5 value, 
     * if NULL there is no md5 management, TSequence in WB
     * 
     * @var string
     */
    private static $_Model = \NULL;
    /**
     * An array containing the names of the fields used to make the mapping
     * URL - md5 (URL and Md5 in WB)
     * @var array
     */
    private static $_Fields = array();

    /**
     * Access the singleton 
     * @param string $id (the optional id of the span field
     * @return \iris\views\helpers\Signature
     */
    public function help($id = \NULL) {
        return $this;
    }

    /**
     * Change the span id
     * @param string $id (the optional id of the span field
     */
    public static function SetSpanId($id){
        self::$_SpanID = $id;
    }
    
    /**
     * Creates the HTML code with a span which is to contain the MD5 value
     * 
     * @return string HTML code
     */
    public function display() {
        $name = static::$_SpanID;
        return "<b>MD5 finger print</b> : <span id=\"$name\"></span>";
    }

    /**
     * Creates the HTML code for a button to save the current MD5
     * 
     * @return string
     */
    public function saveButton() {
        $name = self::$_SpanID;
        return $this->_view->button()->addAttribute('id',"b_$name")
                ->autorender('button','Save MD5',"b_$name",'Save the present MD5 signature' );
    }

    /**
     * If necessary, compute the md5 code for the present page and place it in the
     * a span zone.
     * 
     * @param string $text the entire HTML code for the page
     * @return string
     */
    public static function computeMD5(&$text) {
        if (is_null(self::$_Model)) {
            return '';
        }
        $componentId = self::$_SpanID;
        if ($componentId == '') {
            $javascriptCode = '';
        }
        else {
            $md5 = md5($text);
            $url = self::_URL();
            $link = 'javascript:location.href=\'' . "/manager/md5/save$url/$md5" . "'";
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

    /**
     * Gets the old md5 code stored in the database
     * 
     * @return string
     */
    private static function _OldMd5() {
        $model = "\\models\\".self::$_Model;
        $tSequences = new $model();
        $urlField = self::$_Fields[self::URL];
        $screen = $tSequences->fetchRow("$urlField=", self::_URL());
        //iris_debug($screen);
        if (is_null($screen)) {
            return '';
        }
        $md5Field = self::$_Fields[self::MD5];
        return $screen->$md5Field;
    }

    /**
     * Gets the present URL
     * 
     * @return string
     */
    private static function _URL() {
        $response = \Iris\Engine\Response::GetCurrentInstance();
        $module = $response->getModuleName();
        $controller = $response->getControllerName();
        $action = $response->getActionName();
        return "/$module/$controller/$action";
    }

    /**
     * Sets the model and URK and md5 field names in the model
     * 
     * @param string $model
     * @param string $URLField
     * @param string $md5Field
     */
    public static function SetModel($model, $URLField, $md5Field) {
        self::$_Model = $model;
        self::$_Fields[self::URL] = $URLField;
        self::$_Fields[self::MD5] = $md5Field;
    }

}

