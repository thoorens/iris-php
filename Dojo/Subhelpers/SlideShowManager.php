<?php

namespace Dojo\Subhelpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This subhelper realizes a slide show using Dojo.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class SlideShowManager extends \Iris\Subhelpers\_SlideShowManager {
    /**
     * The slideshow is based on an array
     */

    const ARRAYMODE = 1;
    /**
     * The slideshow is based on a JSON file
     */
    const FILEMODE = 2;
    /**
     * The slideshow is based on a a Flickr account
     */
    const FLICKRMODE = 3;
    /**
     * The slide show is based on a Picassa account
     */
    const PICASSAMODE = 4;

    /**
     * Defines the way of accessing images
     * 
     * @var int 
     */
    private $_mode;

    /**
     * The Dojo manager
     * 
     * @var \Dojo\Manager
     */
    private $_manager;

    /**
     * The data-dojo-props collection
     * 
     * @var string[] 
     */
    private $_dojoProps = [
        "slideshowInterval" => 4,
        "imageWidth" => 300,
        "imageHeight" => 300,
        "autoLoad" => \NULL, // def true
        //"noLink" => true,
        "autoStart" => \NULL, // def false
        "loop" => \NULL, // def false,
            //"fixedHeight" => true,
    ];

    /**
     *
     * @var type 
     */
    private $_source = "/images/images.json";

    /**
     *
     * @var type 
     */
    protected $_imageCount = 1;

    /**
     *
     * @var type 
     */
    protected $_AltImage = array();

    /**
     * The data in JSON format corresponding to the images to display
     * @var string
     */
    private $_data = "";

    /**
     * During construct, marks CSS and dojo resources for loading
     */
    protected function __construct() {
        $this->_manager = \Dojo\Manager::GetInstance();
        $source = $this->_manager->getURL();
        $this->_manager->addStyle("$source/dojox/image/resources/image.css");
        $this->setAltImage('nopicture.jpg', 'Image not available', NULL, '/!documents/file/images');
    }

    /**
     * 
     * @param type $start
     * @return type
     */
    public function render($start = \NULL) {
        if (!\Iris\Users\Session::JavascriptEnabled()) {
            list($name, $alt, $title, $dir, $class) = $this->_altImage;
            return $this->callViewHelper('image', $name, $alt, $title, $dir, $class);
        }
        if ($start == \TRUE) {
            $this->setAutoPlay();
        }
        foreach ($this->_dojoProps as $index => $value) {
            if (!is_null($value)) {
                if (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                }
                $propItems[] = "$index:$value";
            }
        }
        $props = implode(',', $propItems);
        $html = '';
        switch ($this->_mode) {
            case self::ARRAYMODE:
                $this->_makeArrayBubble();
                break;
            case self::FILEMODE:
                $storeId = 'imageItemStore' . $this->_id;
                $this->_makeFileBubble($storeId);
                break;
            case self::FLICKRMODE:
                $this->_makeFlickrBubble();
                break;
            case self::PICASSAMODE:
                \Iris\Engine\Debug::ErrorBoxDie('Picassa mode is still unsupported');
                break;
            default:
                \Iris\Engine\Debug::ErrorBoxDie('Dojo SlideShow need a source to display images');
        }
        $html .= <<<HTML
    <div id="$this->_id" data-dojo-type="dojox.image.SlideShow" data-dojo-props="$props">
    </div>
HTML;
        return $html;
    }

    public function setData($items) {
        $this->_mode = self::ARRAYMODE;
        $this->_data = json_encode($items);
        return $this;
    }

    /**
     * Sets the interval between two images (in seconds)
     * 
     * @param int $interval
     * @return \Dojo\views\helpers\SlideShow for fluent interface
     */
    public function setInterval($interval) {
        $this->_dojoProps['slideshowInterval'] = $interval;
        return $this;
    }

    /**
     * Sets the width of the slide show display zone
     * 
     * @param int $width
     * @return \Dojo\views\helpers\SlideShow for fluent interface
     */
    public function setWidth($width) {
        $this->_dojoProps["imageWidth"] = $width;
        return $this;
    }

