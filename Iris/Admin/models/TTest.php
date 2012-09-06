<?php



/*
 * CREATE TABLE double (
  id1 int(11) NOT NULL,
  id2 int(11) NOT NULL,
  Data varchar(20) NOT NULL,
  PRIMARY KEY (id1,id2)
  ) ;



  CREATE TABLE simple (
  id int(11) NOT NULL,
  DataChar varchar(20) NOT NULL,
  PRIMARY KEY (id)
  );

  CREATE TABLE  niveau2 (
  id int(11) NOT NULL,
  simple_id int(11) NOT NULL,
  double_id1 int(11) NOT NULL,
  double_id2 int(11) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (double_id1, double_id2) REFERENCES double (id1, id2),
  FOREIGN KEY (simple_id) REFERENCES simple (id));


 */

namespace Iris\Admin\models;

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
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TTest extends _IrisObject {

    public function __construct($entityName, $type='sqlite') {
        $this->_entityName = $entityName;
        switch ($type) {
            case 'sqlite':
                $EM = $this->_getSystemEM('admin/monTest.sqlite');
                $EM->listTables();
                break;
        }
        $EM->listTables();
        parent::__construct($EM);
    }

}

?>
