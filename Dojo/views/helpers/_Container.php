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
 */
abstract class _Container extends _DojoHelper {
use \Dojo\Translation\tTranslatable;

    /**
     * Determine where to put navigation buttons
     */
    const NONE = 0;
    const LEFT = 1;
    const RIGHT = 2;
    const BOTTOM = 3;
    const TOP = 4;
    
    // confirmation
    protected static $_Singleton = \FALSE;

    /**
     * Each subclass has its proper type specification
     * 
     * @var string
     */
    protected static $_Type = \NULL;

    /**
     * True if the client has javascript capabilities (autodeterminated)
     * @var boolean 
     */
    private $_JS = TRUE;

    /**
     * 
     * @var \Dojo\Engine\Item[]
     */
    protected $_items = [];

    /**
     * Determines if all tabs are displayed when javascript is not present
     * 
     * @var boolean
     */
    protected $_altNodisplay = \TRUE;

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
     * The position of the stack controller
     * 
     * @var int 
     */
    protected $_position = self::NONE;

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

    /**
     * Indicates that the container has splitter
     * 
     * @var boolean
     */
    protected $_splitter = \FALSE;

    /**
     *
     * @var array
     */
    protected $_specials = [];

    /**
     * This methods initializes the container, gives it a name seen by the view. If no
     * variable name is given, prepare to switch to programatic dijit expecting
     * other methods to be fired
     * 
     * @param string $varName the name of the container seen by the view containing it
     * @param string $type the type of the container (by default Tab)
     * @return static 
     */
    public function help($varName = \NULL) {
        if (is_null($varName)) {
            // no necessity to have a bubble for declarative uses
            \Dojo\Engine\Bubble::DropObject(static::$_Type);
        }
        else {
            $this->_JS = \Iris\Users\Session::JavascriptEnabled();
            if ($this->_JS) {
                //$this->_createBubble();
                \Dojo\Engine\Bubble::getBubble(static::$_Type)
                        ->addModule("dijit/layout/" . static::$_Type)
                        ->addModule("dojo/parser")
                        ->addModule("dijit/layout/ContentPane")
                        ->addModule("dijit/layout/LinkPane");
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
    public function divMaster($height = \NULL, $width = \NULL) {
        $this->setDim($height, $width);
        $html = $this->_renderController(self::TOP);
        // for javascript, returns a div with Dojo attributes
        if ($this->_JS) {
            $html .= '<div id="' . $this->_name . '" style="height:' . $this->_height;
            $html .= 'px;width:' . $this->_width . 'px" data-dojo-type="dijit.layout.' . static::$_Type . '"';
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
                    $html .= $this->callViewHelper('button', $item, $this->_url . $key, $item, 'tabs');
                }
            }
        }
        return $html . "\n";
    }

    /**
     * 
     * @param string $objectId
     * @param type $data
     * @return type
     */
    public function jsRender($objectId, $data) {
        $type = static::$_Type;
        $objectId = "tc1-prog";
        $script = <<< SCRIPT
        <script>require(["dojo/ready", "dijit/layout/$type", "dijit/layout/ContentPane"], function(ready, $type, ContentPane){
    ready(function(){
    var tc = new $type({
    style: "height: 100%; width: 100%;"
}, "$objectId");
SCRIPT;
        $num = 0;
        foreach ($data as $title => $content) {
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
        $html .= $this->_renderController(self::BOTTOM);
        return $html;
    }

    /**
     * Returns the stack controller at the position of the call the helper method.
     * This will display a controller with all types of containers
     *  
     * @return string
     */
    public function controller() {
        $containerName = $this->_name;
        $html = "\n<div data-dojo-type=\"dijit/layout/StackController\" data-dojo-props=\"containerId:'$containerName'\"></div>\n";
        return $html;
    }

    /**
     * Some containers need navigations buttons. This call back can manage 
     * them
     * 
     * @param int $position
     * @return string
     */
    protected function _renderController($position) {
        return '';
    }

    /**
     * Setter for the position  of the container controller buttons (not used
     * by all subclasses)
     * 
     * @param int $position
     * @throws \BadFunctionCallException
     */
    public function setPosition($position) {
        $className = get_called_class();
        throw new \BadFunctionCallException("No setPosition() defined in class $className");
    }

    /**
     * Initialise the items index and labels
     * 
     * @param type $items
     * @return Container (fluent interface)
     */
    public function setItems($items) {
        foreach ($items as $name => $label) {
            $this->addItem($name, $label);
        }
        return $this;
    }

    /**
     * Returns the items index and labels
     * 
     * @return array
     */
    public function getItems() {
        return $this->_items;
    }

    /**
     * Setter for the spliter option of the container
     * 
     * @param boolean $splitter
     * @return \Dojo\views\helpers\_Container
     */
    public function setSplitter($splitter) {
        $this->_splitter = $splitter;
        return $this;
    }

    /**
     * Returns true if the object has splitter
     * 
     * @return boolean 
     */
    public function getSplitter() {
        return $this->_splitter;
    }

    /**
     * Gets an 
     * @param type $name
     * @return \Dojo\Engine\Item
     */
    public function getItem($name) {
        //iris_debug($name);
        if (!isset($this->_items[$name])) {
            //iris_debug($name);
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
        return $item->itemRender($this->_JS, $this);
    }

    /**
     * 
     * @param type $itemIndex
     * @param type $url
     * @return type
     */
    public function linkedItem($itemIndex, $url) {
        $item = $this->getItem($itemIndex);
        $item->setLink($url);
        return $item->itemRender($this->_JS, $this);
    }

    /**
     * If set to true (default), the display of the different items is managed by the server
     * (in non javascript environment)
     * The Url has to be set to create the links
     * 
     * @param boolean $altNodisplay If FALSE, all tabs are displayed
     * @return _Container (fluent interface) 
     */
    public function setAltNodisplay($altNodisplay) {
        $this->_altNodisplay = $altNodisplay;
        return $this;
    }

    /**
     * Getter for the _altNoDisplay property
     * 
     * @return boolean
     */
    public function getAltNodisplay() {
        return $this->_altNodisplay;
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

    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    /**
     * Set the two dimensions of the container (instead of 650x650 default)
     * 
     * @param int $height Container heigth in pixels
     * @param int $width Container width in pixels
     * @return Container (fluent interface) 
     */
    public function setDim($height = \NULL, $width = \NULL) {
        if ($height != \NULL) {
            $this->_height = $height;
        }
        if ($width != \NULL) {
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

}