    /**
     * Sets the height of the slide show display zone
     * 
     * @param int $height
     * @return \Dojo\views\helpers\SlideShow for fluent interface
     */
    public function setHeight($height) {
        $this->_dojoProps["imageHeight"] = $height;
        return $this;
    }

    /**
     * Sets the file containing the JSON data for the images
     * 
     * @param string $fileName The file name
     * @return \Dojo\views\helpers\SlideShow for fluent interface
     */
    public function setFile($fileName) {
        $this->_mode = self::FILEMODE;
        $this->_source = $fileName;
        return $this;
    }

    public function defineFlickr($user, $apiKey) {
        $this->_mode = self::FLICKRMODE;
        $this->_source = [$user, $apiKey];
        return $this;
    }

    /**
     * 
     * @param type $imageCount
     * @return \Dojo\views\helpers\SlideShow for fluent interface
     */
    public function setImageCount($imageCount) {
        $this->_imageCount = $imageCount;
        return $this;
    }

    /**
     * Sets the autoload feature (preloads images).
     * True by default.
     * 
     * @param boolean $value
     * @return SlideShowManage for fluent interface
     */
    public function setAutoload($value = \TRUE) {
        $this->_dojoProps['autoLoad'] = $value;
        return $this;
    }

    /**
     * Sets the autostart feature (preloads images).
     * False by default.
     * 
     * @param boolean $value
     * @return SlideShowManager for fluent interface
     */
    public function setAutoStart($value = \TRUE) {
        $this->_dojoProps['autoStart'] = $value;
        return $this;
    }

    private function _makeFileBubble() {
        $id = $this->_id;
        $bubble = \Dojo\Engine\Bubble::GetBubble("File_$id");
        $bubble->addModule("dojo/data/ItemFileReadStore", 'ItemFileStore');
        $bubble->addModule("dojo/ready", 'ready');
        $bubble->addModule('dijit/registry', 'registry');
        $bubble->addModule("dojox/image/SlideShow");
        $count = $this->_imageCount;
        $url = $this->_source;
        $bubble->defFunction(<<<FUNC
                
        ready(function(){  
            var itemFileStore = new ItemFileStore({url:'$url'});
            var req = { query: {}, count:$count };
            var atr = {
                        imageThumbAttr: "thumb",
                        imageLargeAttr: "large"
            };  
            //Initialize the first SlideShow with an ItemFileReadStore
            registry.byId('$id').setDataStore(itemFileStore,req, atr);
        });
                
FUNC
        );
    }

    private function _makeFlickrBubble() {
        $id = $this->_id;
        $bubble = \Dojo\Engine\Bubble::GetBubble("Flickr_$id");
        $bubble->addModule("dojox/data/FlickrRestStore", 'FlickrRestStore');
        $bubble->addModule("dojo/ready", 'ready');
        $bubble->addModule('dijit/registry', 'registry');
        $bubble->addModule("dojox/image/SlideShow");
        list($userId, $apiKey) = $this->_source;
        $count = $this->_imageCount;
        $bubble->defFunction(<<<FUNC
                
    var flickrRestStore = new FlickrRestStore();
    var req = {
        query: {
            userid: '$userId',
            apikey: "$apiKey"
        },
        count: $count
    };
    ready(function() {
        registry.byId('$id').setDataStore(flickrRestStore, req);
    });
FUNC
        );
    }

      
    
    
    private function _makeArrayBubble() {
        $id = $this->_id;
        $bubble = \Dojo\Engine\Bubble::GetBubble("Array_$id");
        $bubble->addModule("dojox/data/KeyValueStore", 'KeyValueStore');
        $bubble->addModule("dojo/ready", 'ready');
        $bubble->addModule('dijit/registry', 'registry');
        $bubble->addModule("dojox/image/SlideShow");
        $count = $this->_imageCount;
        $list = $this->_data;
        $bubble->defFunction(<<<FUNC
                
        ready(function(){
            var container = $list;
            var keyValueStore = new KeyValueStore({data:container});    
            var req = { 
            };        
            //Initialize the first SlideShow with an KeyValueStore
            registry.byId('$id').setDataStore(keyValueStore,req);
        });
                
FUNC
        );
    }

}

