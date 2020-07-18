/**
 * @file map-marker-leaflet.js
 *
 *
 */
jQuery(document).ready(function () {

  $(".map_marker_leaflet_canvas").each( function(index) {

    var elemID = $(this).attr('id');
    var mapkey = elemID.replace(/^map_marker_leaflet_/, '');
    // is there really a map?
    if ( $("#map_marker_leaflet_" + mapkey).is('div') ) {
      var map_settings = map_marker_leaflet_settings[mapkey]['map_settings'];
      var map = L.map('map_marker_leaflet_' + mapkey).setView([map_settings.lat, map_settings.lng], map_settings.zoom);
      var tileOpts = {
        attribution: map_settings.attribution,
        maxZoom: map_settings.maxzoom
      };
      if ( map_settings.apikey ) {
        tileOpts.apikey = map_settings.apikey;
      }
      if ( map_settings.style ) {
        tileOpts.style = map_settings.style;
      }
      L.tileLayer(map_settings.tilestanza , tileOpts).addTo(map);
      // more goodies here
    }

  });

});
