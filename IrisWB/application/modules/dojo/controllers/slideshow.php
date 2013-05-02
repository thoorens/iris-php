<?php

namespace modules\dojo\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of animation
 * 
 * @author jacques
 * @license not defined
 */
class slideshow extends _dojo {

    protected function _init() {
        // to be shure we use dojo library
        \Iris\Subhelpers\_SlideShowManager::SetDefaultLibrary('\\Dojo\\');
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
        $images = $this->exampleImages();
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

    public function testfAction() {
        \Iris\SysConfig\Settings::DisableMD5Signature();
        \Iris\SysConfig\Settings::DisableDisplayRuntimeDuration();
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

    public function directAction() {
        $this->_nolayout();
        $this->setViewScriptName('direct');
    }

}
