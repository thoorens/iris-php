<?php

namespace Iris\controllers\helpers;

/**
 * 
 */
class ExampleImages extends \Iris\controllers\helpers\_ControllerHelper {

    /**
     * Creates an array containing data for getting 5 images
     * 
     * @param boolean $jsonFormat If true, converts the array to JSON
     * @return string/array a JSON string or an array
     */
    public function help($jsonFormat = \TRUE) {
        $titles = ['Etretat (France)', 'Pastry (Alsace France)', 'Lama', 'Tramway (Lisboa)', 'Cabourg (France)'];
        for ($i = 1; $i < 6; $i++) {
            $images[] = [
                "large" => sprintf("/images/slideshow/image%02d.jpg", $i),
                "title" => $titles[$i - 1],
            ];
        }
        return $images;
    }

}