<?php
namespace Iris\Structure;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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

