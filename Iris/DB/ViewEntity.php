<?php

namespace Iris\DB;

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
 * A special entity for a database view. It can automatically
 * gets its metadata from another table. As AutoEntity, it
 * need not to be derived.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ViewEntity extends _Entity {

    /**
     *
     * @param string $entityName
     * @param array $idNames
     * @param _EntityManager $EM 
     */
    public function __construct($entityName, $reflectionEntity, array $idNames, $EM=NULL) {
        $this->_view = TRUE;
        $this->_entityName = $entityName;
        $this->_reflectionEntity = $reflectionEntity;
        $this->_idNames = $idNames;
        parent::__construct($EM);
    }

    /**
     * 
     * @return Metadata
     */
    protected function _readMetadata() {
        $reflectionName = "\\models\\T".ucfirst($this->_reflectionEntity);
        $reflexion = new $reflectionName();
        $metadata = $reflexion->_readMetadata();
        return $metadata;
    }

}

?>
