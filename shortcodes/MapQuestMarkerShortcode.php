<?php
namespace Grav\Plugin\Shortcodes;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class MapQuestMarkerShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('marker', function(ShortcodeInterface $sc) {
            $s = $sc->getContent();
            // process any twig variables in the markercode
            $s = $this->grav['twig']->processString($s);
            $json = html_entity_decode(preg_replace('/\<\/?p.*?\>|\n/i',' ',$s));
            $params = $sc->getParameters();
            $output = $this->twig->processTemplate('partials/mapquestmarker.html.twig',
                [
                    'points' => json_decode( $json ),
                    'primaryColor' =>  isset($params['primaryColor'] )?  $params['primaryColor'] : '#22407F',
                    'secondaryColor' => isset( $params['secondaryColor'] )? $params['secondaryColor'] : '#ff5998',
                    'shadow' =>  array_key_exists('shadow', $params),
                    'enum' =>  array_key_exists('enum', $params),
                    'size' => isset( $params['size'] )? $params['size'] : 'sm',
                    'type' => isset( $params['type'] )? $params['type'] : 'marker',
                    'symbol' =>  isset( $params['symbol'] )? $params['symbol'] : ''
                ]);
            return $output;
        });
    }
}
