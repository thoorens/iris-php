<?php

namespace Payoff\Paypal;


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
 * This class is a subhelper for the helper Paypal family. 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Subhelper extends \Iris\Subhelpers\_Subhelper {

    /**
     * Each subhelper class has its own unique instance
     * @var static
     */
    protected static $_Instance = NULL;
    
    private $_demo;
    
    private $_otherWindow = \TRUE;

    private $_objectID;
    
    public function setDemo($demo = \TRUE) {
        $this->_demo = $demo;
        return $this;
    }
    
    public function setOtherWindow($otherWindow) {
        $this->_otherWindow = $otherWindow;
        return $this;
    }

    
    private function _getPaypalSite(){
        if(\Iris\Engine\Mode::IsProduction() and !$this->_demo){
            $site = "www.paypal.com";
        }
        else {
            $site = "www.sandbox.paypal.com";
        }
        return $site;
    }    
    
    
    
    public function buyButton(){
        $site = $this->_getPaypalSite();
        $objectID = $this->_objectID;
        $target = $this->_otherWindow ? '_blank' : '_top';
        $locale = $this->_('en_US',\TRUE);
        $title = $this->_("PayPal - The safer, easier way to pay online!",\TRUE);
        return <<< TEXT
<form action="https://$site/cgi-bin/webscr" method="post" target="$target">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="$objectID">
<input type="image" src="https://www.paypalobjects.com/$locale/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="$title" title="$title">
</form>
   

TEXT;
        
    }
    
    public function setObjectID($objectID) {
        $this->_objectID = $objectID;
        return $this;
    }


}
