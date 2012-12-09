<?php

namespace Iris\views\helpers;

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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */

/**
 * Generates some sentences or paragraph with random text (called Lorem ipsum
 * by typographs)
 *
 */
class LoremIpsum extends _ViewHelper {

    /**
     * The array containing the gibberish sentences
     * 
     * @var array(string) 
     */
    private $_sentenceArray;
    
    /**
     *
     * @var int the number of gibberish sentences
     */
    private $_sentenceNumber;

    /**
     * Explodes the text of _getSentences method in an array
     */
    protected function _init() {
        $this->_sentenceArray = explode("\n", $this->_getSentences());
        $this->_sentenceNumber = count($this->_sentenceArray);
    }

    /**
     * 
     * @param mixed $items (number of sentences or paragraphs or array with item numbers)
     * @param boolean $lorem if true, the first sentence is the traditional one
     * @param boolean $paragraph if true, counts the paragraphs instead of sentences
     * @return string
     */
    public function help($items, $lorem = TRUE, $paragraph = FALSE) {
        if(is_array($items)){
            return $this->_fixed($items);
        }
        else{
            return $this->_lorem($items, $lorem, $paragraph);
        }
    }

    
    /*
     * @param int $items (number of sentences or paragraphs)
     * @param boolean $lorem if true, the first sentence is the traditional one
     * @param boolean $paragraph if true, counts the paragraphs instead of sentences
     * @return string
     */
    private function _lorem($items, $lorem = TRUE, $paragraph = FALSE){
        $text = '';
        if ($lorem) {
            $text = $this->_sentenceArray[0].' ';
            $items--;
        }
        if ($paragraph) {
            for ($p = 0; $p < $items; $p++) {
                $lines = rand(2, 6);
                $text .= $this->_paragraph($lines) . "<br/>\n";
            }
        }
        else {
            $text .= $this->_paragraph($items);
        }
        return $text;
    }
    
    public function _fixed($items) {
        foreach($items as $item){
            $text[] = $this->_sentenceArray[$item % $this->_sentenceNumber];
        }
        return implode(' ',$text);
    }
    
    /**
     * Generates a paragraph
     * 
     * @param int $items the number of sentences in the paragraphe
     * @return string
     */
    private function _paragraph($items) {
        $text = '';
        for ($i = 0; $i < $items; $i++) {
            $text .= $this->_sentenceArray[rand(1, $this->_sentenceNumber - 1)] . " \n";
        }
        return $text;
    }

