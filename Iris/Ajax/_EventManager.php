<?php

namespace Iris\Ajax;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */
 
/**
 * A series of counters, wich may be linked to dom object where to display
 * their values and send a message in a bottle.
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
abstract class _EventManager extends \Iris\Subhelpers\_Subhelper {

    protected static $_Instance = \NULL;
      

    

   public abstract function onClick($sender, $function, $modules = []);
    
    
}