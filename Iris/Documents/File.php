<?php

namespace Iris\Documents;

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
 * A special type of object: it is managed in a table and
 * correspond to a concrete file in the data directory.
 * It may used in file download and upload.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ *
 */
class File extends \Iris\DB\Object {
    const URL = 1;
    const PATH = 2;

    const WAITING = 0;
    const CONFIRMED = 1;
    const DEF_DIR = '/data/temp';


    private static $_Num = 0;
    private static $_User = 0;
    private $_nameField = 'File';
    private $_dirField = 'Folder';
    private $_statusField = 'Statut';

    /**
     * Fonction appelée par le constructeur:
     * -- création d'un compteur pour les liens temporaires
     * -- suppression des liens temporaires existants
     * -- initialisation des chemins
     */
    public static function MagicNumber() {
        if (self::$_Num == 0) {
            self::$_Num = rand(1, 1000000);
            $_SESSION['FileManagerToken'] = sprintf('%06d',self::$_Num);
        }
        return sprintf('%06d',self::$_Num);
    }
    
    public function __construct(\Iris\Documents\FileEntity $entity, $idValues, $data, $new = FALSE) {
        self::MagicNumber();
        return parent::__construct($entity,$idValues,$data,$new);
    }

    

    /**
     *
     * @param type $newRep
     * @return \Iris\FileSystem\File 
     */
    public function newFileName($newRep=null) {
        if (is_null($newRep)) {
//            $newRep = self::DEF_DIR;
            $newRep = $this->Folder;
        }
        $id = $this->_getId();
        $ext = $this->_getFileExt();
        $table = $this->_getFileName();
        $fileName = new \Iris\FileSystem\File('Document_'.$id.'.'.$ext,IRIS_ROOT_PATH.$newRep);
        return $fileName;
    }

    /**
     * Renvoie l'URL ou le PATH vers le document
     *
     * @param <boolean> $mode
     * @return <string>
     */
    public function normalFileName($mode=self::URL) {
        $categorie = $this->_at_categorie_id;
        $categorie = str_replace(' ','_', $categorie->Description);
        $dir = ($mode == self::URL) ? '' : "http://".IRIS_ROOT_PATH;
        $dir .= $this->{$this->_dirField};
        $file = sprintf("%s%s/%s", $dir, $categorie, $this->_getFileName());
        return $file;
    }

    /**
     * Creates a new temporary file
     * and returns its name
     *
     * @param int $userId
     * @param string $dir
     * @return string temp file URL
     * @todo ENLEVER LES NOMS DE CATEGORIES
     */
    public function getHiddenFile($dir=NULL) {
        if (is_null($dir)) {
            $categorie = str_replace(' ','_',$this->_at_categorie_id->Categorie);
            $dir = $this->Folder.$categorie;
            $trans = new \Iris\Translation\UTF8($dir);
            $dir = $trans->noAccent()->spaceToUnderscore();
        }
        $ext = $this->_getFileExt();
        $id = $this->_getId();
        $filename = sprintf("/!documents/file/read/%06d%s/%s", self::$_Num,$dir,$this->_getFileName());
        return $filename;
    }

    /**
     * Permet de modifier le nom du champ qui contient le nom du
     * fichier téléversé (par défaut File)
     *
     * @param <string> $nameField le nom du champ
     */
    public function setNameField($nameField) {
        $this->_nameField = $nameField;
    }

    /**
     * Permet de modifier le nom du champ qui contient le nom du 
     * répertoire de destination finale (par défaut Folder)
     * 
     * @param <string> $dirField le nom du champ
     */
    public function setDirField($dirField) {
        $this->_dirField = $dirField;
    }

    public function validate($valide) {
        $visible = $this->normalFileName(self::PATH);
        $invisible = $this->newFileName();
        if ($valide) {
            // eviter les doublons
            while (file_exists($visible)) {
                $visible .='_';
            }
            self::moveFile($invisible, $visible);
        } else {
            self::moveFile($visible, $invisible);
        }

        $this->Statut = $valide;
        $this->save();
    }

    /**
     * Renvoie la valeur de la clé primaire simple du document
     * en tenant compte du champ précisé dans la classe associée
     * à la table (par défaut c'est le champ id)
     *
     * @return <mixed> valeur de la clé primaire du document
     */
    private function _getId() {
        $idValues = $this->primaryKeyValue();
        $ids = array_values($idValues);
        return $ids[0];
    }
    

    /**
     * Donne le nom du fichier téléchargé associé au document
     * @return <string> le nom du fichier
     */
    private function _getFileName() {
        return $this->{$this->_nameField};
    }

    /**
     * Déterminer l'extension du document
     *
     * @return <string> l'extension du fichier (sans point)
     */
    private function _getFileExt() {
        return pathinfo($this->_getFileName(), PATHINFO_EXTENSION);
    }

//    public static function CleanTempDir($user, $tempdir=null) {
//        if (is_null($tempdir)) {
//            $tempdir = self::DEF_DIR;
//        }
//        if (self::$_user == 0) {
//            $commande = "rm " . PUBLIC_PATH . $tempdir . "/{$user}_*";
//            system($commande);
//            self::$_user = $user;
//        }
//    }
//
//    
}


