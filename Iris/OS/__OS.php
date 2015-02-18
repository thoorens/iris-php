<?php

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Some OS dependand OS dependant. 
 * 
 * Windows should be considered as a descendant of  Unix : it misses some 
 * functions which must be simulated or behaves differenty. Some peculiarities 
 * are  hidden by PHP.
 * 
 * To avoid problems and not cause pain to anybody, I have renamed Unix to 
 * _OS and made an empty subclass Unix. 
 * 
 */
namespace Iris\OS;

/**
 */
class __OS {
    
}


