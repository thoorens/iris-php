<?php

namespace Iris\controllers\helpers;

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
 * @copyright 2011-2013 Jacques THOORENS
 *
 * 
 */

/**
 * Stores the results of distinct tests in an array
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class StoreResults extends \Iris\controllers\helpers\_ControllerHelper {
    /**
     * Types of results
     */

    const TITLE = 0;
    const GOOD = 1;
    const BAD = 2;
    const COMMENT = 3;
    const PAUSE = 4;
    const SHIFT = 5;
    /**
     * Fields
     */
    const TYPE = 0;
    const MESSAGE = 1;
    const VALUE = 2;
    const STYLE = 3;

    /**
     * Message mode
     */
    const TEXT = 1;
    const CODE = 2;

    /**
     * The array containing all the results
     * 
     * @var array[]
     */
    private $_results = [];

    /**
     * Returns the only instance of the helper
     * 
     * @return \Iris\controllers\helpers\DbState
     */
    public function help() {
        return $this;
    }

    /**
     * Enters a new good result in the internal array: 
     * 
     * @param string $message an explanation of the test or the command present in the test
     * @param string $result a result or an error message (e.g. a message from a exception
     * @param int $mode TEXT or CODE explains the nature of the message
     * @return \Iris\controllers\helpers\StoreResults (fluent interface)
     */
    public function addGoodResult($message, $result, $mode = self::TEXT) {
        $this->_results[] = [
            self::TYPE => self::GOOD,
            self::MESSAGE => $message,
            self::VALUE => $result,
            self::STYLE => $mode,
        ];
        return $this;
    }

    /**
     * Enters a new bad result in the internal array: 
     * 
     * @param string $message an explanation of the test or the command present in the test
     * @param string $result a result or an error message (e.g. a message from a exception
     * @param int $mode TEXT or CODE explains the nature of the message
     * @return \Iris\controllers\helpers\StoreResults (fluent interface)
     */
    public function addBadResult($message, $result, $mode = self::TEXT) {
        $this->_results[] = [
            self::TYPE => self::BAD,
            self::MESSAGE => $message,
            self::VALUE => $result,
            self::STYLE => $mode,
        ];
        return $this;
    }

    /**
     * Enters a simple comment in the internal array: 
     * 
     * @param string $message an explanation of the test or the command present in the test
     * @param int $mode TEXT or CODE explains the nature of the message
     * @return \Iris\controllers\helpers\StoreResults (fluent interface)
     */
    public function addComment($message, $mode = self::TEXT) {
        $this->_results[] = [
            self::TYPE => self::COMMENT,
            self::MESSAGE => '',
            self::VALUE => $message,
            self::STYLE => $mode,
        ];
        return $this;
    }

    /**
     * Introduces a title in the list of results
     * 
     * @param string $content
     * @param int $level from 1 to 6 for a &lt;h#> tag
     * @return \Iris\controllers\helpers\StoreResults (fluent interface)
     */
    public function addTitle($content, $level = 3) {
        $this->_results[] = [
            self::TYPE => self::TITLE,
            self::MESSAGE => $content,
            self::VALUE => $level,
        ];
        return $this;
    }

    /**
     * Introduces a break (closes a table and is ready to open a new one)
     * 
     * @return \Iris\controllers\helpers\StoreResults (fluent interface)
     */
    public function addBreak() {
        $this->_results[] = [
            self::TYPE => self::PAUSE,
        ];
        return $this;
    }

    /**
     * Shifts from on raw result display to two raw result display
     * or vice versa
     * @return \Iris\controllers\helpers\StoreResults (fluent interface)
     */
    public function rawShift() {
        $this->_results[] = [
            self::TYPE => self::SHIFT,
        ];
        return $this;
    }

    /**
     * Sends the result array to a view variable 'results'
     * 
     * @param string $varName another var name may be specified
     */
    public function sendToView($varName = 'results') {
        $this->toView($varName, $this->_results);
    }

}
