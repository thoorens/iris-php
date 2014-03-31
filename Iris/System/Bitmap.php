<?php

namespace Iris\System;

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
 * @copyright 2011-2014 Jacques THOORENS
 */

/**
 * A way to manage a large bitfield (up to 47). The corresponding names may
 * be stored in a table
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Bitmap {

    /**
     * The entity managing the bits names and values
     *
     * @var \Iris\DB\_Entity
     */
    private $_entity;

    /**
     *
     * @var int
     */
    private $_maxSize = 47;

    /**
     *
     * @var type
     */
    private $_idField = 'id';

    /**
     *
     * @var type
     */
    private $_positionField = 'Position';

    /**
     *
     * @var string
     */
    private $_labelField = "Label";

    /**
     *
     * @var type
     */
    private $_verbose = \FALSE;

    /**
     *
     * @var string
     */
    private $_errorMessage = '';

    /**
     *
     * @var \Iris\DB\Object[]
     */
    private $_data = [];

    function __construct(\Iris\DB\_Entity $entity) {
        $this->_entity = $entity;
    }

    /**
     *
     * @param mixed $arg
     * @return \Iris\controllers\helpers\BitGenerator
     */
    public function help($arg = \NULL, $reset = \FALSE) {
        if ($arg instanceof \Iris\DB\_Entity) {
            $this->_entity = $arg;
            if ($reset) {
                $this->resetTable();
            }
            $this->generate();
        }
        return $this;
    }

    /**
     * Delete all the lines of the table
     *
     * @todo verify if names have been encoded
     */
    public function resetTable() {
        $eCriteres = $this->_entity;
        $tableName = $eCriteres->getEntityName();
        $eCriteres->getEntityManager()->directSQLExec("TRUNCATE TABLE $tableName");
        $this->_report("The table $tableName has been truncated");
    }

    public function generate($force = \FALSE) {
        $eCritere = $this->_entity;
        $tableName = $eCritere->getEntityName();
        $lines = $eCritere->fetchAll();
        if (count($lines)) {
            if ($force) {
                $this->resetTable();
            }
            else {
                $this->_report("The table $tableName already contains data.");
                return;
            }
        }
        $bits = 1;
        $oldbits = 1;
        $index = 0;
        do {
            $record = $eCritere->createRow();
            $record->{$this->_idField} = $bits;
            if (!empty($this->_positionField)) {
                $record->{$this->_positionField} = $index;
            }
            $oldbits = $bits;
            $bits *= 2;
            $record->save();
        } while ($index < 47);

        $this->_report("The table $tableName has been filled with bit data.");
    }

    /**
     *
     * @param \Iris\DB\_Entity $entity
     * @return \Iris\System\BitMap (fluent interface)
     */
    public function setEntity(\Iris\DB\_Entity $entity) {
        $this->_entity = $entity;
        return $this;
    }

    /**
     *
     * @param type $maxSize
     * @return \Iris\System\BitMap (fluent interface)
     */
    public function setMaxSize($maxSize) {
        $this->_maxSize = $maxSize;
        return $this;
    }

    /**
     *
     * @param type $idField
     * @return \Iris\System\BitMap (fluent interface)
     */
    public function setIdField($idField) {
        $this->_idField = $idField;
        return $this;
    }

    /**
     *
     * @param type $positionField
     * @return \Iris\System\BitMap (fluent interface)
     */
    public function setPositionField($positionField) {
        $this->_positionField = $positionField;
        return $this;
    }

    /**
     *
     * @param boolean $verbose
     * @return \Iris\System\BitMap (fluent interface)
     */
    public function setVerbose($verbose = \TRUE) {
        $this->_verbose = $verbose;
        return $this;
    }

    /**
     *
     * @param string $labelField
     * @return \Iris\System\BitMap (fluent interface)
     */
    public function setLabelField($labelField) {
        $this->_labelField = $labelField;
        return $this;
    }

    /**
     * Issues an error message in verbose mode
     * @param string $message
     */
    private function _report($message) {
        if ($this->_verbose) {
            echo $message . "</br>";
        }
    }

    /**
     * Get a possible error message (usefull in non verbose mode
     * @return string
     */
    public function getErrorMessage() {
        return $this->_errorMessage;
    }

    /**
     * Returns an array of
     *
     * @param boolean $byLabel if TRUE, sort by label (default FALSE)
     * @param type $positional if TRUE (default), use position as index (otherwise, bitmap value)
     */
    public function asArray($byLabel = \FALSE, $positional = \TRUE) {
        $lines = $this->_getData();
        foreach ($lines as $line) {
            if ($line->{$this->_labelField} != "") {
                if ($positional) {
                    $array[$line->{$this->_positionField}] = $line->{$this->_labelField};
                }
                else {
                    $array[$line->{$this->_idField}] = $line->{$this->_labelField};
                }
            }
        }
        if ($byLabel) {
            asort($array);
        }
        return $array;
    }

    public function getLabel($position) {
        return $this->getData()[$position]->{$this->_labelField};
    }

    public function getValue($position) {
        return $this->getData()[$position]->{$this->_idField};
    }

    public function getLabelAndValue($position) {

    }

    private function _getData() {
        if (count($this->_data) == 0) {
            $entity = $this->_entity;
            $entity->order($this->_positionField);
            $this->_data = $entity->fetchAll();
        }
        return $this->_data;
    }

}

