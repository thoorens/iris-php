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
 * gets its metadata from another table. 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ViewEntity extends _Entity {

    protected $_reflectionEntity = \NULL;

    public static function GetEntity() {
        iris_debug('NOT IMPLEMENTED');
        
        $params = new EntityParams(get_called_class(), func_get_args());
        $strings = $params->getStrings();
        /* @var $metadata Metadata */
        $metadata = $params->getMetadata();
        if (count($strings) != 1 and is_null($metadata)) {
            $message = 'Implicit model entities need a table name as string parameter or a metadata expression.';
            throw new \Iris\Exceptions\DBException($message, \Iris\Exceptions\DBException::$ErrorCode + 11);
        }
        else {
            if (is_null($metadata)) {
                $params->setEntityName($strings[0]);
            }
            else {
                $params->setEntityName($metadata->getTablename());
            }
        }
        return \Iris\DB\_EntityManager::FindEntity($params);
    }

    protected function _readMetadata($metadata = \NULL) {
        iris_debug($metadata, \FALSE);
        if (is_null($this->_metadata)) {
            $masterEntity = TableEntity::GetEntity($this->_reflectionEntity);
            $metadata = $masterEntity->_readMetadata();
            return $metadata;
        }
        return parent::_readMetadata($metadata);
    }

    public function getReflectionEntity() {
        return $this->_reflectionEntity;
    }

}

