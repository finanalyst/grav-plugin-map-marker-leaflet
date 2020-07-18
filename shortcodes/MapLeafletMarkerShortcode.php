<?php
namespace Grav\Plugin\Shortcodes;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class MapLeafletMarkerShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('a-markers', function(ShortcodeInterface $sc) {
            $s = $sc->getContent();
            $twig = $this->grav['twig'];
            // process any twig variables in the markercode
            $s = $twig->processString($s);
            $s = preg_replace('/\\<\\/?p.*?\\>/i','',$s);
            $json = json_decode( html_entity_decode($s) );
            if ( $json == Null || count($json) < 1)  {
                return Null; // Not valid json or empty array, so retun nothing. Only map will be shown
            }
            $params = $sc->getParameters();
            foreach ($params as $k => $v){
                if (is_string($v)) $params[$k] = $twig->processString($v);
            }
            $iDef = isset($params['icon']) ?: '';
            $dDef = array_key_exists('draggable', $params) && (empty($params['draggable'])  || $params['draggable'] == 'true' );
            $icDef = isset($params['iconColor']) ? $params['iconColor'] : 'white';
            $mDef = isset($params['markerColor']) ? $params['markerColor'] : 'blue';
            $tDef = '';
            $sDef = array_key_exists('spin', $params) && (empty($params['spin'])  || $params['spin'] == 'true' );
            $mks = [];
            foreach ($json as $k => $v ) {
                $mks[$k] = [
                    'lat' => $v->{'lat'},
                    'lng' => $v->{'lng'},
                    'title' => isset($v->{'title'})? htmlentities($v->{'title'}) : '',
                    'icon' => isset($v->{'icon'})? htmlentities($v->{'icon'}) : $iDef,
                    'drag' => isset($v->{'draggable'})? htmlentities($v->{'draggable'}) : $dDef,
                    'icol' => isset($v->{'iconColor'})? htmlentities($v->{'iconColor'}) : $icDef,
                    'mcol' => isset($v->{'markerColor'})? htmlentities($v->{'markerColor'}) : $mDef,
                    'spin' => isset($v->{'spin'})? htmlentities($v->{'spin'}) : $sDef,
                    'text' => isset($v->{'text'})? htmlentities($v->{'text'}) : $tDef
                ];
            }
            $output = $twig->processTemplate('partials/mapleafletmarker.html.twig',
                [
                    'mks' => $mks
                ]);
            return $output;
        });
    }
}
