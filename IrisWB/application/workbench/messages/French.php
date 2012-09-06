<?php



namespace workbench\messages;

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
 * In workbench this class provides explanation messages in french
 * for the tests
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

class French extends \Iris\Translation\_Messages {

    public function main_index_index() {
        echo <<< END
   <h2>Lancement des tests</h2>
<p>Les routines de test doivent se dérouler sans erreur. Elles manifestent que la
version en cours du framework fonctionne normalement.</p>
<p>Les noms de fichiers affichés sont toujours relatifs à la racine du site (celle 
qui contient les répertoires application, library et public).
</p>
<p class="errorTrue">
Un message d'erreur anormal figurera sur un fond d'écran de couleur rouge.
</p>
<p class="errorTest">
Les erreurs volontaires (test d'erreur) auront un fond d'écran de couleur rosée.
</p>
END;
    }

    /* =============================================================================
     * 4 levels for layout
     */

    public function testLayout_layout_applayout() {
        echo <<< END
   <h2>Test de niveau de définition des layouts</h2>
On voit ici un layout de niveau "application". Il concerne par défaut tout l'application.
END;
    }

    public function main_layout_module() {
        echo <<< END
   <h2>Test de niveau de définition des layouts</h2>
On voit ici un layout de niveau "module". Il concerne par défaut tous les contrôleurs du module <em>main</em>
END;
    }

    public function main_index_controller() {
        echo <<< END
   <h2>Test de niveau de définition des layouts</h2>
On voit ici un layout de niveau "contrôleur". Il concerne par défaut toutes les actions du contrôleur <em>main\index</em>
END;
    }

    public function main_layout_action() {
        echo <<< END
   <h2>Test de niveau de définition des layouts</h2>
On voit ici un layout de niveau "action". Il concerne la seule action atteinte par <em>/main/layout/action</em>. 
END;
    }

    /* ==============================================================================
     * Islets and subcontrollers
     */

    public function main_layout_basic() {
        echo <<< END
   <h2>Ecran principal</h2>
Cet écran vise à tester les appels de subcontrollers et d'islets depuis une méthode du module main.
En déplaçant la souris sur une zone, vous verrez s'afficher le nom du contrôleur qui la dirige.

END;
    }

    public function testlayout_layout_index() {
        echo <<< END
   <h2>Ecran principal</h2>
Cet écran vise à tester les appels de subcontrollers et d'islets depuis une méthode d'un module différent de 'main'.
En déplaçant la souris sur une zone, vous verrez s'afficher le nom du contrôleur qui la dirige.

END;

        /* ============================================================================================
         * Test of view scripts
         */
    }

    //
    public function main_views_index() {
        echo <<< END
   <h2>Script de vue implicite</h2>
<p>Ce script porte le nom de l'action choisie. </p>
<p>(Pas de possibilité de <i>partial</i> implicite)</p>
</p>
END;
    }

    public function main_views_explicit() {
        echo <<< END
   <h2>Script de vue explicite</h2>
<p>Le nom du script a été spécifié dans le même répertoire que celui qu'on aurait pu attendre implicitement. 
</p>
<p> Le <i>partial</i> qu'il
contient obéit aux mêmes règles (voir le blanc souligné dans le nom du fichier).</p>
END;
    }

    public function main_views_common() {
        echo <<< END
  <h2>Script de vue dans un autre répertoire</h2>
<p>Le script demandé se trouve dans un autre répertoire.</p>
<p>
Le <i>partial</i> qu'il
contient obéit aux mêmes règles (ici pas de blanc souligné dans le nom du fichier).</p>
END;
    }

    public function other_views_index() {
        echo <<< END
  <h2>Script de vue implicite dans un module</h2>
<p>Ce script porte le nom de l'action choisie. Pas de différence par rapport à main. </p>
<p>(Pas de possibilité de <i>partial</i> implicite)</p>
END;
    }

    public function other_views_explicit() {
        echo <<< END
  <h2>Script de vue explicite dans un module</h2>
<p>
Le nom du script a été spécifié dans le même répertoire que celui qu'on aurait pu attendre implicitement.
Pas de différence par rapport à main.</p>
<p>
Le <i>partial</i> qu'il
contient obéit aux mêmes règles (voir le blanc souligné dans le nom du fichier).</p>
END;
    }

    public function other_views_common() {
        echo <<< END
  <h2>Script de vue dans un autre répertoire d'un module</h2>
<p>Le script demandé se trouve dans un autre répertoire. Pas de différence par rapport à main.</p>
<p>Le <i>partial</i> qu'il
contient obéit aux mêmes règles (ici pas de blanc souligné dans le nom du fichier).</p>
END;
    }

    public function other_views_inheritedImplicit() {
        echo <<< END
  <h2>Héritage d'un script implicite de main</h2>
<p>
Si un script au nom implicite pas n'existe pas dans le module, il est repris dans main 
s'il y existe.</p>
<p>(Pas de possibilité de <i>partial</i> implicite)</p>
END;
    }

    public function other_views_inherited() {
        echo <<< END
  <h2>Héritage d'un script explicite de main</h2>
<p>Si un script au nom explicitement précisé n'existe pas, il est repris dans main 
s'il existe.</p>
<p>Le <i>partial</i> obéit aux mêmes règles (voir le blanc souligné dans le nom du fichier)</p>
END;
    }

    public function other_views_commonInherited() {
        echo <<< END
  <h2>Héritage d'un sous répertoire de main</h2>
<p>Un script de vue placé dans un répertoire sera éventuellement trouvé dans main,
s'il n'existe pas dans le module.</p>
<p></p>
END;
    }
    
    public function main_loop_simple() {
        echo <<< END
  <h2>Boucle simple (<i>loop</i>)</h2>
<p>Un tableau indicé ou non peut facilement être affiché avec une mise en page dans un <i>loop</i>.</p>
<p></p>
END;
    }
    
    public function main_loop_recursive() {
        echo <<< END
  <h2>Boucles récursives (<i>loop</i></h2>
<p>Un tableau complexe peut s'afficher peut s'afficher à l'aide de plusieurs <i>loops</i>.</p>
<p>Dans l'exemple, trois niveaux de <i>loop</i> gèrent les différents niveaux de titre et <ol>
<li> les colonnes et la couleur </li>
<li> les blocs de catégorie </li>
<li> l'affichage des données </li>
</ol>
<p></p>
END;
    }
    /* ============================================================================================
     * Errors
     */

    public function main_stupid_index() {
        $html = <<< END
  <h2>Module ou contrôleur inexistant</h2>
  En cas de module inexistant ou en cas de contrôleur inexistant dans un module existant (Iris-PHP n'est
  pas capable de distinguer l'une ou l'autre erreur), une routine d'erreur est déclenchée, qui essaie
  de donner un maximum d'information.
END;
        $html .= $this->_intentionalError();
        echo $html;
    }

    public function main_index_stupid() {
        $html = <<< END
  <h2>Action inexistante</h2>
      <p>
      Une action inexistante provoque un message d'erreur. Ce type d'erreur ne survient que lorsque le module et le 
      contrôleur ont été correctement chargés.
      </p>
END;
        $html .= $this->_intentionalError();
        echo $html;
    }

    public function errors_variables_view() {
        $html = <<< END
  <h2>Variable inexistante dans une vue</h2>
      <p>
      Trois variables sont définies. Une quatrième ne l'est pas. 
      </p>
END;
        $html .= $this->_intentionalError();
        echo $html;
    }
    public function errors_variables_partial() {
        $html = <<< END
  <h2>Variable inexistante dans un <i>partial</i></h2>
      <p>
      On fait référence à 'tes' au lieu de 'test' dans le partial.
      </p>
END;
        $html .= $this->_intentionalError();
        echo $html;
    }
    
    public function errors_variables_partialPrivate() {
        $html = <<< END
  <h2>Variable de vue dans un partial</h2>
      <p> Un partial a son propre espace de mémoire. Les variables de la vue qui le contient ne
      sont accessibles que si elles sont expicitement transmises dans l'appel du partial. Ici la var1iable
      <tt>var1</tt> a été passé en paramètre, mais pas <tt>var2</tt>.
      </p>
END;
        $html .= $this->_intentionalError();
        echo $html;
    }
    
    public function errors_variables_islet() {
        $html = <<< END
  <h2>Variable inexistante dans un îlot</h2>
      <p> L'erreur est détectée dans la vue. Elle emploie la variable 'text' qui n'est définie ni dans le contrôleur d'îlot, 
      ni dans le layout qui inclut l'îlot (comme deuxième paramètre de l'aide de vue <tt>islet()</tt>).
      </p>
END;
        $html .= $this->_intentionalError();
        echo $html;
    }
    
   
    
    
    
    public function errors_scripts_view() {
        $html = <<< END
  <h2>Script de vue inexistant</h2>
      <p>Un script n'a pas été trouvé.</p>
      <p>La recherche d'un script obéit à des règles complexes. Il peut être<ul>
      <li>implicite (porte le nom de l'action et se trouve dans le répertoire scripts du module</li>
      <li>explicite</li>
      <li>explictie dans un sous-répertoire</li>
      <li>implicite dans un module (possibilité d'héritage de main)</li>
      <li>explicite dans un module (possibilité d'héritage de main)</li>
      <li>explicite dans un ssous-répertoire d'un module (possibilité d'héritage de main)</li>
      </ul>
      </p>
END;
        $html .= $this->_intentionalError();
        echo $html;
    }
    
    public function errors_scripts_partial() {
        $html = <<< END
  <h2>Script partial inexistant</h2>
      <p>Un script partial n'a pas été trouvé.</p>
      <p>La recherche d'un script partial obéit à des règles complexes. Il peut être<ul>
      <li>explicite (son nom commence par '_' et il se situe dans le répertoire script du module courant</li>
      <li>explicite dans un sous-répertoire des scripts du module courant</li>
      <li>hérité du module main (dans 'scripts' et commence par'_')</li>
      <li>hérité d'un sous-répertoire de scripts du module main</li>
      </ul>
      </p>
END;
        $html .= $this->_intentionalError();
        echo $html;
    }
    
    
    
    /* ============================================================================================
     * End of tests
     */

    public function main_index_end() {
        echo <<< END
  <h2>Fin des tests</h2>
  Si vous êtes arrivés ici sans problème, vous possédez une version cohérente et fonctionnelle d'Iris-PHP.
  Les éventuelles modifications que vous y avez apportées par des classes personnelles ne semblent pas perturber le fonctionnement
  du framework.
END;
    }

}

