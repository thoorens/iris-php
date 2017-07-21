<?php

namespace modules\helpers\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

class various extends _helpers {

    public function _init() {
        $this->setDefaultScriptDir('various');
    }

    /**
     * Will show how the controller helper are found
     * in the librairies
     */
    public function controllersAction() {
        // this controller helper is in main module
        $this->__computeVar = $this->compute(7, '+');
        // this controller helper is in current module
        $this->__localComputeVar = $this->localCompute(8, '*');
        // this controller helper is in current module and mask the one in main module
        $this->__localCompute2Var = $this->compute2(9, '-');
        // this controller helper is in Iris library
        $this->__systemComputeVar = $this->testCompute(10, '/');
        // this controller helper is in Iris library
        $this->__systemComputeVar2 = $this->iris_testCompute(11, '/');
        // this controller helper is in Dojo library
        $this->__libComputeVar = $this->dojo_compute(12, '-');
    }

    /**
     * Will show how view helper are found in the librairies
     */
    public function viewsAction() {
        // all stuff are in the view
    }

    public function multipleCSSAction() {
        $this->_setLayout('specialCSS');
        $arguments = "body{background-color:lightgreen}";
        $this->__styl = "li#ndis{display:none;}";
        \Iris\views\helpers\StyleLoader::FunctionCall(["st"=>$arguments]);
        \Iris\views\helpers\StyleLoader::FunctionCall(['css1.css']);
    }

    public function multipleJSAction() {
        $this->_setLayout('specialJS');
        \Iris\views\helpers\JavascriptLoader::FunctionCall('script1.js');
        \Iris\views\helpers\JavascriptLoader::FunctionCall(['test1', "alert('Message 1 sur 5');"]);
    }

    /**
     * A simple table
     * 
     * @todo Implement a simple example
     */
    public function tableAction() {
        $table1 = new \Iris\Subhelpers\Table();
        $table1 = \Iris\views\helpers\Table::FunctionCall('demo');
        $table1->setContent([
            ['un', 'deux'],
            ['one', 'two']
        ]); 
        $this->__table1 = $table1->render();
        
        $this->__vocabulary = [
            ['manger', 'to eat'],
            ['boire', 'to drink'],
            ['dormir', 'to sleep'],
            ['travailler', 'to work'],
            ['vivre', 'to live'],
        ];
        $this->__titles = [['french', 'english']];
        
       
        $this->__table2 = new \Iris\Subhelpers\Table();;
    }

    /**
     * A complete display of the french personal pronoun
     */
    public function pronounsAction() {
        $this->setViewScriptName('all');
        $table = new \Iris\Subhelpers\Table('democ');
        //\Iris\views\helpers\Table::FunctionCall()->SetColMark('-');
        //$table::SetRawMark('!');
        //$table::SetStyleSeperator('&');
        //$table::SetTableCSS('mytab.css');
        $table->head = \TRUE;
        //$table->cellTag = 'th';
        $table->setTitles([
                    [' ', '_', '1st pers', '2nd pers', '3rd pers', '_', '_'],
                    ['|', '_|', 'Male. & female', 'Mal. & fem', 'Male', 'Female.', 'Refl.']
                ])
                ->setContent([
                    ['0µSing', '0µSubject', 'je', 'tu', '2µil', '3µelle', ''],
                    ['|', '0µDir. Object', 'me', 'te', '2µle', '3µla', 'se'],
                    ['|', '0µInd. object', 'me', 'te', 'lui', '_', 'se'],
                    ['|', '0µRenf Compl. Attr.', 'moi', 'toi', '2µlui', '3µelle', 'soi'],
                    ['0µPlur.', '0µSubject', 'nous', 'vous', '2µils', '3µelles', ''],
                    ['|', '0µDir. Object', 'nous', 'vous', 'les', '_', 'soi'],
                    ['|', '0µInd. object', 'nous', 'vous', 'leur', '_', 'se'],
                    ['|', '0µRenf. Compl. Attr.', 'nous', 'vous', '2µeux', '3µelles', 'soi'],
                ])
                ->setCaption("French personal pronouns", \Iris\Subhelpers\Table::CAPTION_BOTTOM)
                //->setFormated(\TRUE)
                ->setClass('democ')
                ->setHeadBody(\TRUE);
        $this->__table = $table->__toString();
    }

}
