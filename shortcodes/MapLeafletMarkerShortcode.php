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
            $s = preg_replace('/\\<\\/?p.*?\\>|\\n|\\s/i','',$s);
            $json = json_decode( html_entity_decode($s) );
            if ( $json == Null || count($json) < 1)  {
                return Null; // Not valid json or empty array, so retun nothing. Only map will be shown
            }
            $params = $sc->getParameters();
            foreach ($params as $k => $v){
                if (is_string($v)) $params[$k] = $twig->processString($v);
            }
            $titles = [];
            $points = [];
            $pc = [];
            $sc = [];
            $sz = [];
            if ( array_key_exists('array_of_hash', $params) && (empty($params['array_of_hash'])  || $params['array_of_hash'] == 'True' ) ) {
                foreach ($json as $k => $v ) {
                    $points[$k][0] = $v->{'lat'};
                    $points[$k][1] = $v->{'lng'};
                    if (isset($v->{'title'})) $titles[$k] =  htmlentities($v->{'title'}?:'');
                    if (isset($v->{'prim'})) $pc[$k] =  htmlentities($v->{'prim'}?:'');
                    if (isset($v->{'scnd'})) $sc[$k] =  htmlentities($v->{'scnd'}?:'');
                    if (isset($v->{'size'})) $sz[$k] =  htmlentities($v->{'size'}?:'');
                }
            } else $points = $json;
            $output = $twig->processTemplate('partials/mapquestmarker.html.twig',
                [
                    'points' => $points,
                    'titles' => $titles,
                    'pc' => $pc,
                    'sc' => $sc,
                    'sz' => $sz,
                    'primaryColor' =>  isset($params['primaryColor'] )?  $params['primaryColor'] : '#22407F',
                    'secondaryColor' => isset( $params['secondaryColor'] )? $params['secondaryColor'] : '#ff5998',
                    'size' => isset( $params['size'] )? $params['size'] : 'sm',
                    'shadow' =>  array_key_exists('shadow', $params) && (empty($params['shadow'])  || $params['shadow'] == 'True' ),
                    'enum' =>  array_key_exists('enum', $params) && (empty($params['enum'])  || $params['enum'] == 'True' ),
                    'type' => isset( $params['type'] )? $params['type'] : 'marker',
                    'symbol' =>  isset( $params['symbol'] )? $params['symbol'] : '',
                    'draggable' =>array_key_exists('draggable', $params) && (empty($params['draggable'])  || $params['draggable'] == 'True' )
                ]);
            return $output;
        });
    }
}
