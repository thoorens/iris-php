<?php

namespace Iris\views\helpers;

/**
 * Creates a "quoted" view , passes it an array of data and
 * "renders" it.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * 
 */
class Quote extends _ViewHelper {


    public function help($text, $data=NULL) {
        if(is_null($data)){
            $data = $this->_view;
        }
        $quoteView = new \Iris\MVC\Quote($text, $data);
        return $quoteView->render();
    }

}

