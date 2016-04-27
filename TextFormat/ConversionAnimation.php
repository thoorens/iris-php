<?php

namespace TextFormat;

/**
 * Conversion de MarkDown en HTML avec quelques codes en plus
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 */
class ConversionAnimation extends _ConversionMD {

    const NEXTANIM = '__NEXT__';

    public function convert($texte, $objet) {
        $eventId = $objet->id;
        //$texte = $this->_getCommunes($texte);
        $this->_getImages($texte, $eventId, 'evenements/');
        $this->_getLink($texte, $eventId, 'evenements/');
        return \Vendors\Markdown\MarkdownExtra::defaultTransform($texte);
    }

    protected function _getCommunes($texte) {
        if (preg_match_all('/<![0-9]*!>/', $texte, $matches)) {
            iris_debug($matches);
        }
        return $texte;
    }

    /**
     * Renvoie un bouton ou un lien vers un sommaire
     * 
     * @param boolean $button
     * @param string[] $array Les paramètres sous forme de tableau
     * @return \Iris\Subhelpers\Link|\Dojo\Subhelpers\Button
     */
    protected function _animationMere($array, $button) {
        list($label, $toc, $title) = $array;
        $evenement = \models\TAnimations::GetEntity()
                ->where('Groupe = ', "M_$toc")
                ->fetchRow();
        $link = [$label, '/animations/details/' . $evenement->id, $title];
        if ($button) {
            $link = new \Dojo\Subhelpers\Button($link);
        }
        else {
            $link = new \Iris\Subhelpers\Link($link) . '';
        }
        return $link->__toString();
    }

    /**
     * Gestion d'une table
     * 
     * @param string[] $params
     * @return \Iris\Subhelpers\Table
     */
    protected function _table($params) {
        list($famille, $titre1, $titre2) = $params;
        $animations = \models\TAnimations::GetFamille($famille, $titre1, $titre2);
        foreach ($animations as $animation) {
            $date = new \Iris\Time\Date($animation[1]);
            $lien = new \Iris\Subhelpers\Link([$animation[2], '/animations/details/' . $animation[0]]);
            $animation3 = preg_replace('/(<\|.*\|>)/', '', $animation[3]);
            $details = ":" . \Vendors\Markdown\MarkdownExtra::defaultTransform($animation3) . ":";
            $next = $animation[4] == 1 ? self::NEXTANIM : "____";
            $data[] = [$date->toString('j M'), $lien->__toString(), $animation3, $next];
        }
        \Iris\Subhelpers\Table::SetSpecial(self::NEXTANIM);
        $table = new \Iris\Subhelpers\Table('Groupe', 3, 10, 'simple');
        $titres = [explode('_', \models\TAnimations::TITRE)];
        $table->setHeadCells($titres)
                ->setHead(\TRUE)
                ->setFormated()
                ->setContent($data);
        return $table . "";
    }

    /**
     * Nothing to do 
     * 
     * @param type $match
     * @param type $animation
     */
    protected function _getData($match, $animation) {
        
    }

}
