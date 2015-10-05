<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/*
 * Add a md5 signature of the page
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * @todo : verify the utility of this and suppress it in all layouts
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
     * @var string[]
     */
    private static $_Fields = array();

    /**
     * The name of the class responsible for the generation of the
     * javascript code for the placement of the good values by the
     * subhelper Head through the static method ComputeMD5. By default,
     * the method is in this class, but it may be changed to a static
     * method situated elsewhere.
     * 
     * @var string
     */
    public static $JSManager = '\\Iris\\views\\helpers\\Signature';

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
    public static function SetSpanId($id) {
        self::$_SpanID = $id;
    }

    /**
     * Creates the HTML code with a span which is to contain the MD5 value
     * 
     * @return string HTML code
     */
    public function display() {
        $name = static::$_SpanID;
        if (property_exists($this->_view, 'specialScreen') and $this->_view->specialScreen === \FALSE) {
            return "<b>MD5 finger print</b> : <span class=\"iris_md5_unused\">unused</span>";
        }
        else{
            return "<b>MD5 finger print</b> : <span id=\"$name\"></span>";
        }
    }

    /**
     * Creates the HTML code for a button to save the current MD5 (if necessary)
     * 
     * @return string
     */
    public function saveButton() {
        $name = self::$_SpanID;
        if ($this->_view->specialScreen !== \FALSE) {
            return '';
        }
        /* @var $button \Iris\Subhelpers\Button */
        $button = $this->callViewHelper('button', ['Save MD5', \NULL, 'Save the present MD5 signature'])
                ->setId("b_$name");
        return $button->__toString();
    }

    /**
     * If necessary, compute the md5 code for the present page and place it in the
     * a span zone.
     * 
     * @param string $text the entire HTML code for the page
     * @return string
     */
    public static function ComputeMD5(&$text) {

        $componentId = self::$_SpanID;
        if ($componentId == '') {
            $javascriptCode = '';
        }
        else {
            $md5 = md5($text);
            $url = \Iris\Engine\Response::GetDefaultInstance()->getURL();
            $link = 'javascript:location.href=\'' . "/manager/md5/save$url/$md5" . "'";
            if ($md5 == self::_OldMd5()) {
                $class = 'iris_md5_ok';
                $md5Status = "correct";
            }
            else {
                $class = 'iris_md5_bad';
                $md5Status = "not correct";
            }
            $javascripManager = self::$JSManager;
            $javascriptCode = $javascripManager::md5JS($componentId, $class, $md5Status, $link);
        }
        return $javascriptCode;
    }

    /**
     * Gets the old md5 code stored in the database
     * 
     * @return string
     */
    private static function _OldMd5() {
        // in case of no model, MD5 is supposed bad
        if (is_null(self::$_Model)) {
            return '';
        }
        $model = self::$_Model;
        $tSequences = $model::GetEntity();
        $urlField = self::$_Fields[self::URL];
        $screen = $tSequences->fetchRow("$urlField=", \Iris\Engine\Response::GetDefaultInstance()->getURL());
        if (is_null($screen)) {
            return '';
        }
        $md5Field = self::$_Fields[self::MD5];
        return $screen->$md5Field;
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

    /**
     * Returns the field names for URL and md5 as an array of 2 elements
     * 
     * @return array
     */
    public static function GetModelFields() {
        return self::$_Fields;
    }

    /**
     * This code is responsible for the generation of the javascript code
     * in ComputeMD5
     * 
     * @param string $componentId
     * @param string $class
     * @param string $md5
     * @param string $link
     * @return string
     */
    public static function md5JS($componentId, $class, $md5, $link) {
        $signature = \Dojo\Manager::Debug('Generated by \Iris\views\helpers\Signature');
        return <<< JS
<script>
$signature  
require(["dojo/dom-class", "dojo/dom", "dojo/on", "dojo/domReady!"],
function(domClass, dom, on){
    dom.byId("$componentId").innerHTML = "<i>$md5</i>";
    domClass.add("$componentId", "$class");    
    on(dom.byId("b_$componentId"), "click", function(){
        $link;
  });
});
</script>

JS;
    }

}
