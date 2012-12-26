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
        $this->__numbers = [1, "two", "trois", "cuatro", "fünf"];
    }

    public function assocAction() {
        $this->__numberTranslations = [
            'un' => 'uno',
            'deux' => 'dos',
            'trois' => 'tres',
            'quatre' => 'cuatro',
            'cinq' => 'cinco',
        ];
    }

    public function recursiveAction() {
        $words['Français'] = [
            'color' => 'BLUE1',
            'categories' => [
                'Animaux' => ['chat', 'chien', 'cheval'],
                'Nourriture' => ['pain', 'fromage', 'poisson'],
                'Couleurs' => ['rouge', 'vert', 'bleu'],
            ]
        ];
        $words['English'] = [
            'color' => 'GREEN1',
            'categories' => [
                'Animals' => ['cat', 'dog', 'horse'],
                'Food' => ['bread', 'cheese', 'fish'],
                'Colors' => ['red', 'green', 'blue'],
            ]
        ];
        $words['Español'] = [
            'color' => 'ORANGE1',
            'categories' => [
                'Animales' => ['gato', 'perro', 'caballo'],
                'Comida' => ['pan', 'queso', 'pescado'],
                'Colores' => ['rojo', 'verde', 'azul'],
            ]
        ];
        $this->__words = $words;
    }

}
