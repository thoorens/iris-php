<?php
namespace Iris\views\helpers;


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
 * This helper permits to collect differents meta tags during
 * the page construction (by calling the helper with an array
 * parameter) and to display all of them (by calling the
 * helper without any parameter). The array parameter consists
 * in an associative array. The keys are used to evit doublons.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Head extends _ViewHelper {

    public function help(){
        return \Iris\Subhelpers\Head::GetInstance()->writeLoaderMark();
    }
    
}