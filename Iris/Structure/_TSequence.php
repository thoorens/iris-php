<?php
namespace Iris\Structure;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016
 *  Jacques THOORENS
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
    const MAX = 1000000;

    /**
     * Gets an object from the sequence corresponding to the URL
     * 
     * @return \Iris\DB\Object 
     */
    public static function GetItem($url) {
        //$tSequence = \Iris\DB\_EntityManager::GetEntity($entityName)
        //$tSequence = static::GetEntity(97);
        $tSequence = static::GetEntity(97);
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
                    return \Iris\Subhelpers\Link::GetNoLinkLabel();
                }
                $tSequence->order('id DESC');
                $tSequence->where('id<', $current->id);
                if ($current->id > static::MAX) {
                    $tSequence->where('id>', self::MAX);
                }
                else {
                    $tSequence->where('id<', self::MAX);
                }
                $tSequence->_AND_();
                $label = 'Previous';
                break;
            case self::NEXT:
                $current = $tSequence->fetchRow('URL=', $url);
                if (is_null($current)) {
                    return \Iris\Subhelpers\Link::GetNoLinkLabel();
                }
                $tSequence->order('id');
                $tSequence->where('id>', $current->id);
                if ($current->id > static::MAX) {
                    $tSequence->where('id>', self::MAX);
                }
                else {
                    $tSequence->where('id<', self::MAX);
                }
                $tSequence->_AND_();
                $label = 'Next';
                break;
            case self::LAST:
                $tSequence->order('id DESC');
                break;
        }

        $sequence = $tSequence->fetchRow();
        if ($array) {
            if (is_null($sequence)) {
                $value = \Iris\Subhelpers\Link::GetNoLinkLabel();
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

    public static function GetStructuredSequence($limit = self::MAX, $exclude = \TRUE) {
        $level1 = array();
        $level2 = array();
        $oldSection = -1;
        //$tSections = \Iris\DB\DataBrowser\AutoEntity::EntityBuilder('sections');
        $tSequence = static::GetEntity();
        // Does not treat
        if ($exclude) {
            $tSequence->where('id<', $limit);
        }
        else {
            $tSequence->where('id>=', $limit);
        }
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
