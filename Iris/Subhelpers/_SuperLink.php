<?php

namespace Iris\Subhelpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Description of tLink
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
abstract class _SuperLink extends _FlexibleSubhelper implements \Iris\Translation\iTranslatable {

    use \Iris\Translation\tTranslatable;

    /**
     * An array given the name and order of the constructor parameters
     * 
     * @var [string]
     */
    protected static $_ParameterList = ['label', 'url', 'tooltip', 'class', 'id'];

    
    const IMAGE = 1;
    const LINK = 2;
    const IMAGELINK = 3;
    const BUTTON = 4;
    const ICON = 8;

    /**
     * Indicates that the link part of the class will be a button or not
     * 
     * @var boolean
     */
    protected $_superlinkType = \NULL;
    protected static $_Type = \NULL;

    /**
     * Returns the special value to indicate a invisible link
     * 
     * @return string 
     */
    public final static function GetNoLinkLabel($isArray = \TRUE) {
        $noLink = \Iris\SysConfig\Settings::$NoLinkLabel;
        if ($isArray) {
            return [$noLink, BLANKSTRING, BLANKSTRING];
        }
        else {
            return $noLink;
        }
    }

    

    /**
     * There may exists additionnal attributes
     * 
     * @var string[]
     */
    protected $_attributes = [];

    /**
     * If true, the link is unnecessary (and not displayed)
     * 
     * @var boolean
     */
    protected $_nodisplay = \FALSE;

    /**
     * Creates the object and dispatches all the parameters to 
     * the _parameters protected datamember
     * 
     * @param array $args
     */
    public function __construct($args) {
        parent::__construct($args);
        $this->_superlinkType = static::$_Type;
        $this->setNoLink(\FALSE);
    }

    /**
     * Function automatically called at the final stage of the conversion to HTML
     * 
     * @return sting
     */
    public final function __toString() {
        // if nodisplay has been set or if there is a NOLINK label, returns a blank string
        if ($this->_nodisplay or $this->getLabel() === self::GetNoLinkLabel(\FALSE)) {
            return BLANKSTRING;
        }
        else {
            return $this->_render();
        }
    }

    
    /**
     * Render will be implemented in each subclass to make a correct render
     */
    abstract protected function _render();

    

    /**
     * Transforms a link or an image to a button
     * 
     * @param type $url
     * @return \Iris\Subhelpers\Button
     */
    public abstract function button($url = BLANKSTRING);

    /**
     * Transforms a button or an image to a link
     * 
     * @param type $url
     * @return \Iris\Subhelpers\Link
     */
    public abstract function link($url = BLANKSTRING);

    /**
     * Transforms the link in an image link
     * 
     * @param string $imageName
     * @return \Iris\Subhelpers\ImageLink
     */
    public function image($imageName) {
        $image = new ImageLink([]);
        $this->_copyData($image);
        // do not copy the image name if the label is !!!NONE!!!
        if ($this->getLabel() !== self::GetNoLinkLabel(\FALSE)) {
            $image->setSource($imageName);
        }
        $image->_superlinkType = $this->_superlinkType + self::IMAGE;
        $image->_oldLink = $this;
        return $image;
    }

    /**
     * When added, this method display the link only if the user has the right
     * to access the target.
     * WARNING : the URL must be in canonical form "/m/c/a/....."
     * 
     * @return \Iris\Subhelpers\_SuperLink
     */
    public function acl() {
        $acl = \Iris\Users\Acl::GetInstance();
        $aUrl = explode('/', $this->url . '////');
        list($module, $controller, $action) = array_slice($aUrl, 1, 3);
        $this->_nodisplay = !$acl->hasPrivilege("/$module/$controller", $action);
        return $this;
    }

//    public function getTarget() {
//        return $this->target;
//    }

    public function setTarget($target) {
        $this->addAttribute('target', $target);
        return $this;
    }

//    protected function _renderTarget() {
//        $title = $this->getTarget();
//        return $this->_renderHtmlAttribute('target', $title);
//    }

    public function goBlank() {
        return $this->setTarget('_blank');
    }

    public function goParent() {
        return $this->setTarget('_parent');
    }

    public function goSelf() {
        return $this->setTarget('_self');
    }

    public function goTop() {
        return $this->setTarget('_top');
    }

    /**
     * Renders an html attribute if a value is defined for it
     * 
     * @param string $name
     * @param mixed $value
     * @return string
     */
    protected function _renderHtmlAttribute($name, $value) {
        if ($value === BLANKSTRING) {
            $text = '';
        }
        else {
            $text = "$name=\"$value\"";
        }
        return $text;
    }

    /*
     * The accessors
     */

    /**
     * returns the optional classname attributed to the link
     * 
     * @return string
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * 
     * @param type $class
     * @return \Iris\Subhelpers\_SuperLink for fluent interface
     */
    public function setClass($class) {
        $this->class = $class;
        return $this;
    }

    protected function _renderClass() {
        $value = $this->getClass();
        return $this->_renderHtmlAttribute('class', $value);
    }

