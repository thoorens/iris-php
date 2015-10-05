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
 * Generates an icon link to a small presentation of IRIS or to the official website
 */
class GoAd extends \Iris\views\helpers\_ViewHelper {

    /**
     * This helper is a singleton
     * 
     * @var boolean
     */
    protected static $_Singleton = \TRUE;

    /**
     * 
     * @param boolean $local If TRUE go the internal description, otherwise to the official site
     * @param boolean $grey If True, the logo is in grey tones
     * @param boolean $button If true the link will be a button
     * @return \Iris\Subhelpers\ImageLink
     */
    public function help($local = \TRUE, $grey = \FALSE, $button = \FALSE) {
        $logoName = $grey ? 'IrisSmallG' : 'IrisSmall';
        $client = new \Iris\System\Client();
        $lang = $client->getLanguage();

        $title = $this->_('Site powered by Iris-PHP', \TRUE);
        if (is_string($local)) {
            $url = $local;
        }
        elseif ($local === \TRUE) {
            $url = "/!iris/index/index/$lang";
        }
        else {
            $url = 'http://irisphp.org';
        }
        $imageLink = new \Iris\Subhelpers\ImageLink(["/!documents/file/logos/$logoName.png", $url, $title]);
        if ($button) {
            return $imageLink->button();
        }
        else {
            return $imageLink;
        }
    }

}
