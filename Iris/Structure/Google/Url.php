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
 * 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Url {

    private $_base;
    private $_loc;
    private $_lastmod;
    private $_changefreq;
    private $_priority;

    function __construct($base, $protocol = 'http') {
        $this->_base = "$protocol://$base";
        $this->_loc = '';
        $this->_lastmod = new \Iris\Time\Date();
        $this->_changefreq = "never";
        $this->_priority = 0.5;
    }

    public function render() {
        $url = "$this->_base/$this->_loc";
        $xml[] = "<loc>$url</loc>";
        $xml[] = "<lastmod>$this->_lastmod</lastmod>";
        $xml[] = "<changefreq>$this->_changefreq</changefreq>";
        $xml[] = "<priority>$this->_priority</priority>";
        return implode("\n", $xml);
    }

}

/*
<?xml version="1.0" encoding="UTF-8"?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

   <url>

      <loc>http://www.example.com/</loc>

      <lastmod>2005-01-01</lastmod>

      <changefreq>monthly</changefreq>

      <priority>0.8</priority>

   </url>

   <url>

      <loc>http://www.example.com/catalog?item=12&amp;desc=vacation_hawaii</loc>

      <changefreq>weekly</changefreq>

   </url>

   <url>

      <loc>http://www.example.com/catalog?item=73&amp;desc=vacation_new_zealand</loc>

      <lastmod>2004-12-23</lastmod>

      <changefreq>weekly</changefreq>

   </url>

   <url>

      <loc>http://www.example.com/catalog?item=74&amp;desc=vacation_newfoundland</loc>

      <lastmod>2004-12-23T18:00:15+00:00</lastmod>

      <priority>0.3</priority>

   </url>

   <url>

      <loc>http://www.example.com/catalog?item=83&amp;desc=vacation_usa</loc>
      
    <lastmod>2004-11-23</lastmod>

   </url>

</urlset>
  

}


