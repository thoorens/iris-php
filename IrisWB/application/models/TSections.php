<?php

namespace models;

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
 * This class is used in the TOC of the workbench to get
 * the parent section of each item of the sequence (through the 
 * use of the _at_ pseudo field
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TSections extends \Iris\DB\_Entity {

//    /**
//     * This method illustrates how to get the children records
//     * in a n to 1 relation
//     */
//    public static function Test() {
//        $tsections = new TSections;
//        $sections = $tsections->fetchall();
//
//        /* @var $section \Iris\DB\Object */
//        foreach ($sections as $section) {
//            echo "<h5>" . $section->GroupName . '</h5>';
//            $seqs = $section->_children_sequence_section_id;
//            foreach ($seqs as $seq) {
//                echo $seq->URL . '<br>';
//            }
//        }
//        die('ok');
//    }

}

?>
