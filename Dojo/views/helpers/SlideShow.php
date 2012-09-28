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
 * This helpers realizes a slide show.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class SlideShow extends _DojoHelper {

    protected static $_Singleton = TRUE;
    protected $_interval = 4;
    protected $_width = 300;
    protected $_height = 300;
    protected $_file = "/images/images.json";
    protected $_imageCount = 1;
    protected $_AltImage = array();

    public function help() {
        return $this;
    }

    protected function _init() {
        $source = $this->_manager->getSource();
        $this->_manager->addStyle("$source/dojox/image/resources/image.css");
        $this->_manager->addRequisite("dojox.image.SlideShow");
        $this->_manager->addRequisite("dojo.data.ItemFileReadStore");
        $this->_requiredDone = TRUE;
        $this->setAltImage('IrisImage.jpg','Image not available',NULL,'/iris_aspect/images');
    }

    public function slides($start=TRUE) {
        if (!\Iris\Users\Session::JavascriptEnabled()) {
            list($name,$alt,$title,$dir,$class) = $this->_AltImage;
            return $this->_view->image($name,$alt,$title,$dir,$class);
        }
        if (!$start) {
            $doStart = '';
        }
        else {
            $doStart = 'this.toggleSlideShow(true);';
        }
        return <<<HTML
<div jsId="imageItemStore" dojoType="dojo.data.ItemFileReadStore" url="$this->_file"></div>
    <div id="diaporama" dojoType="dojox.image.SlideShow" imageWidth="$this->_width"
                imageHeight="$this->_height" slideshowInterval="$this->_interval">
        <script type="dojo/connect">
            this.setDataStore(
            imageItemStore,
            { query: {}, count:$this->_imageCount },
            {imageLargeAttr: "large",linkAttr : "link"}
            );
            $doStart
        </script>
    </div>
HTML;
    }

    public function setInterval($interval) {
        $this->_interval = $interval;
        return $this;
    }

    public function setWidth($width) {
        $this->_width = $width;
        return $this;
    }

    public function setHeight($height) {
        $this->_height = $height;
        return $this;
    }

    public function setFile($file) {
        $this->_file = $file;
        return $this;
    }

    public function setImageCount($imageCount) {
        $this->_imageCount = $imageCount;
        return $this;
    }

    /**
     * Setter to choose the image that will be displayed
     * in case JS is not enabled
     * 
     * @param int $noJSImageNumber 
     */
    public function setAltImage($imageName, $alt= NULL, $title = NULL, $imageDir ='',$class='') {
        $this->_AltImage = array($imageName,$alt,$title,$imageDir,$class);
        return $this;
    }

}

