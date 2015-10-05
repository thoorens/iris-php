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
 * A subhelper permitting to display an image (with link if necessary)
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Image extends ImageLink {

    protected static $_ParameterList = [ 'source', 'alt', 'tooltip', 'imageFolder', 'class', 'id'];
    
    protected static $_Type = self::IMAGE;
    
    function __construct($args) {
        parent::__construct($args);
        $this->setNoLink(\TRUE);
    }

    
}
