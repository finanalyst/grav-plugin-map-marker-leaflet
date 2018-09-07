<?php
namespace Grav\Plugin\Shortcodes;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class MapLeafletShortcode extends Shortcode {
    public function init() {
        $this->shortcode->getHandlers()->add('map-leaflet', function(ShortcodeInterface $sc) {
            //add assets
            $this->grav['assets']->addJs("https://unpkg.com/leaflet@1.3.4/dist/leaflet.js");
            $this->grav['assets']->addCss("https://unpkg.com/leaflet@1.3.4/dist/leaflet.css");
            $twig = $this->twig;
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
            $output = $twig->processTemplate('partials/mapquest.html.twig',
                [
                    'apikey' => $this->grav['config']->get('plugins.map-leaflet.mapbox_api_key'),
                    'mapstyle' => $this->grav['config']->get('plugins.map-leaflet.mapbox_style'),
                    'mapname' =>  isset( $params['mapname'] )? $params['mapname'] : 'map',
                    'lat' => isset( $params['lat'] )? $params['lat'] : '51.505',
                    'lng' =>  isset( $params['lng'] )? $params['lng'] : '-0.09',
                    'zoom' => isset( $params['zoom'] )? $params['zoom'] : '13',
                    'width' => isset( $params['width'])? $params['width'] : '100%',
                    'width' => isset( $params['height'])? $params['height'] : '530px',
                    'markercode' => $markercode
                ]);
            return $output;
        });
    }
}
