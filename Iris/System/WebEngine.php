<?php

namespace Iris\System;

use Iris\Forms\_FormFactory as I_FF;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * Tries to identificate the browser engine used by the client of the web server
 * 
 * Some parts of the code were inspired by the Browser class  by
 * Chris Schuld (http://chrisschuld.com/)
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class WebEngine {

    use \Iris\Engine\tSingleton;
    /*
      Prince
      Robin – for The Bat!
      Servo – developed by Mozilla and Samsung, written in Rust[2]
      Tkhtml – for hv3

      Links2 launched with -g flag. ChangeLog
     */

    const ENGINE_UNKNOWN = "unknow engine";
    const VERSION_UNKNOWN = "unknow version";
    /** Put in _FormFactory
      const GECKO_ENGINE = "Gecko";
      const WEBKIT_ENGINE = "WebKit";
      const EDGEHTML_ENGINE = "EdgeHTML";
     */

    /** To be added
      const BLINK_ENGINE = "blink";       // – for Google Chrome, Opera version 15+,[1] Sleipnir version 5+, and Maxthon version 4.2+
      const DILLO_ENGINE = "dillo";       // – for Dillo
      const GOANA_ENGINE = "Goana";       // – for Pale Moon, FossaMail
      const KHTML_ENGINE = "KHTML";       // – for Konqueror (last versions use Webkit
      const MARTHA_ENGINE = "MARTHA";     // (layout engine) – for RealObjects
      const NETFRONT_ENGINE = "NetFront"; // – for Access NetFront
      const NETSURF_ENGINE = "NetSurf";   // – for NetSurf
      const PRINCE_ENGINE = "Prince";
      const ROBIN_ENGINE = "Robin";       // – for The Bat!
      const SERVO_ENGINE = "Servo";       // – developed by Mozilla and Samsung, written in Rust[2]
      const TKHTML_ENGINE = "Tkhtml";     // – for hv3
      const LINKS2_ENGINE = "Links2";     // launched with -g flag. ChangeLog
     */
    const NOHTML5 = "IncompatibleHTML5_Browser";
    const NONEVERSION = "";
    const FULLHTML5 = "FullHTML5_Browser";
    const ALLVERSION = "";

    /**
     *
     * @var type 
     */
    protected $_agent;

    /**
     *
     * @var type 
     */
    protected $_is_mobile;

    /**
     *
     * @var type 
     */
    protected $_is_tablet;

    /**
     *
     * @var type 
     */
    protected $_is_robot;

    /**
     *
     * @var type 
     */
    protected $_engine;

    /**
     *
     * @var type 
     */
    protected $_version;
    static $TypeRules;

    protected function __construct() {
        $compatibility = \Iris\SysConfig\Settings::$ForceHTMLCompatibility;
        if ($compatibility === 0) {
            $this->reset();
            $this->_determine();
        }
        else {
            $this->_engine = $compatibility > 0 ? self::FULLHTML5 : self::NOHTML5;
            $this->_version = $compatibility > 0 ? self::ALLVERSION : self::NONEVERSION;
        }
    }

    /**
     * Reset all properties
     */
    public function reset() {
        $this->_agent = \Iris\Engine\Superglobal::GetServer('HTTP_USER_AGENT', '');
        $this->_engine = self::ENGINE_UNKNOWN;
        $this->_version = self::VERSION_UNKNOWN;
        self::DefineRules();
//   unused extensions        
//        $this->_is_mobile = \FALSE;
//        $this->_is_tablet = \FALSE;
//        $this->_is_robot = \FALSE;
//        $this->_platform = self::PLATFORM_UNKNOWN;
//        $this->_os = self::OPERATING_SYSTEM_UNKNOWN;
//        $this->_is_aol = \FALSE;
//        $this->_browser_name = self::BROWSER_UNKNOWN;
//        $this->_is_facebook = \FALSE;
//        $this->_aol_version = self::VERSION_UNKNOWN;
    }

    /**
     * Returns the name of the browser engine
     * 
     * @return string
     */
    public function getEngineName() {
        return $this->_engine;
    }

    /**
     * Returns the version number of the browser engine
     * 
     * @return string
     */
    public function getVersion() {
        return $this->_version;
    }

    /*
     * ---------------------------------------------
     * Some unused extensions
     * ---------------------------------------------
     */

//    public function is_mobile() {
//        return $this->_is_mobile;
//    }
//    public function is_tablet() {
//        return $this->_is_tablet;
//    }
//    public function is_robot() {
//        return $this->_is_robot;
//    }

    /**
     * Determines the used browser engine
     * 
     * @todo Put the test in good order
     */
    protected function _determine() {
        $this->_testWebKit() ||
                $this->_testGecko() ||
                $this->_testEdge() /* || Still not tested engines
          $this->_testBlink() ||
          $this->_testDillo() ||
          $this->_testGoana() ||
          $this->_testKHtml() ||
          $this->_testLinks2() ||
          $this->_testMartha() ||
          $this->_testNetFront() ||
          $this->_testNetSurf() ||
          $this->_testPrince() ||
          $this->_testRobin() ||
          $this->_testServo() ||
          $this->_testTlHtml() */
        ;
        print($this->_engine . ' - ' . $this->_version);
    }

    /**
     * Executes a test for a provided engine name
     * 
     * @param string $engineName
     * @return boolean
     */
    protected function _testEngine($engineName) {
        $value = \FALSE;
        $pos = stripos($this->_agent, $engineName);
        if ($pos !== \FALSE) {
            $this->_engine = $engineName;
            $aresult = explode('/', stristr($this->_agent, $engineName));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->_version = $aversion[0];
            }
            $value = \TRUE;
        }
        return $value;
    }

    /**
     * Test if "webkit" is present in the agent string
     * 
     * @return boolean
     */
    protected function _testWebKit() {
        return ($this->_testEngine(\Iris\Forms\_FormFactory::WEBKIT_ENGINE));
    }

    /**
     * Test if "gecko" is present in the agent string
     * 
     * @return boolean
     */
    protected function _testGecko() {
        return ($this->_testEngine(\Iris\Forms\_FormFactory::GECKO_ENGINE));
    }

    /**
     * Test if "edgehtml" is present in the agent string
     * 
     * @return boolean
     */
    protected function _testEdge() {
        return ($this->_testEngine(\Iris\Forms\_FormFactory::EDGEHTML_ENGINE));
    }

    public static function DefineRules() {
        self::$TypeRules = [
            'date' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => '537.36'
            ],
            'email' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => ''
            ],
            'time' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => ''
            ],
            'datetime' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => ''
            ],
            'datetime-local' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => '537.36'
            ],
            'week' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => ''
            ],
            'url' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => ''
            ],
            'tel' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => ''
            ],
            'search' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => ''
            ],
            'number' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => ''
            ],
            'month' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => ''
            ],
            'color' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => ''
            ],
            'range' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => '534.33'
            ],
            'week' => [
                I_FF::GECKO_ENGINE => '',
                I_FF::EDGEHTML_ENGINE => '',
                I_FF::WEBKIT_ENGINE => ''
            ],
        ];
    }

    /*
     * -------------------------------------------------------------------------
     * List of form input type compatibility 
     * -------------------------------------------------------------------------
     */

