<?php

namespace Dojo\Forms;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * @todo Write the description  of the trait
 */
trait tDojoPlugins {

    use tDojoDijit;

    /**
     * Adds an attribute for a plugin (to be inserted in dojo-props)
     * 
     * @param string $name
     * @param string $plugin
     */
    public function _addPlugin($name, $plugin = \NULL) {
        if (is_null($plugin))
            $plugin = $name;
        $this->_dijitAttributes['plugins'][$name] = $plugin;
    }

    /**
     * Adds an attribute for an extra plugin (to be inserted in dojo-props)
     * 
     * @param string $name
     * @param string $plugin
     */
    public function _addExtraPlugin($name, $plugin) {
        if (is_null($plugin))
            $plugin = $name;
        $this->_dijitAttributes['extraplugins'][$name] = $plugin;
    }

}

