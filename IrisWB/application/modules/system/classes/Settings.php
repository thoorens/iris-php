<?php


namespace modules\system\classes;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Description of Settings
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 */
class Settings extends \Iris\SysConfig\_Settings{
    
    protected static $_GroupName = "test";
    
    protected function _init() {
        \Iris\SysConfig\StandardSetting::CreateSetting('backgroundColor', '#000');
        \Iris\SysConfig\BooleanSetting::CreateSetting('layout', \TRUE);
        
    }    
    
    

}
