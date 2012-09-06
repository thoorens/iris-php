<?php



namespace Iris\Forms\Elements;

use Iris\Forms as ifo;

/*
 * This file is part of IRIS-PHP.
 *
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IRIS-PHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IRIS-PHP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright 2012 Jacques THOORENS
 */

/**
 * A file loader element in a form
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class FileElement extends \Iris\Forms\_Element {

    /**
     * The uploaded file can have a specific destination
     * directory
     * 
     * @var string
     */
    protected $_specificDirectory = NULL;
    
    /**
     * The uploaded file can have a specific destination
     * @var string
     */
    protected $_specificFileName = NULL;


    public function __construct($name, $options = array()) {
        parent::__construct($name, 'input', $options);
        $this->_subtype = 'file';
        if (isset($options['maxfilesize'])) {
            $this->_fileSize = $options['maxfilesize'];
        }
        else {
            $this->_fileSize = 1000000;
        }
    }

    /**
     * For this type of element, the "Value" is the name
     * of the uploaded file.
     * @return string
     */
    public function getValue() {
        if(isset($_FILES[$this->getName()]['name'])){
            return $_FILES[$this->getName()]['name'];
        }
        else{
            return '';
        }
    }

    public function getSpecificDirectory() {
        return $this->_specificDirectory;
    }

    /**
     * Sets the specific directory for a file to upload (always relative
     * to IRIS_ROOT_PATH)
     * 
     * @param string $destinationDirectory 
     */
    public function setSpecificDirectory($destinationDirectory) {
        $this->_specificDirectory = $destinationDirectory;
    }

    

    public function getProposedFileName() {
        return $this->_specificFileName;
    }

    public function setProposedFileName($proposedFileName) {
        $this->_specificFileName = $proposedFileName;
    }


    public function render($layout=\NULL) {
        if(isset($this->_attributes['disabled'])){
            return '';
        }
        return parent::render($layout);
    }

    
    
}

?>
