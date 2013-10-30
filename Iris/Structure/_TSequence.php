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
 * Data for the sequence of tests. It may be used for any sequence using
 * a database.
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _TSequence extends \Iris\DB\_Entity {

    const FIRST = 1;
    const PREVIOUS = 2;
    const NEXT = 3;
    const LAST = 4;

    /**
     * Gets an object from the sequence corresponding to the URL
     * 
     * @return \Iris\DB\Object 
     */
    public static function GetItem($url) {
        //$tSequence = \Iris\DB\_EntityManager::GetEntity($entityName)
        $tSequence = static::GetEntity();
        $tSequence->where('URL=', $url);
        return $tSequence->fetchRow();
    }

    /**
     * Returns the first
     * 
     * @return string/array the URL or an array for a Button helper 
     */
    public static function GetFirst($array) {
        return self::_GetURL(self::FIRST, \NULL, $array);
    }

    /**
     * 
     * @return string/array the URL or an array for a Button helper 
     */
    public static function GetPrevious($url, $array) {
        return self::_GetURL(self::PREVIOUS, $url, $array);
    }

    /**
     *
     * @return string/array the URL or an array for a Button helper 
     */
    public static function GetNext($url, $array) {
        return self::_GetURL(self::NEXT, $url, $array);
    }

    /**
     * Returns the last
     * 
     * @return string/array the URL or an array for a Button helper 
     */
    public static function GetLast($array) {
        return self::_GetURL(self::LAST, \NULL, $array);
    }

    /**
     *
     * @param int $position
     * @param string $url
     * @param boolean $array
     * @return string/array the URL or an array for a Button helper 
     */
    private static function _GetURL($position, $url = NULL, $array = \FALSE) {
        $tSequence = static::GetEntity();
        switch ($position) {
            case self::FIRST:
                $tSequence->order('id');
                break;
            case self::PREVIOUS;
                $current = $tSequence->fetchRow('URL=', $url);
                if (is_null($current)) {
                    return \Iris\Subhelpers\Link::$NoLink;
                }
                $tSequence->order('id DESC');
                $tSequence->where('id<', $current->id);
                $label = 'Previous';
                break;
            case self::NEXT:
                $current = $tSequence->fetchRow('URL=', $url);
                if (is_null($current)) {
                    return \Iris\Subhelpers\Link::$NoLink;
                }
                $tSequence->order('id');
                $tSequence->where('id>', $current->id);
                $label = 'Next';
                break;
            case self::LAST:
                $tSequence->order('id DESC');
                break;
        }

        $sequence = $tSequence->fetchRow();
        if ($array) {
            if (is_null($sequence)) {
                $value = \Iris\Subhelpers\Link::$NoLink;
            }
            else {
                $value = array($label, $sequence->URL, $sequence->Description);
            }
        }
        else {
            $value = $sequence->URL;
        }
        return $value;
    }

    public static function GetStructuredSequence() {
        $level1 = array();
        $level2 = array();
        $oldSection = -1;
        //$tSections = \Iris\DB\DataBrowser\AutoEntity::EntityBuilder('sections');
        $tSequence = static::GetEntity();
        $tSequence->order('id');
        $sequence = $tSequence->fetchAll();
        foreach ($sequence as $item) {
            $section_id = $item->section_id;
            if ($oldSection != $section_id) {
                if (count($level2)) {
                    $level1[$groupName] = $level2;
                }
                $level2 = array();
                $oldSection = $section_id;
                $section = $item->_at_section_id;
                if ($section_id != 0) {
                    $groupName = $section->GroupName;
                }
            }
            if ($section_id == 0) {
                $level1[$item->URL] = $item->Description;
            }
            else {
                $level2[$item->URL] = $item->Description;
            }
        }
        return $level1;
    }

}

