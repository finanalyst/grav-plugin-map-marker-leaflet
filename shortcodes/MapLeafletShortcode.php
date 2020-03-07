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

            $provider = [];
            if (isset($config['providers'][$config['provider']])) {

                $providerFromList = $config['providers'][$config['provider']];
                if (!is_array($providerFromList['variants'])) {
                    $variant = '';
                }
                else {
                    if (isset($params['variant'])) {
                        $variant = $this->grav['twig']->processString($params['variant']);
                    // style is deprecated
                    } elseif (isset($params['style'])) {
                        $variant = $this->grav['twig']->processString($params['style']);
                    // m-style & t-style are deprecated configuration options
                    } else {
                        $variant = $config['variant'] ?? $config['t-style'] ?? $config['m-style'];
                    }

                    $variant = in_array($variant, $providerFromList['variants']) ? $variant : $providerFromList['default'];
                }

                $provider = [
                    'tilestanza' => $providerFromList['tilestanza'],
                    'attribution' => $providerFromList['attribution'],
                    'maxzoom' => $providerFromList['maxzoom'],
                    'apikey' => $providerFromList['maxzoom'],
                    'variant' => $variant
                ];
                
            }
            else {
                $provider = [
                    'tilestanza' => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                    'attribution' => 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                    'maxzoom' => 17
                ];
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
                array_merge(
                    $provider,
                    [
                        'mapname' =>  isset( $params['mapname'] )? $params['mapname'] : 'map',
                        'lat' => isset( $params['lat'] )? $params['lat'] : '51.505',
                        'lng' =>  isset( $params['lng'] )? $params['lng'] : '-0.09',
                        'zoom' => isset( $params['zoom'] )? $params['zoom'] : '13',
                        'width' => $width,
                        'height' => $height,
                        'classes' => isset( $params['classes'])? $params['classes'] : '',
                        'scale' => array_key_exists('scale', $params)? 'True' : '', # default is FALSE, when scale is not set
                        'markercode' => $markercode
                    ]
                ));
            return $output;
        });
    }
}
