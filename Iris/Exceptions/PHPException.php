<?php
namespace Iris\Exceptions;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * An encapsulation of standard exceptions to mimic the _Exception 
 * behaviour.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class PHPException extends _Exception {

    /**
     * A copy of system trace for standard exceptions
     * @var Exception[]
     * @todo not read
     */
    protected $_trace = [];

    /**
     * A copy of system trace (as a string) for standard exceptions
     * @var string
     * @todo not read
     */
    protected $_traceAsString = NULL;

    /**
     *
     * @var \Exception
     */
    private $_exception;
    /**
     * A way to encapsulate all exceptions in a \Iris\Exceptions\_Exception
     * 
     * @param \Exception $exception 
     * @return _Exception 
     */
    public static function Encapsulate($exception) {
        while($exception->getPrevious()!=\NULL){
            $exception = $exception->getPrevious();
        }
        if ($exception instanceof _Exception) {
            return $exception;
        }
        else {
            return new PHPException($exception);
        }
    }

    public function __construct(\Exception $exception) {
        $this->_phpException = $exception;
        $code = $exception->getCode();
        $code = is_numeric($code) ? $code : 0;
        parent::__construct($exception->getMessage(), $code, $exception->getPrevious());
        $this->file = $exception->getFile();
        $this->line = $exception->getLine();
        $this->_trace = $exception->getTrace();
        $this->_traceAsString = $exception->getTraceAsString();
    }

    /**
     * In the case of true PHP, one has to get traces from the copy.
     * 
     * @param type $mode
     * @return type 
     */
    public function getIrisTrace($mode,$lineTitle=\FALSE) {
        if($this->_exception instanceof \PDOException){
            if($mode==self::MODE_STRING){
                return $this->_exception->getTraceAsString();
            }
            else{
                return $this->_exception->getTrace();
            }
        }
        else{
            return parent::getIrisTrace($mode);
        }
    }

    public function getExceptionName() {
        return "System PHP Exception";
    }

}

