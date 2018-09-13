<?php
namespace Grav\Plugin\Shortcodes;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class MapLeafletShortcode extends Shortcode {
    public function init() {
        $this->shortcode->getHandlers()->add('map-leaflet', function(ShortcodeInterface $sc) {
            //add assets
            $assets = $this->grav['assets'];
            $assets->addJs("https://unpkg.com/leaflet@1.3.4/dist/leaflet.js");
            $assets->addCss("https://unpkg.com/leaflet@1.3.4/dist/leaflet.css");
            //add leaflet awesome assets
            $assets->addJs('plugin://map-leaflet/assets/leaflet.awesome-markers.js');
            $assets->addCss('plugin://map-leaflet/assets/leaflet.awesome-markers.css');
            $twig = $this->twig;
            $config = $this->config->get('plugins.map-leaflet');
            switch ($config['provider']) {
                case 'openstreetmap':
                    $tilestanza = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
                    $attribution = 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors';
                    $maxzoom = 17;
                    break;
                case 'thunderforest':
                    $tilestanza = "https://tile.thunderforest.com/" . $config['t-style'] . "/{z}/{x}/{y}.png?apikey=" . $config['apikey'] ;
                    $attribution =  'Maps &copy; <a href="www.thunderforest.com/">Thunderforest</a> Data &copy; <a href="www.opensteetmap.org/copyright">OpenStreetMap</a> contributors';
                    $maxzoom = 18;
                    break;
                case 'mapbox':
                    $tilestanza = "https://api.tiles.mapbox.com/v4/" . $config['m-style'] . "/{z}/{x}/{y}.png?access_token=" . $config['apikey'] ;
                    $attribution = 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, Imagery &copy; <a href="https://www.mapbox.com/">Mapbox</a>';
                    $maxzoom = 24;
            }
            $s = $sc->getContent();
            $markercode = '';
            if (is_string($s) ) {
                // process any twig variables in the markercode
                $s = $twig->processString($s);
                $markercode = html_entity_decode(preg_replace('/\<\/?p.*?\>/i',' ',$s));
            }
            $params = $sc->getParameters();
            foreach ($params as $k => $v) {
                if (is_string($v)) $params[$k] = $twig->processString($v);
            }
            $output = $twig->processTemplate('partials/mapleaflet.html.twig',
                [
                    'tilestanza' => $tilestanza,
                    'attribution' => $attribution,
                    'maxzoom' => $maxzoom,
                    'mapname' =>  isset( $params['mapname'] )? $params['mapname'] : 'map',
                    'lat' => isset( $params['lat'] )? $params['lat'] : '51.505',
                    'lng' =>  isset( $params['lng'] )? $params['lng'] : '-0.09',
                    'zoom' => isset( $params['zoom'] )? $params['zoom'] : '13',
                    'width' => isset( $params['width'])? $params['width'] : '100%',
                    'height' => isset( $params['height'])? $params['height'] : '530px',
                    'markercode' => $markercode
                ]);
            return $output;
        });
    }
}
