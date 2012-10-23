<?php

namespace Dojo\views\helpers;

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
 */

/**
 * This helper permits to use Tab and Accordeon containers of Dojo. If javascript
 * is not available on the client, it simulate the tab with buttons and interaction
 * with the server. Another option is to display all the items, with <h5> title in front
 * of them.
 *

 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Container extends _DojoHelper {
    use \Dojo\Translation\tTranslatable;

    
    /**
     * The type of the container (Accordion, Border, Split or Tab)
     * @var string 
     */
    protected $_type;
    
    /**
     * True if the client has javascript capabilities (autodeterminated)
     * @var boolean 
     */
    private $_JS = TRUE;
    
    /**
     * 
     * @var array
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

    /**
     * This methods initializes the container, gives it a name seen by the view.
     * 
     * @param string $varName the name of the container seen by the view containing it
     * @param string $type the type of the container (by default Tab)
     * @return Container 
     */
    public function help($varName=NULL, $type='Tab') {
        $this->_JS = \Iris\Users\Session::JavascriptEnabled();
        if ($this->_JS) {
            $this->_type = $type;
            $this->_manager->addRequisite('dijit.layout.ContentPane')
                    ->addRequisite("dijit.layout.{$type}Container");
        }
        if (!is_null($varName)) {
            $this->_view->$varName = $this;
        }
        $this->_name = $varName;
        return $this;
    }

    /**
     * Creates a <div> with Dojo attributes or a serie of buttons if javascript
     * is not installed in the client.
     * 
     * @param string $name the name internally used by javascript
     * @param array $items pairs of key and label to identify and display the container parts
     * @param int $heigth Height of the container
     * @param int $width Width of the container
     * @return string 
     */
    public function divMaster($height=NULL, $width=NULL) {
        $this->setDim($height, $width);
        // for javascript, returns a div with Dojo attributes
        if ($this->_JS) {
            return '<div id="' . $this->_name . '" design="headline" dijitParams="top" style="height:' . $this->_height .
                    'px;width:' . $this->_width . 'px" dojoType="dijit.layout.' . $this->_type . 'Container"'.
                    ' data-dojo-id="'. $this->_name . '" >';
        }
        else {
            // with no javascript, returns a serie of buttons (unless altNodisplay is false)
            if ($this->_altNodisplay) {
                $html = '<div>';
                foreach ($this->_items as $key => $item) {
                    if($key==$this->_default){
                        $item = "<i>$item</i>";
                    }
                    $html .= $this->_view->button($item, $this->_url . $key,$item,'tabs');
                }
                return $html;
            }
        }
    }

    /**
     * For clarity, the closing tag &lt;/div> corresponding to divMaster
     * 
     * @return string
     */
    public function endMaster() {
        return "\n</div>\n";
    }

    /**
     * Initialise the items index and labels
     * 
     * @param type $items
     * @return Container (fluent interface)
     */
    public function setItems($items){
        $this->_items = $items;
        return $this;
    }
    
    /**
     * Add an item and label
     * 
     * @param string $name
     * @param string $label 
     * @return Container (fluent interface)
     */
    public function addItem($name,$label){
        $this->_items[$name] = $label;
        return $this;
    }
    
    /**
     * The dojo attribute to realize a tab item (or html if no javascript)
     * 
     * @param string $itemIndex the item index of the container
     * @param string $selectedItem the selected item (if no display is choosed)
     * @return string 
     */
    public function item($itemIndex, $selectedItem=NULL) {
        if($selectedItem==NULL){
            $selectedItem = $this->_default;
        }
        $label = $this->_items[$itemIndex];
        // no javascript mode
        if (!$this->_JS) {
            // none of the items are displayed except the selected one
            if ($this->_altNodisplay) {
                if ($selectedItem != $itemIndex)
                    return 'style = "display:none"';
                else
                    return '';
            }else {
                // if all items are to be seen, the label is displayed as
                // a title. The div
                return "><h5>$label</h5><br";
            }
        }
        // javascript mode: dojo attributes are inserted in the div
        else {
            if ($selectedItem == $itemIndex) {
                $selected = ' selected = "true"';
            }
            else {
                $selected = '';
            }
            return 'id="' . $itemIndex . '" title="' . $label . '" dojoType="dijit.layout.ContentPane"' . $selected;
        }
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
    public function setDim($height=NULL, $width=NULL){
        if($height!=NULL){
            $this->_height = $height;
        }
        if($width!=NULL){
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
    public function setDefault($name){
        $this->_default = $name;
        return $this;
    }
    
}

