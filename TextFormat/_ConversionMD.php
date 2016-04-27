<?php

namespace TextFormat;

/**
 * Conversion de MarkDown en HTML avec quelques codes en plus
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 */
abstract class _ConversionMD implements \Iris\Design\iSingleton {

    use \Iris\Engine\tSingleton;

    const PDF = 'p';
    const BUTTONPDF = 'P';
    const MERE = 'm';
    const BUTTONMERE = 'M';
    const BUTTONEXPANSION = 'E';
    const LINK = 'l';
    const BUTTONLINK = 'L';
    const TABLEAU = 'T';
    const IMAGEFOLDER = '/!documents/file/protected/images/';
    /*
     * Les expressions rationnelles
     */

    /**
     * Recherche des images 
     * <!fichier!> ou <!!fichier!!>
     */
    const REGEX_IMAGE = '/<!.*!>/';

    /**
     * fichier JPG dans le dossier du cours
     * <!01!> 
     */
    const REGEX_JPG1 = '/<!(.*)!>/';

    /**
     * fichier PNG dans le dossier du cours
     * <!!01!!>
     */
    const REGEX_PNG1 = '/<!!(.*)!!>/';

    /**
     * dossier + fichier JPG
     * <!dossier/fichier!>
     */
    const REGEX_PNG2 = '/<!!(.*)\/(.*)!!>/';

    /**
     * dossier + fichier PNG
     * <!!dossier/fichier!!>
     */
    const REGEX_JPG2 = '/<!(.*)\/(.*)!>/';

    /**
     * Lien vers un fichier externe:
     * <|label|url|title|> lien externe vers fichier
     * <|P|label|url|title|> variante
     * <|E|toc|title|>
     * 
     */
    const REGEX_ELINK = '/<\|(.*)\|>/';

    /**
     * format pour un lien interne 
     */
    const PDFLINK = "<a target=\"_new\" href=\"%s/%s\" %s>%s</a>";

    protected $_jours = [];
    private $_type;
    private $_folder = \NULL;

    /**
     * La fonction de conversion proprement dite (elle est dépendante 
     * de la sous-classe)
     * 
     * @param string $texte Le texte à convertir
     * @param int $formation L'id du contexte contenant (formation, ou événément
     * @return string Le texte converti
     */
    public abstract function convert($texte, $objet);

    /**
     * Traduction des liens images
     * 
     * @param string $texte Le texte en cours de modification
     * @param int $id L'id de l'objet fournisseur de donnée
     * @param string $dir Le dossier de base des images pour ce type de table
     * @return return
     */
    protected function _getImages(&$texte, $id, $dir) {
        // si le texte contient <!caractères!> une image est détectée
        if (preg_match_all(self::REGEX_IMAGE, $texte, $matches)) {
            // Remplacements à sous-répertoire explicite
            $imgDir2 = static::IMAGEFOLDER . $dir . '$1';
            $imageLink2 = '<img class="prog"  src="%s/$2.%s"/>';
            // <!!COURS/FICHIER!!>  vers cours/Fichier.png
            $texte = preg_replace(self::REGEX_PNG2, sprintf($imageLink2, $imgDir2, 'png'), $texte);
            // <!COURS/FICHIER!>  vers cours/Fichier.jpg
            $texte = preg_replace(self::REGEX_JPG2, sprintf($imageLink2, $imgDir2, 'jpg'), $texte);

            // Remplacement sans sous-répertoire
            $imgDir =   static::IMAGEFOLDER. $dir . $id;
            $imageLink = '<img class="prog"  src="%s/$1.%s"/>';
            // <!!FICHIER!!> : lien vers FICHIER.png
            $texte = preg_replace(self::REGEX_PNG1, sprintf($imageLink, $imgDir, 'png'), $texte);
            // <!FICHIER!> lien vers FICHIER.jpg
            $texte = preg_replace(self::REGEX_JPG1, sprintf($imageLink, $imgDir, 'jpg'), $texte);
        }
    }

    /**
     * 
     * @param type $texte
     * @param type $matches
     * @param type $id
     * @return type
     */
    protected function _data(&$texte, $matches, $id) {
        foreach ($matches as $match) {
            $texte = preg_replace("/$match/", $this->_getData($match, $id), $texte);
        }
        //return $newData;
    }

    protected function _getData($match, $id){
        return '';
    }

    protected function _animationMere($array, $button){
        return '';
    }

    protected function _getLink(&$texte, $id, $dir) {
        if ($this->_folder === \NULL) {
            $this->_folder = '/!documents/file/protected/';
        }
        $this->_type = self::PDF;
        $texte = preg_replace_callback(self::REGEX_ELINK, [$this, '_analyseLink'], $texte);
    }

    /**
     * Cette fonction analyse le lien (appel par callback dans _getLink())
     * 
     * @param string[] $matches
     * @return string
     */
    private function _analyseLink($matches) {
        $type = $this->_type;
        $params = explode('|', $matches[1] . '|||');
        $command = array_shift($params);
        // if the first is not a command, keep it as first
        if (strlen($command) !== 1) {
            array_unshift($params, $command);
        }
        else {
            $type = $command;
        }
        $new = "???";
        switch ($type) {
            // simple link or button
            case self::PDF:
                return $this->_simpleLink($params, \FALSE);;
            case self::BUTTONPDF:
                $new = $this->_simpleLink($params, \TRUE);
            case self::MERE:
                return $this->_animationMere($params, \FALSE);
            case self::BUTTONEXPANSION:
            case self::BUTTONMERE:
                return $this->_animationMere($params, \TRUE);
                break;
            case self::BUTTONLINK:
            case self::LINK:
                return $this->_link($type,$params);
            case self::TABLEAU:
                return $this->_table($params);
        }
        return $new;
    }

    protected abstract function _table($params);

    protected function _simpleLink($params, $button) {
        list($label, $url, $title) = $params;
        if ($title !== '') {
            $title = "title=\"$title\"";
        }
        return sprintf(self::PDFLINK, $this->_folder, $url, $title, $label);
    }

    protected function _link($type, $params) {
        $num = $params[1];
        $buttonData = ['Détails',"/animations/details/$num",'Voir détails'];
        if($type === self::BUTTONLINK){
            return \Iris\views\helpers\_ViewHelper::HelperCall('Button', $buttonData).'';
        }
        else{
            return \Iris\views\helpers\_ViewHelper::HelperCall('Link', $buttonData).'';
        }
    }

}
