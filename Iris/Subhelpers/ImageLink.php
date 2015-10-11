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
 * A subhelper permitting to display an image (with link if necessary)
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class ImageLink extends _SuperLink {
    
    protected static $_Type = self::IMAGELINK;

    protected static $_ParameterList = ['source', 'url', 'tooltip', 'alt', 'class', 'id'];
    
    protected static $_DefaultImageFolder;
    
    public static function __ClassInit() {
        static::$_DefaultImageFolder = \Iris\SysConfig\Settings::$ImageFolder;
    }

    /**
     *
     * @var _SuperLink
     */
    protected $_oldLink = \NULL;
    
    /**
     * Final render for the image
     * 
     * @param boolean $active By default use an active icon (may not active)
     * @return string
     */
    protected function _render() {
        $image = $this->_renderImage();
        $url = $this->_renderUrl();
        $operation = $this->_superlinkType ^ self::IMAGE;
        switch ($operation) {
            case self::LINK: 
                if($this->_oldLink === \NULL){
                    $link = new Link([$image, $this->getUrl(), $this->getTooltip()]);
                }
                else{
                    $link = $this->_oldLink->setLabel($image);
                }
                return $link->_render();
            case self::BUTTON:
                if($this->_oldLink === \NULL){
                    $link = new Button([$image, $this->getUrl(), $this->getTooltip()]);
                }
                else{
                    $link = $this->_oldLink->setLabel($image);
                }
                return $link->_render();
// in the case of an image or an image with empty URL
            default :
                return $image;
        }
//        $manager = \models\crud\CrudIconManager::GetInstance();
//        if ($this->_predefined) {
//            $dir = $manager->getSystemIconDir();
//        }
//        else {
//            $dir = $manager->getIconDir();
//        }
//        $name = $this->_operationName;
//        if (!$this->_active) {
//            $name = $name . "_des";
//            $toolTip = $manager->_('Operation not possible in context');
//            $html = \Iris\views\helpers\Link::HelperCall('image', ["$name.png", "Icone $name", $toolTip, $dir]);
//        }
//        else {
//            $help = $manager->makeTooltip($name);
//            //$ref = $manager->makeReference($this, $this->_urlParam);
//            $ref = '';
//            $linkParams = [$name, $ref, $help];
//            // we force the string conversion now (if not, problem if we have various icons made in sequence)
//            $html = \Iris\views\helpers\Link::HelperCall('link', $linkParams)->setInternalImageFolder($dir)->image("$name.png")->__toString();
//        }
//        iris_debug($html);
//        return $html;
    }

    public function _renderImage() {
        $source = $this->_renderSource();
        $alt = $this->_renderAlt();
        $title = $this->_renderTitle();
        $attributes = $this->_renderAttributes();
        $text = sprintf('<img %s %s %s %s />', $source, $title, $alt, $attributes);
        return $text;
    }

    /**
     * This method throws an exception if called with an image or image link object
     * 
     * @param string $imageName
     * @throws \Iris\Exceptions\BadLinkMethodException
     */
    public function image($imageName) {
        throw new \Iris\Exceptions\BadLinkMethodException('An image cannot be converted again to an image');
    }

    /**
     * 
     * @return string
     */
    public function getAlt() {
        return $this->alt;
    }

    /**
     * 
     * @param type $alt
     * @return \Iris\Subhelpers\_SuperLink
     */
    public function setAlt($alt) {
        $this->alt = $alt;
        return $this;
    }

    protected function _renderAlt() {
        $value = $this->getAlt();
        if ($this->_empty($value)) {
            $value = 'Image';
        }
        return $this->_renderHtmlAttribute('alt', $value);
    }

    public function getImageFolder() {
        return $this->imageFolder;
    }

    public function setImageFolder($imageFolder) {
        $this->imageFolder = $imageFolder;
        return $this;
    }

    public function setDefaultImageFolder($folder) {
        self::$_DefaultImageFolder = $folder;
    }

    public function resetDefaultImageFolder() {
        self::$_DefaultImageFolder = \Iris\SysConfig\Settings::$ImageFolder;
    }

    public function getSource() {
        return $this->source;
    }

    /**
     * 
     * @param string $source
     * @return \Iris\Subhelpers\ImageLink
     */
    public function setSource($source) {
        $this->source = $source;
        return $this;
    }

    protected function _renderSource() {
        $source = $this->getSource();
        if ($source[0] === '/') {
            $folder = '';
        }
        else {
            $folder = $this->getImageFolder();
            if ($this->_empty($folder)) {
                $folder = self::$_DefaultImageFolder;
            }
            $folder .= $folder[strlen($folder) - 1] !== '/' ? '/' : '';
        }
        return $this->_renderHtmlAttribute('src', $folder . $source);
    }

        public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    protected function _renderTitle() {
        $title = $this->getTitle();
        return $this->_renderHtmlAttribute('title', $title);
    }

    /**
     * 
     * @param string $folderName
     * @param string $fileName
     * @return string
     */
    protected function _getFullFileName($folderName, $fileName) {
        $extendedFN = $fileName . '  ';
        if ($this->_used($folderName)) {
//iris_debug($folderName);
            if (substr($folderName, -1) !== '/') {
                $folderName .= '/';
            }
        }
        elseif ($extendedFN[1] != '!') {
            $folderName = '/images/';
//iris_debug($folderName);
        }
        else {
            $folderName = '';
        }
        $fullName = $folderName . $fileName;
        iris_debug($fullName, \FALSE);
        return $fullName;
    }

    /**
     * Will confirm the superlink type to LINK, modifying if specified the 
     * URL of the link
     * 
     * @param string $url the ref part of the link
     * @return \Iris\Subhelpers\ImageLink
     */
    public function button($url = BLANKSTRING) {
        $this->setUrl($url);
        $this->_superlinkType = self::IMAGE + self::BUTTON;
        return $this;
    }

    /**
     * Will change the superlink type to BUTTON, modifying if specified the 
     * URL of the link
     * 
     * @param string $url 
     * @return \Iris\Subhelpers\ImageLink
     */
    public function link($url = BLANKSTRING) {
        $this->setUrl($url);
        $this->_superlinkType = self::IMAGE + self::LINK;
        return $this;
    }

}
