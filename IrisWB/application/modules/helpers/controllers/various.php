<?php

namespace modules\helpers\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Various helpers <ul>
 * <li>welcome
 * <li>table
 * </ul>
 * @author jacques
 * @license not defined
 */
class various extends _helpers {

    /**
     * Different uses of welcome view helper
     */
    public function welcomeAction() {
        
    }

    /**
     * A simple table
     * 
     * @todo Implement a simple example
     */
    public function tableAction(){
        $this->setViewScriptName('all');
        $this->__table = '';
    }
    
    /**
     * A complete display of the french personal pronoun
     */
    public function pronounsAction() {
        $this->setViewScriptName('all');
        $table = new \Iris\Subhelpers\Table('Pronouns', 5, 10, 'show');
        $table->head = \TRUE;
        $table->cellTag = 'th';
        $table->setHeadCells([
                    ['', '1st pers ', '1st pers ', '2nd pers', '3rd pers'],
                    ['Mal. & fem', 'Mal. & fem', 'Mal', 'Fem.', 'Refl.']
                ])->setHeadSpan('C', 0, 0, 2)
                ->setHeadSpan('R', 0, 0, 2)
                ->setHeadSpan('C', 0, 0, 2)
                ->setHeadSpan('C', 0, 4, 2)
                ->setContent([
                    ['Sing', 'Subject', '!je!', '!tu!', '!il!', '!elle!', ''],
                    ['Dir. Object', '!me!', '!te!', '!le!', '!la!', '!se!'],
                    ['Ind. object', '!me!', '!te!', '!lui!', '!se!'],
                    ['Renf <br>Compl<br>Attr.', '!moi!', '!toi!', '!lui!', '!elle!', '!soi!'],
                    ['Plur.', 'Subject', '!nous!', '!vous!', '!ils!', '!elles!', ''],
                    ['Dir. Object', '!nous!', '!vous!', '!les!', '!soi!'],
                    ['Ind. object', '!nous!', '!vous!', '!leur!', '!se!'],
                    ['Renf <br>Compl<br>Attr.', '!nous!', '!vous!', '!eux!', '!elles!', '!soi!'],
                ])
                ->setBodySpan('R', 0, 0, 4)
                ->setBodySpan('R', 4, 0, 4)
                ->setBodySpan('C', 2, 3, 2)
                ->setBodySpan('C', 5, 3, 2)
                ->setBodySpan('C', 6, 3, 2)
                ->setCaption("French personal pronouns")
                ->setFormated(\TRUE);
        $this->__table = $table->__toString();
    }

}
