<?php
namespace wbClasses;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

trait tTest{
    /**
     * The file name of sqlite database
     * @var string
     */
    protected $_dataBase=\null;
    
    public function _init(){
        $this->_dataBase = AutoEM::DF_FILENAME;
        $this->_setLayout('module');
    }
    
    public function indexAction(){
        
/* @var $autoem AutoEM */
        $autoem = AutoEM::GetInstance();
        $em = $autoem->getEm();
        //iris_debug($em);
    }
}