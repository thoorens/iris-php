<?php

namespace models\crud;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This class is a subhelper for the helper Crud.
 * Il is aimed to manage icons for a CRUD system.
 * Each main function has a special icon, which can
 * be active or not, have an action link and display an understandable
 * tooltip. It is localized but must receive a pretranslated entity name
 * with appropriate gender mark.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class CrudIconManager extends \Iris\Subhelpers\_CrudIconManager {

    /**
     * The directory containing the system icons. You can change it but
     * must provide the necessary image files.
     * 
     * @var string
     */
    protected $_systemIconDir = '/!documents/file/images/icons';

    /**
     * The directory containing your extended icons. Any folder may be used. For
     * each operation a active and a inactive icons have to be provided.
     * 
     * @var string
     */
    protected $_iconDir = '/images/icons';


    public function _init() {
        
        // you can add new icons and operation here
        //$newIcon = new \Iris\Subhelpers\Icon('***operation name***', 'Do something to %D %E %O', $hasId);
        //$newIcon->setSpecialUrl('/admin/ufs/prerequis/'); // optional
        //$this->insert($newIcon);
        
    }

   

    

}
