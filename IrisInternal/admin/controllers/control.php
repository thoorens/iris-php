<?php

namespace IrisInternal\admin\controllers;;

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
 * A controller for development time
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class control extends \Iris\MVC\_Islet{
    
    
    public function indexAction($color){
        $this->__color=$color;
        $this->__reverseColor = \Iris\System\Functions::GetComplementaryColor($color);
        //iris_debug('Woooow');
        \Iris\Users\Session::GetInstance();
        $identity = \Iris\Users\Identity::GetInstance();
        $this->__userName = $identity->getName();
        $this->__group = $identity->getRole();
        if(!\Iris\Engine\Mode::IsProduction()){
            $this->setViewScriptName('toolbar');
        }
    }
}

?>