    /**
     * Contents a long string with 100 gibberish sentences separated by CR
     * @return string
     */
    private function _getSentences() {
        return <<<END
Lorem ipsum dolor sit amet, consectetur adipiscing elit.
Morbi volutpat risus a arcu mollis rhoncus.
Quisque interdum lorem vel orci fermentum pretium.
Duis eu elit nec urna iaculis consequat.
Integer quis arcu at libero rhoncus molestie a non odio.
Proin porta nibh quis velit porta a venenatis lectus aliquet.
Aenean rutrum tristique lectus, at dignissim justo hendrerit ut.
Quisque consequat mattis orci, sed aliquet justo luctus id.
Sed at odio a odio cursus pellentesque.
Sed consectetur sapien commodo eros aliquet iaculis.
Fusce sed magna id dolor pellentesque luctus.
Proin ac sem bibendum dolor dignissim facilisis.
Vestibulum vel nisl vel magna euismod tempus.
Nulla venenatis nisl eget elit feugiat eleifend.
Fusce tincidunt massa non mauris imperdiet eget dapibus neque euismod.
Pellentesque eleifend purus vitae quam tristique a fringilla quam faucibus.
Aenean vel augue ac diam ultrices condimentum.
Nullam scelerisque nibh at justo fermentum rhoncus.
Integer nec nibh lacus, eu malesuada elit.
Ut luctus faucibus magna, ut semper sapien malesuada vel.
Proin imperdiet turpis egestas nibh mollis quis malesuada ipsum auctor.
Sed venenatis magna interdum magna pellentesque gravida.
Integer tincidunt eros eget tellus congue consectetur.
Praesent a arcu eu massa suscipit convallis.
Quisque congue ultrices nisi, sit amet consequat mauris fermentum a.
Suspendisse ut tortor ut tortor tincidunt fringilla vitae nec velit.
Vestibulum eget tellus lacus, id commodo dolor.
Donec eu mi leo, et pharetra odio.
Duis at tortor quis arcu sodales mattis mattis eu nunc.
Nam et nulla eget enim sollicitudin pulvinar.
Suspendisse lacinia elit eget nisl posuere porta.
Donec convallis quam eget sapien sollicitudin a aliquam nisl commodo.
Nullam volutpat dolor sed tellus vulputate vitae venenatis orci fermentum.
Aenean eget leo ornare mi consectetur bibendum eget non enim.
Aliquam condimentum diam a arcu imperdiet tincidunt.
Suspendisse fringilla eros at nibh porttitor id pulvinar justo porta.
Pellentesque non mi eget arcu hendrerit tincidunt non vitae felis.
Nunc sit amet massa nulla, sed aliquet ipsum.
Mauris sed nisl erat, eu pharetra justo.
Sed et felis felis, vitae rhoncus est.
Nam rutrum imperdiet augue, non iaculis urna semper imperdiet.
Maecenas sagittis eros et lacus tristique non porta leo laoreet.
Sed eu nibh at mauris faucibus tempus non vel purus.
Praesent lacinia felis ac nibh ullamcorper sed consectetur lorem facilisis.
Integer eget velit ipsum, quis ullamcorper sem.
Ut in mi mauris, eget tempor ante.
Donec id risus nunc, sed condimentum eros.
In eu velit lectus, vitae ultrices arcu.
Nunc vel felis quam, quis dictum leo.
Quisque facilisis est at sapien eleifend rutrum tincidunt risus mollis.
Cras varius felis vel risus malesuada imperdiet.
Phasellus non urna nibh, vel tincidunt turpis.
Ut convallis mi ac lectus venenatis commodo.
Nunc varius magna nec tortor elementum et pharetra tortor scelerisque.
Praesent scelerisque tellus eget quam volutpat feugiat.
Vestibulum viverra tincidunt diam, ut mollis odio pellentesque quis.
Ut consequat enim sit amet nibh venenatis nec blandit justo gravida.
Nunc posuere aliquam felis, sit amet molestie neque adipiscing sed.
Suspendisse dictum ligula nec metus dignissim sed eleifend sapien vehicula.
Maecenas aliquam enim at neque imperdiet eleifend.
Ut luctus hendrerit nunc, et congue turpis laoreet quis.
Phasellus faucibus nibh at tellus aliquet ut interdum ligula pretium.
Pellentesque et odio purus, nec pretium magna.
Aliquam eget tellus nec libero tempus aliquet.
Ut suscipit condimentum augue, quis blandit tellus lacinia ac.
Vivamus ornare augue at dui interdum tristique.
Praesent tempus est sit amet mi sollicitudin ac molestie mi porttitor.
Quisque blandit felis sagittis velit scelerisque posuere.
Integer aliquam interdum urna, ac accumsan enim bibendum vel.
Donec id tortor dolor, eu vehicula dui.
Maecenas id tortor dictum nibh tempus pellentesque.
Etiam sodales velit vitae nibh euismod mollis.
Duis semper orci et mauris mollis semper.
Donec nec risus nisi, eget aliquam urna.
Nulla ac libero placerat urna faucibus dapibus eget eu elit.
Maecenas eget odio sed massa tempus dapibus.
Maecenas eu eros sed est accumsan pellentesque ac ut neque.
Cras a turpis quis orci porta porta nec id mi.
Duis posuere purus vitae ante mattis non ultricies tellus imperdiet.
Maecenas at enim porttitor massa accumsan interdum.
Proin eget erat feugiat urna pulvinar faucibus.
Cras ut justo ac nibh hendrerit ultrices sit amet a lacus.
Vestibulum semper viverra neque, at mollis lacus aliquam auctor.
Suspendisse ullamcorper nisi quis eros vehicula vel laoreet sapien malesuada.
Mauris pellentesque tortor vel dolor dapibus quis porta urna vulputate.
Donec tincidunt magna ut eros auctor eget pretium erat condimentum.
Phasellus consequat sem posuere magna ornare ac commodo orci varius.
Maecenas eget est eu risus ornare posuere.
Nunc ut velit ac quam condimentum rhoncus.
Sed eu risus ut purus commodo euismod.
Cras mattis urna a nibh vehicula iaculis.
Etiam ultricies augue in nulla tristique id ornare nibh vestibulum.
Sed tincidunt orci sit amet justo hendrerit sit amet bibendum lorem vestibulum.
Nam non eros massa, in mollis tellus.
Morbi aliquet purus vel mauris volutpat in tristique nisl mollis.
Phasellus quis velit ut magna fermentum venenatis.
Ut ac lorem ligula, eget sagittis massa.
Vivamus eu urna quis sapien faucibus sollicitudin at sed arcu.
Praesent posuere est non libero mattis tristique.
Sed eget dui vitae risus consectetur rhoncus interdum vitae libero.
Etiam nec risus a sem venenatis dictum ac at mauris.
Ut laoreet nulla eget est egestas facilisis.
Integer in nisl vitae leo facilisis placerat.
Maecenas malesuada tellus ac mi eleifend faucibus.
Mauris imperdiet odio nec dolor lacinia adipiscing.
Pellentesque scelerisque sem a purus tristique quis sodales dolor sodales.
Morbi eu erat mi, a faucibus urna.
In non mi imperdiet nulla aliquam adipiscing.
Integer mattis lorem ac nisl aliquet ac mollis arcu ultrices.
Nulla vestibulum convallis felis, a sollicitudin dui semper vel.

END;
    }

    

}

?>
