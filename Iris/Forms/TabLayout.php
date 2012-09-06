<?php



namespace Iris\Forms;

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
 * A tabular layout for forms, using <table> for form, grouping
 * label and input field in <tr> and placing them respectively
 * in <th> and <td> tags. 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * A tabular layout for forms, using <table> for form, grouping
 * label and input field in <tr> and placing them respectively
 * in <th> and <td> tags.
 * 
 */

class TabLayout extends _FormLayout {

    // main tag for the form
    protected $_main ='table';
    // no group allowed
    protected $_group = '!';
    
    // each element begins with <tr attributes><th> (0 matches tr)
    protected $_before = array(0,"tr|th");
    // the label is followed by </th><td attributes> (1 matches td)
    protected $_middle = array(1,"/th|td");
    // the element is terminated by "</td/></tr> (-1 matches none) 
    protected $_after = array(-1,"/td|/tr");
    
    
    
}

?>
