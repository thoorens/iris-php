<?php

namespace Iris\views\helpers;

use models\_invoiceManager as IM;
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
 *
 * 
 */

/**
 * Displays the database state and warning messages
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class DbLogo extends \Iris\views\helpers\_ViewHelper {

    protected $_singleton = TRUE;

    /**
     * Returns a link to the present RDBMS
     * 
     * @return string
     */
    public function help() {
        switch(IM::GetDefaultDbType()){
            case IM::SQLITE:
                $file = 'sqlite.png';
                break;
            case IM::MYSQL:
                $file = 'mySQL.png';
                break;
        }
        return $this->callViewHelper('image',$file,'Logo',\NULL,'/!documents/file/logos/');
    }

}
