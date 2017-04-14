<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * An easy way load css files or script fragment. Each fragment/file receives
 * a index name so it won't loaded twice.
 * Two way to use it:<ul>
 * <li> ->styleLoader('mystyle.css');
 * <li> ->styleLoader('test',"#test {background-color:white}");
 * </ul>
 */
abstract class _Loader extends _ViewHelper {

    use tLoaderRegister;

    /**
     * It is critical this help be a singleton
     * @var boolean
     */
    protected static $_Singleton = \TRUE;

    /**
     * a list of internal style/code definitions
     * @var string[] 
     */
    protected $_text = [];

    /**
     * A list of files to load
     * @var string[] 
     */
    protected $_files = [];

    /**
     * The extension of the file (to be overwritten in subclasses
     * 
     * @var string
     */
    protected $_extension = \NULL;

    /**
     * Add a new script/style or a new script/css file
     * 
     * @param string $label script name 
     * @param string $content content of the script or file name (ends in .js or .css)
     */
    protected function help($label = NULL, $content = NULL) {

        if (is_null($content)) {
            $content = $label;
        }
        $size = strlen($this->_extension);
        if (isset($content) and \substr($content, -$size) === $this->_extension) {
            // This error will occur only during development
            if (isset($this->_files[$label]) and \Iris\Engine\Mode::IsDevelopment()) {
                if ($this->_extension == 'js') {
                    throw new \Iris\Exceptions\ViewException("Doublons in javascript files under index $label");
                }
                else {
                    throw new \Iris\Exceptions\ViewException("Doublons in css files under index $label");
                }
            }
            $this->_files[$label] = $content;
        }
        else {
//            if ($content === $label) {
//                if ($this->_extension == 'js') {
//                    throw new \Iris\Exceptions\ViewException("Explicit JS code needs a label " . substr($content, 0, 20));
//                }
//                else{
//                    throw new \Iris\Exceptions\ViewException("Explicit style code needs a label : " . substr($content, 0, 50));
//                }
//            }
//            if (isset($this->_text[$label]) and \Iris\Engine\Mode::IsDevelopment()) {
//                if ($this->_extension == 'js') {
//                    throw new \Iris\Exceptions\ViewException('Doublons in javascript text');
//                }
//                else{
//                    throw new \Iris\Exceptions\ViewException('Doublons in css text');
//                }
//            }
            $this->_text[$label] = $content;
                    //die('help in _Loader');
        }
    }

    public function load($name, $content = NULL) {
        return $this->help($name, $content);
    }

    /**
     * A file name is prefixed by '/css/', or '/js/' except if <ul>
     * <li>it is absolute (beginning by /)
     * <li>it has a bang operator (!)
     * <li>it begins with http
     * </ul>
     * @param string $file
     * @return string
     */
    protected function _URL($file) {
        if ($file[1] !== '!' and $file[0] !== '/' and strpos($file, 'http') !== 0) {
            $file = sprintf("/%s/%s", $this->_extension, $file);
        }
        return $file;
    }

}
