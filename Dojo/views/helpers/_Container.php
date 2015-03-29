<?php

namespace Dojo\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This helper permits to use various Dojo containers. If javascript
 * is not available on the client, it may simulate the tab with buttons and interaction
 * with the server. Another option is to display all the items, with <h5> title in front
 * of them.
 *

 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
abstract class _Container extends _DojoHelper {
    /**
     * Determine where to put navigation buttons
     */

    const NONE = 0;
    const BOTTOM = 1;
    const TOP = 2;

    use \Dojo\Translation\tTranslatable;

    // confirmation
    protected static $_Singleton = FALSE;

    /**
     * The type of the container (Accordion, Border, Split or Tab)
     * @var string 
     */
    protected $_type = '';
    protected static $_Type = '';

    /**
     * True if the client has javascript capabilities (autodeterminated)
     * @var boolean 
     */
    private $_JS = TRUE;

    /**
     * 
     * @var \Dojo\Engine\Item[]
     */
    protected $_items = array();

    /**
     * Determines if all tabs are displayed when javascript is not present
     * 
     * @var boolean
     */
    protected $_altNodisplay = TRUE;

    /**
     * The URL to the present page to redisplay it with another tab in
     * @var string
     */
    protected $_url;

    /**
     * The tab item to activate (with javascript or by showing it instead)
     * @var string 
     */
    protected $_default;

    /**
     * The name of the container as seen by the view and javascript
     * 
     * @var string 
     */
    protected $_name;

    /**
     * The container height
     * 
     * @var int
     */
    protected $_height = 650;

    /**
     * The container width
     * 
     * @var int
     */
    protected $_width = 650;
    protected $_specials = array();

    /**
     * This methods initializes the container, gives it a name seen by the view. If no
     * variable name is given, prepare to switch to programatic dijit expecting
     * other methods to be fired
     * 
     * @param string $varName the name of the container seen by the view containing it
     * @param string $type the type of the container (by default Tab)
     * @return static 
     */
    public function help($varName = NULL) {
        $this->_type = static::$_Type;
        if (is_null($varName)) {
            // no necessity to have a bubble for declarative uses
            \Dojo\Engine\Bubble::DropObject($this->_type);
        }
        else {
            $this->_JS = \Iris\Users\Session::JavascriptEnabled();
            if ($this->_JS) {
                $this->_createBubble();
            }
            $this->_view->$varName = $this;
            $this->_name = $varName;
        }
        return $this;
    }

    /**
     * Creates a <div> with Dojo attributes or a serie of buttons if javascript
     * is not installed in the client.
     * 
     * @param string $name the name internally used by javascript
     * @param string[] $items pairs of key and label to identify and display the container parts
     * @param int $heigth Height of the container
     * @param int $width Width of the container
     * @return string 
     */
    public function divMaster($height = NULL, $width = NULL) {
        $this->setDim($height, $width);
        $html = $this->_buttons(self::TOP);
        // for javascript, returns a div with Dojo attributes
        if ($this->_JS) {
            $html .= '<div id="' . $this->_name . '" style="height:' . $this->_height;
            $html .= 'px;width:' . $this->_width . 'px" data-dojo-type="dijit.layout.' . $this->_type . '"';
            $html .= $this->_specialAttributes();
            $html .= ' data-dojo-id="' . $this->_name . '" >';
        }
        else {
            // with no javascript, returns a serie of buttons (unless altNodisplay is false)
            if ($this->_altNodisplay) {
                $html .= '<div>';
                foreach ($this->_items as $key => $item) {
                    if ($key == $this->_default) {
                        $item = "<i>$item</i>";
                    }
                    $html .= $this->callViewHelper('button',$item, $this->_url . $key, $item, 'tabs');
                }
            }
        }
        return $html . "\n";
    }

    public function jsRender($objectId, $data) {
        $type = $this->_type;
        $objectId = "tc1-prog";
        $script = <<< SCRIPT
        <script>require(["dojo/ready", "dijit/layout/$type", "dijit/layout/ContentPane"], function(ready, $type, ContentPane){
    ready(function(){
    var tc = new $type({
    style: "height: 100%; width: 100%;"
}, "$objectId");
SCRIPT;
        $num = 0;
        foreach($data as $title=>$content){
            $content = str_replace("\n", '"+"', $content);
            $script .= <<< SCRIPT2
var cp$num = new ContentPane({
title: "$title",
content: "$content"
});
tc.addChild(cp$num);
SCRIPT2;
            $num++;
        }
        $script .= <<< SCRIPTEND
tc.startup();
});
});</script>
SCRIPTEND;

        return $script;
    }