    /**
     * Returns the optional id of the link
     * 
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @param type $id
     * @return \Iris\Subhelpers\_SuperLink for fluent interface
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    protected function _renderId() {
        $value = $this->getId();
        return $this->_renderHtmlAttribute('id', $value);
    }

    /**
     * The label is mandatory
     * 
     * @return string
     */
    public function getLabel() {
        return $this->_($this->label);
    }

    /**
     * An alternative way to specify the  label of the link
     * 
     * @param string $name
     * @return \Iris\Subhelpers\_SuperLink for fluent interface
     */
    public function setLabel($name) {
        $this->label = $name;
        return $this;
    }

    public function getNoLink() {
        return $this->noLink;
    }

    /**
     * 
     * @param boolean $value
     * @return \Iris\Subhelpers\ImageLink for fluent interface
     */
    public function setNoLink($value) {
        $this->noLink = $value;
        return $this;
    }

    /**
     * This method is only usefull with Image, ImageLink and Icon subclasses.
     * The read accessor is not defined in this class.
     * 
     * @param string $value
     * @throws \Iris\Exceptions\BadLinkMethodException
     */
    public function setSource($value) {
        throw new \Iris\Exceptions\BadLinkMethodException('A button or link cannot have a source parameter');
    }

    /**
     * Returns the tooltip text of the link or image
     * 
     * @return string
     */
    public function getTooltip() {
        return $this->_($this->tooltip);
    }

    /**
     * 
     * @param type $tooltip
     * @return \Iris\Subhelpers\_SuperLink
     */
    public function setTooltip($tooltip) {
        $this->tooltip = $tooltip;
        return $this;
    }

    public function _renderTooltip() {
        $toolTip = $this->getTooltip();
        return $this->_renderHtmlAttribute('title', $toolTip);
    }

    /**
     * if the parameter is \TRUE and there is no usable URL, one must take the label
     * 
     * @return string
     */
    public function getUrl($force = \FALSE) {
        if ($this->_empty($this->url) and $force) {
            $url = $this->getLabel();
        }
        else {
            $url = $this->url;
        }
        return $this->_($url);
    }

    /**
     * A way to modify the URL of the link or image link
     * 
     * @param string $url
     * @return \Iris\Subhelpers\_SuperLink
     */
    public function setUrl($url) {
        if ($this->_used($url)) {
            $this->url = $url;
        }
        return $this;
    }

    protected function _renderUrl($force = \FALSE) {
        $value = $this->getUrl($force);
        return $this->_renderHtmlAttribute('href', $value);
    }

    /**
     * Test if a data is not a blank string
     * 
     * @param string $data
     * @return boolean
     */
    protected function _used($data) {
        return $data !== BLANKSTRING;
    }

    /**
     * Test if the data is a blank string or \NULL
     * 
     * @param string $data
     * @return boolean
     */
    protected function _empty($data) {
        return ($data === BLANKSTRING or $data === \NULL);
    }

    /**
     * Renders all attributes other than href/src
     * 
     * @return string
     */
    protected function _renderAttributes() {
        $attributes = '';
        $tooltip = $this->_renderTooltip();
        $class = $this->_renderClass();
        $id = $this->_renderId();
        foreach ($this->_attributes as $name => $value) {
            $attributes .= " $name=\"$value\"";
        }
        $text = sprintf(" %s %s %s %s", $id, $class, $tooltip, $attributes);
        return $text;
    }

    /**
     * Specifies an alternative folder for images
     *
     * @param string $imageFolder The image folder name
     * @return \Iris\Subhelpers\Link For fluent interface
     */
    public function setImageFolder($imageFolder) {
        $this->imageFolder = $imageFolder;
        return $this;
    }

    /**
     * Specifies an alternative temporary internal folder for images 
     * It is erased rigth after usage.
     * 
     * @param type $internalImageFolder
     * @return \Iris\Subhelpers\Link For fluent interface
     */
    public function setInternalImageFolder($internalImageFolder) {
        $this->_internalImageFolder = $internalImageFolder;
        return $this;
    }

    /**
     * Get an optional image folder (managing a possible temporary one)
     * 
     * @return string 
     */
    public function getImageFolder() {
        if ($this->_internalImageFolder !== \NULL) {
            $folder = $this->_internalImageFolder;
            $this->_internalImageFolder = \NULL;
        }
        else {
            $folder = $this->imageFolder;
        }
        return $folder;
    }

    /*
     * Explicit set accessors 
     */

    /**
     * Adds an attribute to the link
     *
     * @param string $name The attribute name
     * @param mixed $value The attribute value
     * @return \Iris\Subhelpers\Link for a fluent interface
     */
    public function addAttribute($name, $value) {
        $this->_attributes[$name] = $value;
        return $this;
    }

    
    /**
     * Copy all previous data from old object to the new
     * @param _SuperLink $newObject
     */
    public function _copyData($newObject) {
        $newObject->_parameters = $this->_parameters;
        $newObject->_attributes = $this->_attributes;
    }

}
