<?php

namespace Iris\Structure;

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
 */

/**
 * Manages
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class SiteMap {

    private $_version = '0.9';
    private $_urlSet = [];

    public function read($fileName){
        
    }
    
    public function generate() {
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $version = $this->_version;
        $xml[] = "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/$version\">";
        foreach ($this->_urlSet as $url) {
            $xml[] = $url->render();
        }
        $xml .= $this->_body();
        $xml[] = '</urlset>';
        return explode("\n", $xml);
    }

}

