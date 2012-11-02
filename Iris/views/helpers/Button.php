<?php

namespace Iris\views\helpers;

use Iris\System\Client;

/**
 * 
 *
 * Creates a button which links to a page or site
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Button extends _ViewHelper {

    private $_id = \NULL;

    private function _idAttribute() {
        $id = $this->_id;
        $attribute = '';
        if (!is_null($id)) {
            $attribute = sprintf(' id="%s" ', $id);
        }
        return $attribute;
    }

    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    /**
     * A special array corresponding to a non existent button
     * @var array
     */
    public static $NoLink = array('!!!!NONE!!!!', '', '');

    public function help($message = NULL, $url = '/', $tooltip = '', $class = 'norm') {
        // an accessor to helper instance
        if (is_null($message)) {
            return $this;
        }
        else {
            return $this->render($message, $url, $tooltip, $class);
        }
    }

    public function render($message, $url = '/', $tooltip = '', $class = 'norm') {
        // group all three first parameters in one array, second parameters is class
        if (is_array($message)) {
            $url = $message[1];
            $tooltip = $message[2];
            $message = $message[0];
            $class = $url == '/' ? 'norm' : $class;
        }
        // a way to have no button
        if ($message == self::$NoLink[0]) {
            return('');
        }
        // no JS compatibility
        $id = $this->_idAttribute();
        if (!\Iris\Users\Session::JavascriptEnabled()) {
            $href = is_null($url) ? '' : "href=\"$url\"";
            if ($this->_oldBrowser()) {
                $class .= '_old_nav';
                return "<a $href $id class=\"$class\" title=\"$tooltip\">&nbsp;$message&nbsp;</a>";
            }
            else {
                // Bouton dans un lien 
                return "<a $href>" .
                        "<button class=\"$class\" $id title=\"$tooltip\">$message</button></a>";
            }
        }
        else {
            // Bouton avec Javascript
            $onclick = is_null($url) ? '' : "onclick=\"javascript:location.href='$url'\"";
            return "<button class=\"$class\" $id title=\"$tooltip\" $onclick>$message</button>";
        }
    }

    public function image($file, $message = NULL, $url = '/', $bulle = '', $classe = 'norm') {
        // Si le nom commence par /, on place dans le répertoire /image du site
        if (strpos($file, '/') == 0) {
            $src = 'src="images' . $file . '" ';
        }
        // autrement adresse absolue
        else {
            $src = 'src="http://' . $file . '" ';
        }
        $this->getView()->headStyle('span.btnlabel{padding:0 0 20px 20px}');
        $message = "<span class=\"btnlabel\">" . $message . "<span/>";
        return $this->bouton($message, $url, $bulle, $classe);
    }

    private function _oldBrowser() {
        $client = new Client();
        switch ($client->getClient()) {
            case Client::IE:
                $version = $client->getVersion(Client::MAJOR);
                return $version < 7 ? TRUE : FALSE;
        }
        return FALSE;
    }

}

?>
