<?php
namespace Grav\Plugin\Shortcodes;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class MapQuestShortcode extends Shortcode {
    public function init() {
        $this->shortcode->getHandlers()->add('map-quest', function(ShortcodeInterface $sc) {
            $apikey = $this->grav['config']->get('plugins.map-quest.api_key');
            //add assets
            $this->grav['assets']->addJs("https://api.mqcdn.com/sdk/mapquest-js/v1.3.1/mapquest.js");
            $this->grav['assets']->addCss("https://api.mqcdn.com/sdk/mapquest-js/v1.3.1/mapquest.css");
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
                    'apikey' => $apikey,
                    'mapname' =>  isset( $params['mapname'] )? $params['mapname'] : 'map',
                    'lat' => isset( $params['lat'] )? $params['lat'] : '37.7749',
                    'lng' =>  isset( $params['lng'] )? $params['lng'] : '-122.4194',
                    'zoom' => isset( $params['zoom'] )? $params['zoom'] : '15',
                    'markercode' => $markercode
                ]);
            return $output;
        });
    }
}
