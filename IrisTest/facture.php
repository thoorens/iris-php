<?php



namespace IrisInternal\admin\controllers;

use \Iris\Users as u;

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

/*
 * Test class to be modified later
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

class facture extends \IrisInternal\main\controllers\_SecureInternal {

    public function indexAction($test=1, $param2=1) {
        \Iris\DB\_EntityManager::$entityPath = '\\IrisInternal\\models';
        switch ($test) {
            case 1:
                echo "Test d'une table simple (param2 =  numéro du client).<br/>";
                $clients = new \IrisInternal\models\TClients;
                $c = $clients->find($param2);
                echo "Nom du client $param2: $c->Nom<br/> ";
                break;
            case 2:
                echo "Test d'une table avec clé étrangère (param2 =  numéro de la facture).<br/>";
                $factures = \Iris\DB\_EntityManager::GetEntity('factures'); //new \IrisInternal\models\TFactures;
                $factures2 = \Iris\DB\_EntityManager::GetEntity('clients');
                $f = $factures->find($param2);
                echo "Facture $param2 ($f->Numero) destinée au client $f->Nom_at_client_id<br/>";
                break;
            case 3:
                echo "Test d'une table référencée (param2 = numero du client.<br/>";
                $clients = new \IrisInternal\models\TClients;
                $c = $clients->find($param2);
                echo "Le client: $c->Nom a les factures suivantes<br/> ";
                foreach ($c->_children_factures as $f) {
                    echo "Facture $f->id<br/>";
                }
                break;
            case 4:
                echo "Test avec une table pivot (param2 = numero de facture";
                $factures = new \IrisInternal\models\TFactures();
                $f = $factures->find($param2);
                echo "La facture $f->Numero comporte les articles suivants:<br/>";
                foreach($f->_children_lignes as $ligne){
                    echo $ligne->Description_at_article_id."<br/>";
                }
                break;
            case 5:
                echo "Modification d'un article<br/>";
                $factures = new \IrisInternal\models\TFactures();
                $f = $factures->find(1);
                echo "<ul>";
                foreach($f->_children_lignes as $ligne){
                    $a = $ligne->_at_article_id;
                    $a->Description = 'Artikel '.$a->id;
                    echo "<li>".$a->Description."</li>";
                }
                echo "</ul>";
                $f = $factures->find(2);
                echo "<ul>";
                echo "La facture $f->Numero comporte les articles suivants:<br/>";
                foreach($f->_children_lignes as $ligne){
                    echo "<li>".$ligne->Description_at_article_id."</li>";
                }
                echo "</ul>";
                break;
            case 301:
                $a = array('un' => 'one', 'deux' => 'two');
                echo implode('|', $a);
                break;
        }
        die('Fin normale du test.');
    }

}

?>
