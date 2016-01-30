<?php
namespace wbClasses;
/*
 * This file is part of IRIS-PHP distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2016 Jacques THOORENS
 */


/**
 * Description of DBSequence.php
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class DBSequence extends \Iris\Structure\DBSequence{
    
    public static $DefaultSequenceType = '\\wbClasses\\DBSequence'; 
    /**
     * Return a message adapted to the current displayed view.
     * 
     * @param \Iris\MVC\View $view
     */
    public function getMessage($view) {
        $url = $this->getCurrent()[1];
        if (is_null($url)) {
            return '';
        }
        $row = \models_internal\TSequences::GetItem($url);
        $currentLanguage = strtoupper(\Iris\Engine\Superglobal::GetSession('Language', 'en'));
        $message = $row->$currentLanguage;
        $languages = ['EN','FR'];
        $index = 0;
        $language = $currentLanguage;
        while($index <2 and $message == \NULL or $message == ''){
            $language = $languages[$index++];
            $message = $row->$language;
        }
        \Iris\Engine\Memory::Set('GoodDescription', $currentLanguage == $language);
        return $message;
    }

    

}

