<?php
namespace modules\dojo\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Examples of a Dojo slide show
 * 
 */
class slideshow extends _dojo {

    protected function _init() {
        // to be shure we use dojo library
        \Iris\SysConfig\Settings::$SlideShowManagerLibrary = '\\Dojo\\';
        $this->_setLayout('application');       
        $this->setViewScriptName('all');
    }

    /**
     * Makes a slide show with a static local file as a source
     */
    public function fileAction() {
        $this->_file('/images/slideshow.json', \TRUE);
    }

    /**
     * Makes a slide show with a dynamicly generated JSON file
     */
    public function ajaxAction() {
       $this->_file('/dojo/getdata/images', \FALSE); 
    }

    private function _file($fileName, $autostart) {
        /* @var $slideShow \Dojo\Subhelpers\SlideShowManager */
        $slideShow = $this->callViewHelper('slideShow');
        $slideShow->setWidth(603)
                ->setHeight(400)
                ->setAutoStart($autostart)
                // le fichier au format json est réalisé par un contrôleur
                ->setFile($fileName)
                ->setImageCount(5)
                ->setAltImage('/images/nopicture.jpg', "Error in loading picture");
        $this->__slideShow = $slideShow;
    }

    public function dataAction() {
        $images = $this->_exampleImages();
        /* @var $slideShow \Dojo\Subhelpers\SlideShowManager */
        $slideShow = $this->callViewHelper('slideShow');
        $slideShow->setWidth(603)
                ->setHeight(400)
                ->setAutoStart(\TRUE)
                ->setData($images)
                ->setImageCount(5)
                ->setAltImage('/images/nopicture.jpg', "Error in loading picture");
        $this->__slideShow = $slideShow;
    }

    /**
     * Creates an array containing data for getting 5 images
     * 
     * @param boolean $jsonFormat If true, converts the array to JSON
     * @return string[] a JSON string or an array
     */
    private function _exampleImages($jsonFormat = \TRUE) {
        $titles = ['Etretat (France)', 'Pastry (Alsace France)', 'Lama', 'Tramway (Lisboa)', 'Cabourg (France)'];
        for ($i = 1; $i < 6; $i++) {
            $images[] = [
                "large" => sprintf("/images/slideshow/image%02d.jpg", $i),
                "title" => $titles[$i - 1],
            ];
        }
        return $images;
    }
    
    public function testfAction() {
        \Iris\SysConfig\Settings::$MD5Signature = \FALSE;
        \Iris\SysConfig\Settings::$DisplayRuntimeDuration = \FALSE;
        $this->_setLayout(\NULL);
    }

    public function testflAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => "'<h1>dojo - slideshow - testfl</h1>'",
            'buttons' => 1 + 4,
            'logoName' => 'mainLogo'));
    }

    public function flickrAction() {
        /* @var $slideShow \Dojo\Subhelpers\SlideShowManager */
        $slideShow = $this->callViewHelper('slideShow');
        $slideShow->setWidth(604)
                ->setHeight(404)
                ->setAutoStart(\TRUE)
                ->defineFlickr('95173057@N07', "77b51b60f7f0bb0dbee39b12f750230f")
                ->setImageCount(4);
        $this->__slideShow = $slideShow;
    }

    

}
