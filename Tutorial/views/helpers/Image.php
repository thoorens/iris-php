<?php

namespace Tutorial\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * A helper for image display in a precise position
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * @deprecated since version 2014
 */
class Image extends \Iris\views\helpers\Image {

    protected static $_Singleton = \TRUE;
    protected $_defaultClass = 'tuto_absolute';

    /**
     * Creates an image tag with alt and title at a given position 
     * and with a label
     * 
     * @param string $id An id for the image
     * @param int $top Top position (in pixels)
     * @param int $left Left position (in pixels)
     * @param string $file Image file
     * @param string $alt Alt attribute (to display if image is missing)
     * @param string $title Title attribute (tooltip)
     * @param string $dir image directory (by default images)
     * @param string $class class name for CSS
     * @param string $attributes optional attributes or javascript
     * @return \Tutorial\views\helpers\Image (or string)
     */
    public function help($id = \NULL, $top = 0, $left = 0, $file = '', $alt = 'Image', $title = \NULL, $dir = \NULL, $class = '', $attributes = '') {
        if (is_null($id)) {
            return $this;
        }
        else
            return $this->_render($id, $top, $left, $file, $alt, $title, $dir, $class, $attributes);
    }

    

    protected function _render($id, $top, $left, $file, $alt = 'Image', $title = \NULL, $folder = \NULL, $class = '', $attributes = '') {
        $classAttribute = $class . ' ' . $this->_defaultClass;
        $attributes .= " id=\"$id\"";
        //$image = sprintf('<img id="%s" src="%s%s" title="%s" alt="%s" class="tuto_absolute %s" %s/>' . "\n", $id, $dir, $file, $title, $alt, $class, $attributes);
        $image = $this->_renderImage($file, $alt, $title, $folder, $classAttribute, $attributes);
        $style = sprintf("<style>img#%s\n{top: %dpx; left: %dpx}\n</style>\n", $id, $top, $left);
        return $image . $style;
    }

    
    
}

