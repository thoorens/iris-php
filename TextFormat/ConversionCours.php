<?php
namespace TextFormat;

/**
 * Conversion de MarkDown en HTML avec quelques codes en plus
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 */
class ConversionCours extends _ConversionMD implements \Iris\Design\iSingleton {

    /**
     * Un texte commmun désigné par son numéro
     * <#123#>
     */
    const RE_COMMUN = '/<#([0-9]*)#>/';

    const LANGUE = <<< FIN
Il existe une série de cours $2 
dans nos formations. Nous vous conseillons de choisir votre formation 
selon le niveau correspondant à vos connaissances actuelles et en fonction de 
vos disponibilités d'horaire. 
    
Il vous est fortement recommandé de rencontrer 
un ou plusieurs professeurs pour vous aider dans votre choix.
FIN;

    /**
     * La fonction de conversion proprement dite (elle est dépendante 
     * de la sous-classe)
     * 
     * @param string $texte Le texte à convertir
     * @param Formation $formation L'id de la formation
     * @return string Le texte converti
     */
    public function convert($texte, $formation) {
        $coursId = $formation->getId();
        $this->_getShared($texte);
        $this->_getImages($texte, $coursId, 'cours/');
        //$texte = preg_replace('/{([0-9]*?)}/', '<span class="todo">TODO $1</span>', $texte);
// Liens interne: exemple {nomCourt, texte à afficher}
        $texte = preg_replace('/{(\w*?),(.+?)}/', '<a href="/cours/details/$1">$2</a>', $texte);


// Data de la base de données {XXX}
        if (preg_match_all('/{\w*}/', $texte, $matches)) {
            $this->_data($texte, $matches[0], $coursId);
        }
// Cours de langue
        if (preg_match('/{langue\/.*}/', $texte, $matches)) {
            $texte = preg_replace('/{(langue)\/(.+?)}/', self::LANGUE, $texte);
        }

        // Standard Markdown
        return \Vendors\Markdown\MarkdownExtra::defaultTransform($texte);
    }

    /**
     * 
     * @param type $match
     * @param type $coursId
     * @return string
     */
    protected function _getData($match, $coursId) {
        list($field, $offset) = $this->_field($match);
        
        $programme = \models\TProgrammes::GetEntity()->where('cours_id=', $coursId)->fetchRow();
        $titulaire = $programme->_at_titulaire_id;
        $cours = \models\TCours::GetEntity()->find($coursId);
        switch ($field) {
            case 'Titulaire':
                //iris_debug($cours);
                $data = '<b>'.$titulaire->Prenom . ' ' . $titulaire->Nom . '</b>';
                break;
            case 'Courriel':
                $data = \Iris\views\helpers\_ViewHelper::HelperCall('secureMail', [$titulaire->Courriel]);
                break;
            case 'SiteWeb':
                $data = \Iris\views\helpers\_ViewHelper::HelperCall('link', [$titulaire->SiteWeb]);
                break;
            case 'Jour':
                $data = Week::Jours($programme->Jour);
                break;
            case 'Telephone':
                $data = explode('|', $titulaire->Telephone . '|')[$offset];
                break;
            case 'Horaire':
                $data = explode('|', $programme->Horaire . '|')[$offset];
                break;
            case 'Maitre':
                $data = $this->_master($cours);
                break;
            case 'Enfants':
                $data = $this->_children($cours);
                break;
            case 'Aussi':
                $aussi = $programme->VoirAussi;
                $data = '<b>Voir aussi :</b> ' . \Iris\views\helpers\_ViewHelper::HelperCall('link', ['un autre groupe pour ce cours','/cours/details/' . $aussi]);
                break;
            case 'Autre' :
                $autre = $programme->VoirAussi;
                $data = '<b>Voir aussi :</b> ' . \Iris\views\helpers\_ViewHelper::HelperCall('link', ['un autre cours sur une thème proche','/cours/details/' . $autre]);
                break;
            case 'Partie':
                $aussi = $programme->VoirAussi;
                $data = '<b>Voir aussi :</b> ' . \Iris\views\helpers\_ViewHelper::HelperCall('link', ['une autre partie de ce cours','/cours/details/' . $aussi]);
                break;
            case 'B':
                break;
            default: // Horaire/Reprise/PrixSeance/VoirAussi
                $data = "<b>" . $programme->$field . "</b>";
                break;
        }
        return $data;
    }

    /**
     * Analyse du nom du champ : si le dernier caractère est un chiffre, en faire un offset
     * 
     * @param type $match
     * @return type
     */
    private function _field($match){
        // enlever les acollades
        $field = substr($match, 1, strlen($match) - 2);
        $lenField = strlen($field);
        if (is_numeric($field[$lenField - 1])) {
            $offset = $field[$lenField - 1] - 1;
            $field = substr($field, 0, $lenField - 1);
            echo $field.' - '.$offset;
        }
        else {
            $offset = 0;
        }
        return [$field, $offset];
    }
    
    /**
     * Affichage des enfants d'un maître. 
     * 
     * @param type $cours
     * @param type $enfants
     * @return type
     */
    private function _children($cours) {
        // s'il n'y pas d'enfant, on cherche les enfants du cours courant, qui 
        // est probablement le maître
        $enfants = $cours->_children_cours__master_id;
        foreach ($enfants as $enfant) {
            $label = \Iris\views\helpers\_ViewHelper::HelperCall('lienSousCours', [$enfant]);
            $url = "/cours/details/" . $enfant->NomCourt;
            $liens[] = "<a href=\"$url\">$label</a>\n";
        }
        if (count($enfants)) {
            return "###Choix du groupe ou du niveau\n\n" . "<ul>\n" . implode("\n", $liens) . "</ul>\n";
        }
        else {
            return '';
        }
    }

    private function _master($cours) {
        $cours = $cours->_at_master_id->NomCourt;
        $preposition = $this->_preposition('de', $cours);
        $chaine = sprintf("Consultez la [présentation générale](/main/cours/details/%s) des cours %s%s à l'U3A.", $cours, $preposition, $cours);
        return $chaine;
    }

    private function _preposition($preposition, &$cours) {
        $cours[0] = strtolower($cours[0]);
        if (strpos('aeiouéèâ', $cours[0]) !== \FALSE) {
            switch ($preposition) {
                case 'de':
                    $preposition = "d'";
                    break;
            }
        }
        else {
            $preposition .= ' ';
        }
        return $preposition;
    }

    /**
     * 
     * @param string $texte
     * @return string
     */
    protected function _getShared(&$texte) {
        
        if (preg_match_all(self::RE_COMMUN, $texte, $matches)){
            foreach($matches[1] as $match){
                $message = $this->_getTexteCommun($match);
                $texte = str_replace("<#$match#>", $message, $texte);
            }
        }
    }
    
    /**
     * Récupère un texte commun à plusieurs cours en fonction de son numéro
     * 
     * @param int $numero
     * @return string
     */
    private function _getTexteCommun($numero){
        $eProgrammes =\models\TProgrammes::GetEntity();
        $prog = $eProgrammes->find($numero);
        $texte = '';
        if(count($prog)){
            $texte = "<div class=\"extension\">\n";
            $texte .= \Vendors\Markdown\MarkdownExtra::defaultTransform($prog->Contenu);
            $texte .= "\n</div>\n";
        }
        return $texte;
    }

    public static function GetInstance() {
        
    }

    protected function _table($params) {
        
    }

}
