<?php

namespace Iris\OS;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Some functions are specifics or behave differently in Windows.
 * Futhermore there is no Windows but WindowsS. This class is for Windows 7 and 8
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * See commentary in namespace description about Windows as an extension of
 * Unix. The joke apart, it is a way to simplify.
 * 
 */
class Windows extends _OS{
    
    /**
     * Get user home directory 
     * 
     * @return string
     */
    public function getUserHomeDirectory(){
        
        /**
ALLUSERSPROFILE=C:\ProgramData
APPDATA=C:\Users\jacques\AppData\Roaming
HOMEDRIVE=C:
HOMEPATH=\Users\jacques
LOCALAPPDATA=C:\Users\jacques\AppData\Local
OS=Windows_NT
Path=C:\Windows\system32;C:\Windows;C:\Windows\System32\Wbem;C:\Windows\System32\WindowsPowerShell\v1.0\;c:\xampp\php\
PATHEXT=.COM;.EXE;.BAT;.CMD;.VBS;.VBE;.JS;.JSE;.WSF;.WSH;.MSC
PUBLIC=C:\Users\Public
SystemDrive=C:
SystemRoot=C:\Windows
USERNAME=jacques
USERPROFILE=C:\Users\jacques
windir=C:\Windows
         */
        $dir = $this->shellvar('%LOCALAPPDATA%');
        return $dir;
    }

    public function fullPermission($fileName) {
        throw new \Iris\Exceptions\NotSupportedException('Permissions has to be implemented');
    }
}


