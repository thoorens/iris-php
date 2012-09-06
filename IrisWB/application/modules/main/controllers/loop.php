<?php

namespace modules\main\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Test of Loops
 * 
 * @author jacques
 * @license not defined
 */
class loop extends _main {

    public function simpleAction() {
        $this->__array = array(1,"two","trois","cuatro","fünf");
        
    }
    
    public function assocAction(){
        $this->__array = array(
            'un' => 'uno',
            'deux' => 'dos',
            'trois' => 'tres',
            'quatre' => 'cuatro',
            'cinq' => 'cinco',
            );
    }
    
    public function recursiveAction(){
        $data['Français'] = array(
            'color' => 'BLUE1',
            'categories' => array(
                'Animaux' => array('chat','chien','cheval'),
                'Nourriture' => array('pain','fromage','poisson'),
                'Couleurs' => array('rouge','vert','bleu')
            )
        );
        $data['English'] = array(
            'color' => 'GREEN1',
            'categories' => array(
                'Animals' => array('cat','dog','horse'),
                'Food' => array('bread','cheese','fish'),
                'Colors' => array('red','green','blue')
            )
        );
        $data['Español'] = array(
            'color' => 'ORANGE1',
            'categories' => array(
                'Animales' => array('gato','perro','caballo'),
                'Comida' => array('pan','queso','pescado'),
                'Colores' => array('rojo','verde','azul')
            )
        );
        $this->__data = $data;
    }

}
