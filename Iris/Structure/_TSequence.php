<?php

namespace Iris\Structure;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017
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
 */
abstract class _TSequence extends \Iris\DB\_Entity {

    // a dummy value for the sequence length 
    const MAX = 1000000;

    /**
     *
     * @var int the interval to distinguish the page limit
     */
    protected static $Interval = 100000;

    /**
     * 
     * @param int $Interval
     * @return _TSequence
     */
    public static function SetInterval($Interval) {
        self::$Interval = $Interval;
    }

    /**
     * Gets an object from the sequence corresponding to the URL
     * 
     * @return \Iris\DB\Object 
     */
    public static function GetItem($url) {
        $tSequence = static::GetEntity(97);
        $tSequence->where('URL=', $url);
        return $tSequence->fetchRow();
    }

    /**
     * Returns the url of the first element of the sequence
     * 
     * @param boolean $inArray specifies if the result must be an array
     * @return string/array the URL or an array for a Button helper 
     */
    public static function GetFirst($array) {
        return self::_GetURL('<<', \NULL, $array);
    }

    /**
     * Returns the url of the previous element of the sequence
     * 
     * @param boolean $inArray specifies if the result must be an array
     * @return string/array the URL or an array for a Button helper 
     */
    public static function GetPrevious($url, $array) {
        return self::_GetURL('<', $url, $array);
    }

    /**
     * Returns the url of the next element of the sequence
     *
     * @param boolean $inArray specifies if the result must be an array
     * @return string/array the URL or an array for a Button helper 
     */
    public static function GetNext($url, $array) {
        return self::_GetURL('>', $url, $array);
    }

    /**
     * Returns the url of the last element of the sequence
     * 
     * @param boolean $inArray specifies if the result must be an array
     * @return string/array the URL or an array for a Button helper 
     */
    public static function GetLast($array) {
        return self::_GetURL('>>', \NULL, $array);
    }

    /**
     * Seeks an URL curresponding to the beginning or end or to a refering URL
     * 
     * @param int $position may be FIRST, LAST, NEXT or PREVIOUS
     * @param string $url The refering URL for NEXT or PREVIOUS
     * @param boolean $inArray specifies if the result must be an array
     * @return string/array the URL or an array for a Button helper 
     */
    private static function _GetURL($position, $url = NULL, $inArray = \FALSE) {
        $tSequence = static::GetEntity();
        switch ($position) {
            // not used in IrisWB
            case '<<':
                $tSequence->order('id');
                $sequence = $tSequence->fetchRow();
                break;
            case '<';
                $current = $tSequence->fetchRow('URL=', $url);
                if (is_null($current)) {
                    $sequence = \NULL;
                }
                else {
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
                    $sequence = $tSequence->fetchRow();
                }
                break;
            case '>':
                $current = $tSequence->fetchRow('URL=', $url);
                if (is_null($current)) {
                    $sequence = \NULL;
                }
                else {
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
                    $sequence = $tSequence->fetchRow();
                }
                break;
            // not used in IrisWB
            case '>>':
                $tSequence->order('id DESC');
                $sequence = $tSequence->fetchRow();
                break;
        }

        //$sequence = $tSequence->fetchRow();
        if ($inArray) {
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

    /**
     * Retrieves all sequences and stores them in groups of arrays indexed by the group name
     * 
     * @param mixed $page indicates the page number (null if all pages)
     * 
     * @return array[]
     */
    public static function GetStructuredSequence($page) {
        $sectionList = [];
        $sectionDetails = [];
        $oldSectionId = -1;
        $tSequence = static::GetEntity();
        if ($page !== \NULL) {
            $interval = static::$Interval;
            $initialId = $page * $interval;
            $finalId = ($page + 1) * $interval;
            $tSequence->where('id>=', $initialId);
            $tSequence->where('id<', $finalId);
        }
        $tSequence->order('id');
        $sequence = $tSequence->fetchAll();
        foreach ($sequence as $item) {
            $section_id = $item->section_id;
            if ($oldSectionId != $section_id) {
                if (count($sectionDetails)) {
                    $sectionList[$groupName] = $sectionDetails;
                }
                $sectionDetails = [];
                $oldSectionId = $section_id;
                $section = $item->_at_section_id;
                if ($section_id != 0) {
                    $groupName = $section->GroupName;
                }
            }
            if ($section_id == 0) {
                $sectionList[$item->URL] = $item->Description;
            }
            else {
                $sectionDetails[$item->URL] = $item->Description;
            }
        }
        return $sectionList;
    }

    /**
     * Retrieves all sequences and stores them in groups of arrays indexed by the group name
     * 
     * @param type $limit
     * @param type $exclude
     * @deprecated since version number
     * @return type
     */
    public static function GetStructuredSequence_old($limit = self::MAX, $exclude = \TRUE) {
        $sectionList = [];
        $sectionDetails = [];
        $oldSectionId = -1;
        $tSequence = static::GetEntity();
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
            if ($oldSectionId != $section_id) {
                if (count($sectionDetails)) {
                    $sectionList[$groupName] = $sectionDetails;
                }
                $sectionDetails = [];
                $oldSectionId = $section_id;
                $section = $item->_at_section_id;
                if ($section_id != 0) {
                    $groupName = $section->GroupName;
                }
            }
            if ($section_id == 0) {
                $sectionList[$item->URL] = $item->Description;
            }
            else {
                $sectionDetails[$item->URL] = $item->Description;
            }
        }
        return $sectionList;
    }

}
