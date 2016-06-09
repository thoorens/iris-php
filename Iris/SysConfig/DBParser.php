<?php

namespace Iris\SysConfig;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
        throw new \Iris\Exceptions\NotSupportedException('DBParser is not yet functional');
        print "ATTENTION A l'ORDRE";
        
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
    

   

}


