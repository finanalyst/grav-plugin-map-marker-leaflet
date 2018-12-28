<?php
namespace Grav\Plugin\Shortcodes;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class MapLeafletShortcode extends Shortcode {
    public function init() {
        $this->shortcode->getHandlers()->add('map-leaflet', function(ShortcodeInterface $sc) {
            $s = $sc->getContent();
            $params = $sc->getParameters();
            $twig = $this->twig;
            $config = $this->config->get('plugins.map-marker-leaflet');
            if (isset($params['style'])) {
                $style = $this->grav['twig']->processString($params['style']);
            } else $style = '';
            switch ($config['provider']) {
                case 'openstreetmap':
                    $tilestanza = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
                    $attribution = 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors';
                    $maxzoom = 17;
                    $style = '';
                    $apikey = '';
                    break;
                case 'thunderforest':
                    $tilestanza = "https://tile.thunderforest.com/{style}/{z}/{x}/{y}{r}.png?apikey={apikey}";
                    $attribution =  'Maps &copy; <a href="www.thunderforest.com/">Thunderforest</a> Data &copy; <a href="www.opensteetmap.org/copyright">OpenStreetMap</a> contributors';
                    $maxzoom = 18;
                    $apikey = $config['apikey'];
                    $opts = [
                        'cycle' ,
                        'transport' ,
                        'landscape' ,
                        'outdoors' ,
                        'transport-dark' ,
                        'spinal-map' ,
                        'pioneer' ,
                        'mobile-atlas' ,
                        'neighbourhood'
                    ]; // this should be taken from a yaml config. Hard code for the time being.
                    if ( ! $style || ! in_array( $style, $opts) ) $style = $config['t-style'];
                    break;
                case 'mapbox':
                    $tilestanza = "https://api.tiles.mapbox.com/v4/{style}/{z}/{x}/{y}.png?access_token={apikey}";
                    $attribution = 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, Imagery &copy; <a href="https://www.mapbox.com/">Mapbox</a>';
                    $maxzoom = 24;
                    $apikey = $config['apikey'];
                    $opts = [
                        'mapbox.streets' ,
                        'mapbox.outdoors' ,
                        'mapbox.light' ,
                        'mapbox.dark' ,
                        'mapbox.satellite'
                    ];
                    if ( ! $style || ! in_array( $style, $opts) ) $style = $config['m-style'];
            }
            $markercode = '';
            if (is_string($s) ) {
                // process any twig variables in the markercode
                $s = $twig->processString($s);
                $markercode = html_entity_decode(preg_replace('/\<\/?p.*?\>/i',' ',$s));
            }
            foreach ($params as $k => $v) {
                if (is_string($v)) $params[$k] = $twig->processString($v);
            }
            if (isset( $params['classes'] ) ) {
                $width = $height = '';
            } else {
                $height = isset( $params['height'])? $params['height'] : '530px';
                $width = isset( $params['width'])? $params['width'] : '100%';
            }
            $output = $twig->processTemplate('partials/mapleaflet.html.twig',
                [
                    'tilestanza' => $tilestanza,
                    'attribution' => $attribution,
                    'maxzoom' => $maxzoom,
                    'apikey' => $apikey,
                    'style' => $style,
                    'mapname' =>  isset( $params['mapname'] )? $params['mapname'] : 'map',
                    'lat' => isset( $params['lat'] )? $params['lat'] : '51.505',
                    'lng' =>  isset( $params['lng'] )? $params['lng'] : '-0.09',
                    'zoom' => isset( $params['zoom'] )? $params['zoom'] : '13',
                    'width' => $width,
                    'height' => $height,
                    'classes' => isset( $params['classes'])? $params['classes'] : '',
                    'scale' => array_key_exists('scale', $params)? 'True' : '', # default is FALSE, when scale is not set
                    'markercode' => $markercode
                ]);
            return $output;
        });
    }
}
