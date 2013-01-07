<?php

namespace Iris\views\helpers;

use Iris\System\Client;

/**
 * 
 *
 * Creates a link to a page or site (may a normal link or a button) depending
 * on the concrete class
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Link extends _ViewHelper {

    public static $JavaForce = \FALSE; 
    public static $OldBrowser = \FALSE;
    
    private $_attributes = array();

    /**
     * A special array corresponding to a non existent button/link
     * @var array
     */
    public static $NoLink = array('!!!!NONE!!!!', '', '');

    public function help($message = NULL, $url = '/', $tooltip = '', $class = \NULL) {
        // an accessor to helper instance
        if (is_null($message)) {
            return $this;
        }
        else {
            // group all three first parameters in one array, second parameters is class
            if (is_array($message)) {
                $class = $url == '/' ? \NULL : $url;
                $url = $message[1];
                $tooltip = $message[2];
                $message = $message[0];
            }
            // a way to have no button
            if ($message == self::$NoLink[0]) {
                return('');
            }
            return $this->render($message, $url, $tooltip, $class);
        }
    }

    public abstract function render($message, $url = '/', $tooltip = \NULL, $class = \NULL);
    
    public function image($file, $message = NULL, $url = '/', $tooltip = '', $class = \NULL) {
        if (is_array($message)) {
                $class = $url == '/' ? \NULL : $url;
                $url = $message[1];
                $tooltip = $message[2];
                $message = $message[0];
            }
        // Si le nom commence par /, on place dans le répertoire /image du site
        if (strpos($file, '/') == 0) {
            $src = 'src="images' . $file . '" ';
        }
        // autrement adresse absolue
        else {
            $src = 'src="http://' . $file . '" ';
        }
        $this->getView()->styleLoader('nojsbutton','span.btnlabel{padding:0 0 20px 20px}');
        $message = "<span class=\"btnlabel\">" . $message . "</span>";
        $message = $this->_view->image($file,$message,$tooltip);
        //iris_debug($message);
        $helperName = basename(str_replace('\\','/',get_called_class()));
        return $this->$helperName($message, $url, $tooltip, $class);
    }

    protected function _oldBrowser($force = \FALSE) {
        if($force){
            return \TRUE;
        }
        $client = new Client();
        switch ($client->getClient()) {
            case Client::IE:
                $version = $client->getVersion(Client::MAJOR);
                return $version < 7 ? TRUE : FALSE;
        }
        return FALSE;
    }

    public function __call($name, $arguments) {
        if (strpos($name, 'set') === 0) {
            $key = lcfirst(substr($name, 3));
            $this->_attributes[$key] = $arguments[0];
            return $this;
        }
        else {
            return parent::__call($name, $arguments);
        }
    }

    protected function _getAttributes($tooltip, $class) {
        $html = '';
        foreach ($this->_attributes as $name => $value) {
            $html .= "$name=\"$value\" ";
        }
        $html .= is_null($tooltip) ? '' : " title=\"$tooltip\"";
        $html .= is_null($class) ? '' : " class=\"$class\""; 
        return $html;
    }

}

?>
