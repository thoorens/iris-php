<?php
namespace ILO\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 * 
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This helper creates administration and demo buttons.
 * 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org 
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
 */

class GoInternal extends \Iris\views\helpers\_ViewHelper {
    /**
     * The admin button mapped bit
     */

    const ADMIN = 1;
    /**
     * The main welcome page button mapped bit
     */
    const MAIN = 2;
    /**
     * The Reset page button mapped bit
     */
    const RESET = 4;
    /**
     * The irisphp site button mapped bit
     */
    const IRISPHP = 8;
    /**
     * The irisphp wiki site button mapped bit
     */
    const IRISWIKI = 16;
    /**
     * The irisphp API site button mapped bit
     */
    const IRISAPI = 32;

    /**
     * This helper is a singleton
     * @var boolean 
     */
    protected static $_Singleton = \TRUE;

    /**
     * Displays one or more buttons (if first parameters is a bit field)
     * 
     * @param mixed $command The name of the button or an addition of bits
     * @param boolean $developmentOnly If TRUE will appear only at development time
     * @return string
     */
    public function help($command, $developmentOnly = FALSE) {
        if (is_numeric($command)) {
            return $this->_variousButtons($command, $developmentOnly);
        }
        switch ($command) {
            case 'admin':
                $text = 'Administration';
                $uri = '/!' . $command;
                $comment = $this->_('Go to administration tools...', \TRUE);
                $developmentOnly = \TRUE;
                break;
            case 'reset':
                $text = 'RESET';
                $uri = '/!iris/reset';
                $comment = $this->_('Reset the session', \TRUE);
                break;
            case 'main':
                $text = $this->_('Return to main page', \TRUE);
                $uri = '/';
                $comment = $this->_('Quit admin module and return to the site welcome page', \TRUE);
                break;
            case 'irisroot':
                $text = $this->_('Iris PHP official web site', \TRUE);
                $uri = 'http://irisphp.org';
                $comment = $this->_('Return to irisphp.org', \TRUE);
                break;
            case 'iriswiki':
                $text = $this->_('Iris PHP documentation wiki site', \TRUE);
                $uri = 'http://wiki.irisphp.org';
                $comment = $this->_('Return to documentation', \TRUE);
                break;
            case 'irisapi':
                $text = $this->_('Iris PHP API web site', \TRUE);
                $uri = 'http://api.irisphp.org';
                $comment = $this->_('Return to API documentation', \TRUE);
                break;
        }
        if ($developmentOnly and \Iris\Engine\Mode::IsProduction()) {
            return '';
        }
        return $this->callViewHelper('button', $text, $uri, $comment)->__toString();
    }

    /**
     * Displays various buttons in line
     * 
     * @param type $numbers Bits corresponding to the desired buttons
     * @param boolean $developmentOnly if TRUE, the buttons will ignored in production
     * @return string
     */
    private function _variousButtons($numbers, $developmentOnly) {
        if ($developmentOnly and \Iris\Engine\Mode::IsProduction()) {
            return '';
        }
        $text = '';
        if ($numbers & self::ADMIN) {
            $text .= $this->help('admin');
        }
        if ($numbers & self::MAIN) {
            $text .= $this->help('main');
        }
        if ($numbers & self::RESET) {
            $text .= $this->help('reset');
        }
        if ($numbers & self::IRISPHP) {
            $text .= $this->help('irisroot');
        }
        if ($numbers & self::IRISWIKI) {
            $text .= $this->help('iriswiki');
        }
        if ($numbers & self::IRISAPI) {
            $text .= $this->help('irisapi');
        }
        return $text;
    }

}