//    const FORM_TYPES = [
//        'a' => [
//            FM::G
//            self::GECKO_ENGINE => '',
//            self::EDGEHTML_ENGINE => '',
//            self::WEBKIT_ENGINE => '',
//        ],
//    ]

    /* -------------------------------------------------------------------------
     * 
     * Some special engines to be added later ?
     * 
     * -------------------------------------------------------------------------
     */


    /**
     * Test if "blink" is present in the agent string
     * 
     * @return boolean
     */
//    protected function _testBlink() {
//        return ($this->_testEngine(\Iris\Forms\_FormFactory::BLINK_ENGINE));
//    }

    /**
     * Test if "dillo" is present in the agent string
     * 
     * @return boolean
     */
//    protected function _testDillo() {
//        return ($this->_testEngine(\Iris\Forms\_FormFactory::DILLO_ENGINE));
//    }

    /**
     * Test if "goana" is present in the agent string
     * 
     * @return boolean
     */
//    protected function _testGoana() {
//        return ($this->_testEngine(\Iris\Forms\_FormFactory::GOANA_ENGINE));
//    }

    /**
     * Test if "khtml" is present in the agent string
     * 
     * @return boolean
     */
//    protected function _testKHtml() {
//        return ($this->_testEngine(\Iris\Forms\_FormFactory::KHTML_ENGINE));
//    }

    /**
     * Test if "links2" is present in the agent string
     * 
     * @return boolean
     */
//    protected function _testLinks2() {
//        return ($this->_testEngine(\Iris\Forms\_FormFactory::LINKS2_ENGINE));
//    }

    /**
     * Test if "martha" is present in the agent string
     * 
     * @return boolean
     */
//    protected function _testMartha() {
//        return ($this->_testEngine(\Iris\Forms\_FormFactory::MARTHA_ENGINE));
//    }

    /**
     * Test if "netfront" is present in the agent string
     * 
     * @return boolean
     */
//    protected function _testNetFront() {
//        return ($this->_testEngine(\Iris\Forms\_FormFactory::NETFRONT_ENGINE));
//    }

    /**
     * Test if "netfront" is present in the agent string
     * 
     * @return boolean
     */
//    protected function _testNetSurf() {
//        return ($this->_testEngine(\Iris\Forms\_FormFactory::NETSURF_ENGINE));
//    }
    /**
     * Test if "prince" is present in the agent string
     * 
     * @return boolean
     */
//    protected function _testPrince() {
//        return ($this->_testEngine(\Iris\Forms\_FormFactory::PRINCE_ENGINE));
//    }

    /**
     * Test if "robin" is present in the agent string
     * 
     * @return boolean
     */
//    protected function _testRobin() {
//        return ($this->_testEngine(\Iris\Forms\_FormFactory::ROBIN_ENGINE));
//    }

    /**
     * Test if "servo" is present in the agent string
     * 
     * @return boolean
     */
//    protected function _testServo() {
//        return ($this->_testEngine(\Iris\Forms\_FormFactory::SERVO_ENGINE));
//    }

    /**
     * Test if "tlhtml" is present in the agent string
     * 
     * @return boolean
     */
//    protected function _testTlHtml() {
//        return ($this->_testEngine(\Iris\Forms\_FormFactory::TLHTML_ENGINE));
//    }
}
