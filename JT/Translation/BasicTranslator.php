<?php



namespace JT\Translation;

/**
 * A default translator, doing nothing
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class BasicTranslator extends \Iris\Translation\_Translator{
    
    private $_data = array(
        
    );
    
    
    
    public function translate($message, $language = NULL) {
        if(isset($this->_data[$message])){
            return $this->_data[$message];
        }
        return $message;
    }

}

?>
