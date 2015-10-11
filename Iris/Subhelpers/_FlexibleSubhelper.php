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
 * A flexible subhelper is like a subhelpter with some peculiarities <ul>
 * <li>not a singleton
 * <li>needs an array as the constructor parameters
 * <li>analyses that array through a special mechanism
 * </ul>
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _FlexibleSubhelper {
    
    
    /**
     * An array given the name and order of the constructor parameters
     * 
     * @var [string]
     */
    protected static $_ParameterList = [];
    
    /**
     * All the constructor parameters and the magic data members are placed in
     * an array
     * 
     * @var array
     */
    protected $_parameters = [];
    
    /**
     * Creates the object and dispatches all the parameters to 
     * the _parameters protected datamember
     * 
     * @param array $args
     */
    public function __construct($args) {
        if(!is_array($args)){
            $args = func_get_args();
        }
        $this->_initParams($args);
    }
    
    /**
     * Takes the parameters given to the constructor and puts them
     * in the _parameters protected data member, using the key given
     * in the current class in $__ParameterList
     * 
     * @param array $args
     */
    protected function _initParams($args) {
        $num = count(static::$_ParameterList);
        $parameters = self::FlattenArray($args, $num, BLANKSTRING);
        $aparameters = array_combine(static::$_ParameterList, $parameters);
        foreach ($aparameters as $key => $value) {
            $this->_parameters[$key] = $value;
        }
    }
    
    
    /**
     * A magic reading accessor
     * It gives a blank string if the parameter does not exist
     * 
     * @param string $name
     * @return string
     */
    public function __get($name) {
        if (isset($this->_parameters[$name])) {
            return $this->_parameters[$name];
        }
        else {
            return BLANKSTRING;
        }
    }

    /**
     * A magic writing accessor
     * 
     * @param string $name
     * @param mixed $value
     * @return \Iris\Subhelpers\_SuperLink for fluent interface
     */
    public function __set($name, $value) {
        $this->_parameters[$name] = $value;
        return $this;
    }

    
    /**
     * Permits to cut the fluent interface in a script context
     *  
     * @return string
     */
    public function __toString() {
        return '';
    }
    
    
    /**
     * Normalizes the arguments : flatten the array and add necessary missing
     * elements (with null value)
     * Based on an idea from: http://stackoverflow.com/questions/1319903/how-to-flatten-a-multidimensional-array
     * I added the minimumSize argument and the loop to add the missing elements
     * 
     * use this to make a test
     * $args = array('foo', array('nobody', 'expects', array('another', 'level'), 'the', 'Spanish', 'Inquisition'), 'bar');
     * $data = Iris\Subhelpers\_FlexibleLink::FlattenArray($args);
     * iris_debug($data);
     *  
     * @param array $array
     * @param int $minimumSize
     * @param mixed $missingContent
     * @return array
     */
    public static function FlattenArray(array $array, /* added arguments */ $minimumSize = 0, $missingContent = \NULL) {
        $return = [];
        array_walk_recursive($array, function($a) use (&$return) {
            $return[] = $a;
        });
        // my addition
        while (count($return) < $minimumSize) {
            $return[] = $missingContent;
        }
        // end of addition
        return $return;
    }

}

