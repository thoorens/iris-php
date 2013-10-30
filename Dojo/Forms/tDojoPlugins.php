<?php

namespace Dojo\Forms;

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
 * @copyright 2011-2013 Jacques THOORENS
 */

/**
 * 
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

