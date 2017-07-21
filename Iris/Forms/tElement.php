<?php

namespace Iris\Forms;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * Description of tElement
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
trait tElement {
    /**
     *
     * @var string
     */
    protected $_attributes = [];

    /**
     *
     * @var string
     */
    protected $_type;
    
    
    /**
     *
     * @var mixed
     */
    protected $_label = \NULL;

    /**
     *
     * @var string
     */
    protected $_name = \NULL;

    /**
     *
     * @var string
     */
    protected $_title = \NULL;
    /**
     *
     * @var mixed
     */
    protected $_value = '';

    /**
     *
     * @var string
     */
    protected $_errorMessage = '';

    /**
     *
     * @var boolean 
     */
    protected $_canDisable;

    /**
     *
     * @var _Form 
     */
    protected $_container = \NULL;

    /**
     *
     * @var iv\_Validator 
     */
    protected $_validator = \NULL;

    /**
     * TRUE if the element can be checked
     * 
     * @var boolean 
     */
    protected $_checkable = \FALSE;

    /**
     * Value can be rendered as an attribute (by default NOT)
     * 
     * @var boolean
     */
    protected $_valueAsAttribute = \FALSE;

    /**
     * For input file, indicate max file size
     * @var int
     */
    protected $_fileSize = 0;

    /**
     * In mother class, options are a simple text to be inserted
     * in the main tag (special attributes)
     * 
     * @var string
     */
    protected $_options = [];

    /**
     * A class to put on each part of the render code
     * 
     * @var string
     */
    protected $_globalClass = \NULL;


}
