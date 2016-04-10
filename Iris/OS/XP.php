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
 * Futhermore the is no Windows but WindowsS
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * See commentary in namespace description about Windows as an extension of
 * Unix. The joke apart, it is a way to simplify.
 * 
 */
class XP extends _OS{
    
    protected function __construct() {
        $version = self::_DetectOS();
        Messages::Abort('ERR_BAD_OS',$version);
        parent::__construct();
    }

    
    /**
     *
     * @param type $target
     * @param type $link 
     * @todo remove the exception and treat symlinks according to 
     * windows version
     */
    public function symlink($target, $link) {
        throw new \Iris\Exceptions\NotSupportedException('No linking possible in Windows XP');
    }

    /**
     *
     * @param type $from_path
     * @param type $to_path 
     * @todo remove the exception and treat links according to 
     * windows version
     */
    public function link($from_path, $to_path) {
        throw new \Iris\Exceptions\NotSupportedException('No linking possible in Windows XP');
    }

    /**
     * Get user home directory 
     * 
     * @return string
     */
    public function getUserHomeDirectory(){
        /** XP:
ALLUSERSPROFILE=C:\Documents and Settings\All Users
APPDATA=C:\Documents and Settings\Administrateur\Application Data
CommonProgramFiles=C:\Program Files\Fichiers communs
HOME=C:\home
HOMEDRIVE=C:
HOMEPATH=\Documents and Settings\Administrateur
OS=Windows_NT
Path=C:\oraclexe\app\oracle\product\10.2.0\server\bin;C:\WINDOWS\system32;
C:\WINDOWS;C:\WINDOWS\System32\Wbem;C:\Gnu\bin;;C:\GNU\BIN
PATHEXT=.COM;.EXE;.BAT;.CMD;.VBS;.VBE;.JS;.JSE;.WSF;.WSH
SystemDrive=C:
SystemRoot=C:\WINDOWS
USERNAME=Administrateur
USERPROFILE=C:\Documents and Settings\Administrateur
windir=C:\WINDOWS
         */
        $dir = $this->shellvar('%LOCALAPPDATA%');
        return $dir;
    }

    public function fullPermission($fileName) {
        throw new \Iris\Exceptions\NotSupportedException('Permissions has to be implemented');
    }
}


