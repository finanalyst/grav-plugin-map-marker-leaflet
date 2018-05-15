<?php
namespace Grav\Plugin\Shortcodes;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class MapQuestMarkerShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('marker', function(ShortcodeInterface $sc) {
            $s = $sc->getContent();
            $twig = $this->grav['twig'];
            // process any twig variables in the markercode
            $s = $twig->processString($s);
            $json = json_decode( html_entity_decode(preg_replace('/\<\/?p.*?\>|\n/i',' ',$s)) );
            $params = $sc->getParameters();
            foreach ($params as $k => $v){
                if (is_string($v)) $params[$k] = $twig->processString($v);
            }
            if (array_key_exists('array_of_hash', $params) ) {
                foreach ($json as $k => $v ) {
                    $points[$k][0] = $v->{'lat'};
                    $points[$k][1] = $v->{'lng'};
                    if (isset($v->{'title'})) $titles[$k] =  $v->{'title'}?:'';
                }
            } else $points = $json;
            $output = $twig->processTemplate('partials/mapquestmarker.html.twig',
                [
                    'points' => $points,
                    'titles' => $titles,
                    'primaryColor' =>  isset($params['primaryColor'] )?  $params['primaryColor'] : '#22407F',
                    'secondaryColor' => isset( $params['secondaryColor'] )? $params['secondaryColor'] : '#ff5998',
                    'shadow' =>  array_key_exists('shadow', $params),
                    'enum' =>  array_key_exists('enum', $params),
                    'size' => isset( $params['size'] )? $params['size'] : 'sm',
                    'type' => isset( $params['type'] )? $params['type'] : 'marker',
                    'symbol' =>  isset( $params['symbol'] )? $params['symbol'] : '',
                    'draggable' => array_key_exists('draggable',$params)
                ]);
            return $output;
        });
    }
}
