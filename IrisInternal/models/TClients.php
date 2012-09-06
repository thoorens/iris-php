<?php

namespace IrisInternal\models;


/**
 * Small test entity
 */
class TClients extends \Iris\DB\_Entity{

    /*
     * Description de la table menus
     *
     * id int(11) : clé primaire
     * Position int(11) : position relative dans la liste
     * Description varchar(100) : texte à afficher
     * Father int(11) : clé étrangère vers le père dans la hiérarchie
     * Level int(11) : niveau dans la hiérarchie
     * Tooltip text : bulle d'aide à afficher au survol
     * URL text : URL relative dans Zend
     * Details` tinyint(1) : présence de détails pour un cours
     * Visible tinyint(1) : visible ou invisible
     * DansPlan tinyint(1) : doit figurer dans le plan du site ou non
     *
     */

    // TODO les clés étrangères ne sont pas gérées dans la classe

    /**
     *
     * @var String nom de la table
     */
    //protected $_name='menus';
    /**
     *
     * @var String nom de la clé primaire
     */
    //protected $_primary='id';
}

