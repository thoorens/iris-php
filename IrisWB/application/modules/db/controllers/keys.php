<?php

namespace modules\db\controllers;

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
 * 
 * Demonstration and test of multifield primary keys
 * 
 * 
 */
class keys extends _db {

    private function _createTables($table = 1) {
        $em = \models\_invoiceEntity::GetEM();
        $liste = $em->listTables();
        if (array_search('numbers', $liste) === \FALSE) {
            \models\TNumbers::Create();
        }
        if ($table > 1) {
            if (array_search('numbers2', $liste) === \FALSE) {
                \models\TNumbers2::Create();
            }
        }
        if ($table > 2) {
            if (array_search('numbers3', $liste) === \FALSE) {
                die('Numbers3');
                \models\TNumbers3::Create();
            }
        }
    }

    /**
     * We get an object twice (with key values ordered by implicit key names).
     * The two object are the same instance (thanks to internal repository).
     * When we modify the first, the second is changed too
     */
    public function implicitAction() {
        $this->_createTables();
        $eNUmbers = \models\TNumbers::GetEntity();
// first find will fetch the database
        $test1 = $eNUmbers->find(['one', 'un']);
// second find will use the internal repository
        $test2 = $eNUmbers->find(['one', 'un']);
        $this->__orig = clone($test1);
// modify 3d field
        $test1->Data = "Modified";
        $this->__test1 = $test1;
        $this->__test2 = $test2;
        $this->__command = "find(['one', 'un']";
        $this->setViewScriptName('/keys');
    }

    /**
     * Same as implicit, but here the find method uses named key parameters
     */
    public function explicitAction() {
        $this->_createTables();
        $eNUmbers = \models\TNumbers::GetEntity();
// first find will fetch the database
        $test1 = $eNUmbers->find(['French' => 'un', 'English' => 'one']);
// second find will use the internal repository
        $test2 = $eNUmbers->find(['English' => 'one', 'French' => 'un']);
        $this->__orig = clone($test1);
// modify 3d field
        $test1->Data = "Modified";
        $this->__test1 = $test1;
        $this->__test2 = $test2;
        $this->__command = "find(['French' => 'un', 'English' => 'one'])";
        $this->setViewScriptName('/keys');
    }

    public function testErrorAction() {
        $this->_createTables();
        $eNUmbers = \models\TNumbers::GetEntity();
        try {
            $test1 = $eNUmbers->find(['English' => 'one']);
        }
        catch (\Iris\Exceptions\DBException $ex) {
            $this->__message = $ex->getMessage();
        }
        $this->__command = "find(['English' => 'one']";
    }

    public function foreignAction() {
        $this->_createTables(2);
        $eNumbers2 = \models\TNumbers2::GetEntity();
        $first = $eNumbers2->find(1);
        $this->__first = $first;
        $this->__parent = $first->_at_English__French;

        $eNumbers = \models\TNumbers::GetEntity();
        $unone = $eNumbers->find(['French' => 'un', 'English' => 'one']);
        $this->__unone = $unone;
        $this->__children = $unone->_children_Numbers2__English__French;
    }

    public function foreignDeclareAction() {
        $this->_createTables(3);
        $eNumbers3 = \models\TNumbers3::GetEntity();
        iris_debug($eNumbers3->getMetadata());
        $first = $eNumbers3->find(1);
        $this->__first = $first;
        $this->__parent = $first->_at_English__French;
        die('ok');

        $eNumbers = \models\TNumbers::GetEntity();
        $unone = $eNumbers->find(['French' => 'un', 'English' => 'one']);
        $this->__unone = $unone;
        $this->__children = $unone->_children_Numbers3__English__French;
        die('OK');
    }

}
