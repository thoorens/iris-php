<?php

namespace Iris\views\helpers;

/**
 * This helper is part of the CRUD facilites offered by Iris. It serves to 
 * display icons for the different actions. The most part of its job is
 * done by \Iris\Subhelpers\Crud.
 *
 * Exemples d'utilisation :
 *
  <pre>
  $icone = $this->view->icone();
  $icone
  // définition du contrôleur
  ->setController('/admin/ufs')
  // définition du préfixe d'action (avant insert/update/delete)
  ->setActionName('')
  // précision du genre de l'entité (M, F ou M' F' pour les élisions)
  ->gender("F'")
  // intitulé de l'entité
  ->entity('UF')
  // champ de l'intitulé servant à décrire l'objet affecté
  ->setDescField('NomUF');
  </pre>
 *
 * Affichage:
  $d = ....;
  $icone->data($d);
  $icone->render('insert');

 * @author Jacques THOORENS (jacques@thoorens.net)
 * @category   Zend
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2009 Jacques Thoorens (http://thoorens.net)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version $Id: $ * @todo : TRANSLATE COMMENTS
 */
class CrudIcon extends _ViewHelper implements \Iris\Subhelpers\iRenderer {
use \Iris\Subhelpers\tSubhelperLink;
    
    
    protected $_baseDir = '/images/icones';
    protected $_subhelperName ='\Iris\Subhelpers\Crud'; 

    /**
     *
     * @param array $params : nom de l'opération et de l'icône
     * @param boolean $iconeActive: choix d'une icone désactivée (ce n'est pas un lien 
     * @return String (cette méthode brise la chaîne)
     */
    public function render(array $params, $iconeActive=TRUE) {

        $operation = $params['operation'];
        $dir = $params['dir'];
        $ref = $params['ref'];
        $help = $params['help'];

        if ($iconeActive) {
            $icon = $this->_view->image($operation . ".png", $operation, $help, $dir);
            return '<a href="' . $ref . '">' . $icon . '</a>';
        }
        else {
            $file = $operation . "_des.png";
            $help = $this->_("Operation not possible in context", TRUE);
            $desc = "$operation inactive";
            return $this->_view->image($file, $desc, $help, $dir);
        }
    }

    /**
     * Accessor set for icon folder
     * 
     * @param type $baseDir the folder name.
     */
    public function setBaseDir($baseDir) {
        $this->_baseDir = $baseDir;
    }

 
    /**
     * Translates a message by using its ancestor method with default 
     * to system message
     * 
     * @param string $message Message to display
     * @param boolean $system System message (by default yes)
     * @return string 
     */
    public function _($message, $system=\TRUE) {
        return parent::_($message, $system);
    }
}
