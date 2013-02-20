<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tutorial\controllers\helpers;

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
 * Description of FrInstall
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
abstract class _Content extends \Iris\controllers\helpers\_ControllerHelper {

    const VIEW = 1;
    const IMAGE = 2;
    const TEXT = 3;
    const TITLE = 0;
    const TYPE = 1;
    const PAGECONTENT = 2;
    const TEXTCONTENT = 3;
    const DURATION = 4;
    const AUDIO = 5;

    protected $_pages = array();

    /**
     * 
     * @param type $num
     * @param int $max
     * @return \Tutorial\Content\Item
     */
    public function help($num, $ajax = \TRUE) {
        if ($this->COMMON_max == 0)
            $this->COMMON_max = 10;
        $this->toView('title', 'Installation sous Linux');
        $item = new \Tutorial\Content\Item();
        $item->setId($num);
        $page = $this->_pages[$num];
        $this->__frameNumber = $num;
        switch ($page[self::TYPE]) {
            case self::IMAGE:
                $pageContent = \Iris\views\helpers\_ViewHelper::HelperCall('image', $page[self::PAGECONTENT]);
                if (isset($page[self::TEXTCONTENT])) {
                    $textContent = $this->rendernow($page[self::TEXTCONTENT], \FALSE);
                }
                else {
                    $textContent = "Pas d'explications définies";
                }
                break;
            case self::VIEW:
                $pageContent = $this->rendernow($page[self::PAGECONTENT], \FALSE);
                $textContent = $this->rendernow($page[self::PAGECONTENT].'_t', \FALSE);
                break;
            case self::TEXT:
                die('Not defined');
                break;
        }
        $item->setPage($pageContent);
        $item->setText($textContent);
        $item->setDuration($page[self::DURATION]);
        $item->setId = $num + 1;
        $item->setTitle($page[self::TITLE]);
        $item->setAudio($page[self::AUDIO]);
        return $item;
    }

}

