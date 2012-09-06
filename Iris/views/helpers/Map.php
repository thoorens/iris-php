<?php

namespace Iris\views\helpers;

/**
 * 
 *
 * This help offers a way to display an arrow in a map from Openstreemap.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * @todo Définir un sub helper pour cette aide
 */
class Map extends _ViewHelper {

    protected static $_Singleton = TRUE;
    private $_longitude = 0;
    private $_latitude = 0;
    private $_zoom = 13;
    private $_height = 500;
    private $_width = 500;
    private $_mapNumber = 1;
    private $_loaderEvent = 'bodyOnload';

    public function help($number) {
        $this->_mapNumber = $number;
        return $this;
    }

    /**
     * Prepares all javascript and CSS stuff to display the map whose
     * parameters have been previously set.
     */
    public function render($type=NULL) {
        $num = $this->_mapNumber;
        // code to execute at page load
//        if ($this->_loaderEvent == 'bodyOnload') {
//            $this->_view->bodyOnload("openLayersInit$num()");
//        }
//        else {
//            $this->_view->loaderEvent($this->_loaderEvent, "openLayersInit$num()");
//        }
        // file and code to insert in head part of the page
        $this->_view->javascriptLoader('openlayers', 'http://openlayers.org/api/OpenLayers.js');
        $this->_view->javascriptLoader("MainScript$num", <<<FIN
        function openLayersInit$num() {
        dojo.removeAttr( "mapdiv$num",'style');       
        map = new OpenLayers.Map("mapdiv$num");
        var mapnik = new OpenLayers.Layer.OSM();
        map.addLayer(mapnik);

        var lonlat = new OpenLayers.LonLat($this->_longitude,$this->_latitude).transform(
            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
            new OpenLayers.Projection("EPSG:900913") // to Spherical Mercator
          );

        var zoom = $this->_zoom;

        var markers = new OpenLayers.Layer.Markers( "Markers" );
        map.addLayer(markers);
        markers.addMarker(new OpenLayers.Marker(lonlat));

        map.setCenter(lonlat, zoom);
      }
FIN
        );

        $height = $this->_height . "px";
        $width = $this->_width . "px";
        $this->_view->styleLoader("MainStyle$num", <<<FIN2
    #mapdiv$num { width:$width; height:$height; }
    div.olControlAttribution { bottom:3px; }
FIN2
        );
    }

    public function __destruct() {
        for ($num = 1; $num <= $this->_mapNumber; $num++) {
            echo "<script> openLayersInit$num()</script>";
        }
    }

    /**
     * Accessor set for longitude 
     * 
     * @param float $longitude the value of the longitue
     * @return Map (fluent interface)
     */
    public function setLongitude($longitude) {
        $this->_longitude = $longitude;
        return $this;
    }

    /**
     * Accessor set for latitude
     * 
     * @param float $latitude the value of the latitude
     * @return Map (fluent interface)
     */
    public function setLatitude($latitude) {
        $this->_latitude = $latitude;
        return $this;
    }

    /**
     * Accessor set for zoom (default 13)
     * 
     * @param int $zoom the value of the zoom
     * @return Map (fluent interface)
     */
    public function setZoom($zoom) {
        $this->_zoom = $zoom;
        return $this;
    }

    /**
     * Accessor set for the height of the map (default : 500)
     * 
     * @param int $height the height of the map in pixels
     * @return Map (fluent interface) 
     */
    public function setHeight($height) {
        $this->_height = $height;
        return $this;
    }

    /**
     * Accessor set for the width of the map (default : 500)
     * 
     * @param int $width
     * @return Map  (fluent interface)
     */
    public function setWidth($width) {
        $this->_width = $width;
        return $this;
    }

    

    public function setLoaderEvent($loaderEvent) {
        $this->_loaderEvent = $loaderEvent;
        return $this;
    }

    

}

?>
