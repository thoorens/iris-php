<?php

namespace Dojo\views\helpers;

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
 */

/**
 * This helper will provides basic mechanisms for animation in Dojo
 * in interaction with other script
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
abstract class _Animation extends _DojoHelper {

    protected static $_Singleton = \TRUE;

    /**
     * An array of all of the actions to start at frame display
     * @var array(string)
     */
    protected $_jobs = array();

    public function help() {
        return $this;
    }

    public function subscribe($number, $channel = 'NEXT') {
        $doTheJob = '';
        foreach ($this->_jobs as $job) {
            $doTheJob .="restart$job();\n";
        }
        $this->_jobs = array();
        return <<<SCRIPTS
<script type="text/javascript">
   function Subscriber(){
      this.tasks = function(number){
          if(number==$number){
              $doTheJob
        }
        }
      dojo.subscribe('$channel',this,"tasks");
   }
   var sub$number = new Subscriber();
</script>
SCRIPTS;
    }


}

?>
