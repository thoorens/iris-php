<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Search an explanation of the current error in Memory to display it
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * @todo : too specific, move it to WorkBench
 */
class Error extends \Iris\views\helpers\_ViewHelper implements \Iris\Subhelpers\iRenderer {

    use \Iris\Subhelpers\tSubhelperLink;

        
    protected $_subhelperName = '\Iris\Subhelpers\ErrorDisplay';    

    /**
     * Similar to getParameters from the subhelper, but transform the array
     * in a '/' separated string
     * 
     * @return string
     */
    public function parameters() {
        $parameters = $this->_subhelper->getParameters();
        return implode('/', $parameters);
    }

    /**
     * Tries to get a comment of the error if possible.
     * Currently disabled
     * 
     * @return string 
     */
    public function comment() {
        return;
//        $commentName = \Iris\Engine\Memory::Get('ErrorComment', 'noComment');
//        $commentClass = \Iris\Translation\_Messages::GetSender('error');
//        $comment = new $commentClass;
//        $message = $comment->$commentName();
//        if ($message == '')
//            return '';
//        return <<< MESSAGE
//        <div class="iris_error iris_explanation">
//            $message
//        </div>
//MESSAGE;
    }

    public function render(array $arg1, $arg2) {
        $title = $this->_subhelper->title;
        $module = $this->_subhelper->module;
        $controller = $this->_subhelper->controller;
        $action = $this->_subhelper->action;
        $parameters = $this->parameters();
        $message = $this->_subhelper->message;
        $comment = $this->comment();
        $html = <<< RENDER
<h1>$title</h1>
<p class="iris_error iris_context">
module : <em>$module</em> &diams;
controller : <em>$controller</em>&diams;
action : <em>$action</em> &diams;
parameters : <em>$parameters</em>
</p>
<p class="iris_error iris_message">
    $message
</p>
$comment
RENDER;
        $html .= "<div class=\"iris_error iris_tracecont\">\n";
        $html .= "<h3>Controllers:</h3>\n";
        foreach ($this->_subhelper->systemTrace as $index => $trace):
            $html .= sprintf("<p>%2d [%s]- %s / %s / %s </p>\n", $index, $trace['Type'], $trace['MODULE'], $trace['CONTROLLER'], $trace['ACTION']);
        endforeach;
        $html .= "</div>\n";
        return $html;
    }

}

