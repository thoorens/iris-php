<?php

namespace Iris\SysConfig;

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
 */
class TConfig extends \Iris\DB\_Entity{
    
}
/**
 * 
 */
class TSections extends \Iris\DB\_Entity{
}

/**
 * 
 * A config parser using a database.
 * 
 * Projet IRIS-PHP
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * 
 * @todo not functional
 */
class DBParser extends _Parser {

    public function processFile($dsn, $sectionName=FALSE, $inheritance=_Parser::COPY_AND_LINK) {
     
        echo "ATTENTION A l'ORDRE";
        
        $em = \Iris\DB\_EntityManager::EMFactory($dsn);
        $dataConfig = new TConfig();
        $sections = new TSections();
        
        $results = $sections->fetchAll();
        $sectionList = array();
        $configs = array();
        foreach($results as $line){
            $sectionList[$line->id] = $line->SectionName;
            $sectionName = $line->SectionName;
            $config = new Config($sectionName);
            $configs[$sectionName] = $config;
            if($line->parent_id>0){
                $parent = $configs[$sectionList[$line->parent_id]]; 
                $config->setParent($parent,$inheritance);
            }
            $props = $dataConfig->where('section_id =',$line->id)
                    ->fetchAll();
            foreach($props as $cline){
                $key = $cline->Key;
                $value = $cline->Value;
                $config->$key = $value;
            }
        }
        if ($sectionName === FALSE) {
            return $configs;
        } else {
            return $configs[$sectionName];
        }
    }

    public function exportFile($fileName, $configs) {
        
    }

   

}

?>