    /**
     * For clarity, the closing tag &lt;/div> corresponding to divMaster
     * 
     * @return string
     */
    public function endMaster() {
        $html = "\n</div><!-- end " . $this->_name . "-->\n";
        $html .= $this->_buttons(self::BOTTOM);
        return $html;
    }

    /**
     * Initialise the items index and labels
     * 
     * @param type $items
     * @return Container (fluent interface)
     */
    public function setItems($items) {
        foreach ($items as $key => $item) {
            $this->addItem($key, $item);
        }
        return $this;
    }

    public function getItems(){
        return $this->_items;
    }
        
    /**
     * Gets an 
     * @param type $name
     * @return \Dojo\Engine\Item
     */
    public function getItem($name) {
        if(!isset($this->_items[$name])){
            $this->addItem($name,$name);
        }
        return $this->_items[$name];
    }

    /**
     * Add an item and label
     * 
     * @staticvar int $number a defaut number for auto naming
     * @param string $name
     * @param string $label 
     * @return Container (fluent interface)
     */
    public function addItem($name, $label) {
        $item = new \Dojo\Engine\Item($name, $label);
        if ($this->_default != '' and $name == $this->_default) {
            $item->setDefault();
        }
        $this->_items[$name] = $item;
        return $this;
    }

    /**
     * The dojo attribute to realize a tab item (or html if no javascript)
     * 
     * @param string $itemIndex the item index of the container
     * @return string 
     */
    public function item($itemIndex) {
        $item = $this->getItem($itemIndex);
        return $item->render($this->_JS);
    }

    
    public function linkedItem($itemIndex, $url) {
        $item = $this->getItem($itemIndex);
        $item->setLink($url);
        return $item->render($this->_JS);
    }

    /**
     * If set to true (default), the display of the different items is managed by the server.
     * The Url has to be set to create the links
     * 
     * @param boolean $altNodisplay If FALSE, all tabs are displayed
     * @return Container (fluent interface) 
     */
    public function setAltNodisplay($altNodisplay) {
        $this->_altNodisplay = $altNodisplay;
        return $this;
    }

    /**
     * If _altNodisplay is true, the url has to be set to create specialized links.
     * The URL must be [/module]/controller/action/[parameters/]. Then ending / is required.
     * 
     * @param string $url The URL to which the current page 
     * @return Container (fluent interface) 
     */
    public function setUrl($url) {
        $this->_url = $url;
        return $this;
    }

    /**
     * Set the two dimensions of the container (instead 650x650 default)
     * 
     * @param int $height Container heigth in pixels
     * @param int $width Container width in pixels
     * @return Container (fluent interface) 
     */
    public function setDim($height = NULL, $width = NULL) {
        if ($height != NULL) {
            $this->_height = $height;
        }
        if ($width != NULL) {
            $this->_width = $width;
        }
        return $this;
    }

    /**
     * Setter for the default tab index
     * 
     * @param string $name The default tab index
     * @return Container (fluent interface) 
     */
    public function setDefault($name) {
        if ($this->_default != '' and isset($this->_items[$this->_default])) {
            $this->getItem($this->_default)->setDefault(\FALSE);
        }
        $this->_default = $name;
        if (isset($this->_items[$this->_default])) {
            $this->getItem($name)->setDefault();
        }
        return $this;
    }

    /**
     * Some containers need special attributes. This call back can manage 
     * them
     * 
     * @param type $param0
     * @return string
     */
    protected function _specialAttributes() {
        $special = $this->_specials;
        if (count($special)) {
            return sprintf(' data-dojo-props="%s" ', implode(',', $special));
        }
        else {
            return '';
        }
    }

    /**
     * Some containers need navigations buttons. This call back can manage 
     * them
     * 
     * @param int $position
     * @return string
     */
    protected function _buttons($position) {
        return '';
    }

    public function _createBubble() {
        \Dojo\Engine\Bubble::getBubble($this->_type)
                ->addModule("dijit/layout/$this->_type")
                ->addModule("dojo/parser")
                ->addModule("dijit/layout/ContentPane")
                ->addModule("dijit/layout/LinkPane");
    }

}
