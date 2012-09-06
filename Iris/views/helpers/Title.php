<?php

namespace Iris\views\helpers;

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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */

/**
 * Gives facilities to manage the <title> of a page.
 *
 */
final class Title extends _ViewHelper {

    /**
     * Indicates that this helper can't be used in islet nor partial
     * 
     * @var boolean
     */
    protected static $_NotAfterHead = \TRUE;
    protected static $_Singleton = TRUE;
    private $_mainTitle = "IRIS-PHP application";
    private $_subTitle = NULL;



    public function help($mainTitle=NULL) {
        if (!is_null($mainTitle)) {
            $this->mainTitle($mainTitle);
        }
        return $this;
    }

    public function render($tag=FALSE) {
        $text = $this->_mainTitle;
        if (!is_null($this->_subTitle)) {
            $text .= " - $this->_subTitle";
        }
        if ($tag) {
            return "<title>$text</title>\n";
        }
        return $text;
    }

    public function mainTitle($mainTitle) {
        $this->_mainTitle = $mainTitle;
    }

    public function subTitle($subTitle) {
        $this->_subTitle = $subTitle;
    }

    public function head() {
        $title = $this->render();
        return "<title>$title</title>\n";
    }

}

?>
