# Map Leaflet Plugin

The **Map Leaflet** Plugin is for [Grav CMS](http://github.com/getgrav/grav). Embed a map with markers using fontawesome icons or letters/numbers. Uses open source [Leaflet js library](https://leafletjs.com) with data from [OpenStreetMap](https://www.openstreetmap.org). [Extra-Marker Leaflet plugin](https://github.com/coryasilva/Leaflet.ExtraMarkers) is used for the markers. Enhanced map styling can be obtained from  [Thunderforest](https://www.thunderforest.com) or [MapBox](https://www.mapbox.com), but requires an api key (royalty free options are available).


## Purpose

Google changed its policy - effective June 2018 - about maps so that a credit card account had to be provided to access parts of the map api. OpenStreetMap provides map data as a community service, and Leaflet provides an opensource js library to access the map data. Maps can be enhanced by other third-party providers, who - like Google (at the time of writing) - require an apikey, but unlike Google do not require bank details.

This plugin was written to provide an alternative to the `map-google` plugin and is based on `map-quest`.

Since OpenStreetMap provides tiles without charge, there are limitations on use and the service should be treated with respect.

## Installation

Installing the Map Leaflet plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install map-leaflet

This will install the Map Leaflet plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/map-leaflet`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `map-leaflet`. You can find these files on [GitHub](https://github.com/finanalyst/grav-plugin-map-leaflet) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/map-leaflet

> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

### Admin Plugin

If you use the admin plugin, you can install directly through the admin plugin by browsing the `Plugins` tab and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/map-leaflet/map-leaflet.yaml` to `user/config/plugins/map-leaflet.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
provider: opensteetmap
variant:
apikey:
```

- provider If the Admin plugin is used, then three provider options are provided.
    1. OpenStreetMap - no further options are needed
    1. Thunderforest - two further options are given
        1. apikey - the provider requires an apikey, which for hobbyists is free.
        1. variant - the style of the map. A list of available options is given (old t-style parameter is deprecated).
    1. MapBox
        1. apikey - as above
        1. variant - as above (old m-style parameter is deprecated).

Note that if you use the admin plugin, a file with your configuration, and named map-leaflet.yaml will be saved in the `user/config/plugins/` folder once the configuration is saved in the admin.

## Usage

The following example demonstrates how to embed an interactive map and place on it two sets of markers.

The plugin provides two shortcodes:
- `[map-leaflet]`
    - options:
        - lat -- the latitude of the centre of the map, defaults to 51.505 (London).
        - lng -- the longitude of the centre of the map  defaults to -0.09
        - zoom -- an integer from 1-24, see MapLeaflet documentation. Defaults to 13.
        - width -- the width in browser coordinates for the map, without a class defaults to 100% (see below).
        - height -- the height in browser coordinates, without a class defaults to 530px (see below).
        - scale -- is either present or not present in shortcode. If present, then a Scale is shown; if not, no Scale is shown.
        - variant -- override the variant configuration (old style option is deprecated).
        - classes -- adds value of `classes` to the **class** attribute of the **div** containing the map. Defaults to ''.
            - If `classes` is defined, then it is assumed one of the classes sets the *width* and *height* of the div (to allow for responsive map sizing). *width* and *height* must be set by a class in order for the map to be generated.
    - contents:
        - Empty, in which case only a map is generated.
        - A set of `marker` codes.
- `[a-markers]`
    - options that if they are included in the shortcode become the default for each marker:
        - `markerColor` -- one of colors listed below  (default = 'blue')
        -` iconColor` -- an HTML colour code, (default = 'white')
        - `draggable` -- see MapLeaflet documentation. By default False. True if either option is present, or given as draggable=True
        - `spin` -- the icon spins (default = False), can be set as for draggable
    - content between opening and closing shortcodes:
        - A JSON **Array** of **Hash**, eg
            `{"title": "popup text", "lat": 122.222, "lng": 22.9, "markerColor": "red" , "icon": "coffee" }`
        - `lat` and `lng` are mandatory, and the others are optional. If not included, the default value is given to them.
            - `title` -- the text for the popup of the marker (default = '')
            - `text` -- the text inside the marker (default = '') Should be kept short.
            - `markerColor`,` iconColor`,`draggable` and `spin` can be set in the hash or a default set in the shortcode.

### Marker Colours
Markers are coloured images, so only a finite number are possible. The following colors are provided:  

| |  |  |  |
|---|---|---|---|
| red | orange | green | blue |
| purple | darkred | darkblue | darkgreen |
| darkpurple | cadetblue | lightred | beige |
| lightgreen | lightblue | pink | salmon |
|white | lightgray | gray | black |

The colours correspond to:
![](assets/images/markers-soft.png)

### Example
The following code is in <path to grav>/user/pages/03.map/default.md
```md
---
title: Maps
cache_enable: false
---
# London Neighbourhoods
[map-leaflet lat=51.505 lng=-0.09 zoom=13 mapname=neighbourhood variant=neighbourhood scale ]
[a-markers markerColor="darkblue"
iconColor="white"
]
[{ "lat": 37.7749, "lng": -122.4194, "icon": "home", "title": "Home Position" } ]
[/a-markers]
[a-markers icon=""]
[  { "lat": 51.505,  "lng": -0.09 , "text": 1, "draggable": true  },
{ "lat":  51.515,  "lng": -0.1 , "text": 2, "markerColor": "cadetblue" },
{ "lat":   51.515,  "lng": -0.14, "text": 3, "spin": true },
{ "lat":   51.505,  "lng": 0, "text": 4, "spin": false },
{ "lat":   51.525,  "lng": -0.01, "icon": "coffee", "markerColor": "red", "title": "Lovely bistro"}
]
[/a-markers]
[/map-leaflet]

# San Fransisco Transport
[map-leaflet lat=37.7749 lng=-122.4194 zoom=13 mapname=transd variant=transport-dark ]
[a-markers markerColor="lightblue"
iconColor="white"
]
[{ "lat": 37.7749, "lng": -122.4194, "icon": "home", "title": "Home Position" } ]
[/a-markers]
[a-markers icon=""]
[  { "lat": 37.775,  "lng": -122.48 , "text": 1, "draggable": true  },
{ "lat":  37.77,  "lng": -122.414 , "text": 2, "markerColor": "pink" },
{ "lat":   37.765,  "lng": -122.409, "text": 3, "spin": true },
{ "lat":   37.76,  "lng": -122.3995, "text": 4, "spin": false },
{ "lat":   37.755,  "lng": -122.499, "icon": "coffee", "markerColor": "lightred", "title": "Lovely bistro"}
]
[/a-markers]
[/map-leaflet]

```

First try this with OpenStreetMap. There are no styles, so the extra style information is discarded.

Next, get an apikey from [Thunderforest Sign in](https://manage.thunderforest.com/users/sign_in) and add it to the plugin configuration.

Next look at the maps in the example to see how styling can dramatically improve perception.

Since the `style` can be changed inside the shortcode, different map styles can be given to the user to choose (for the two 3-party providers here). For example, a drop down box can be created in a form, and when the user responds with a map style change, it is included via Twig in the shortcode.

In the example, the map style is taken from the page header.

### Comments
- cache_enable should be set to false as there has to be a call to the tile provider to get the data.
- Any of the fontawesome icons can be included as markers.
- Any alphanumeric text can be included, but too many characters > 3? will be ugly.
- OpenStreetMap is the repository for map tiles, Leaflet is the library for managing the map tiles. However, styling the maps is subjective and there are multiple providers, such as Thunderforest and MapBox.

### Disclaimer
The coordinates in this illustration have no meaning.

### Limitations
- Due to the simple implementation of the shortcode, it is possible that there should only be one map on one page. It is possible that if more maps are defined, the marker will be placed on each, or only one, map.
- do not set both 'text' and 'icon' for each marker.

## Credits

- Awesome work by Leaflet, OpenStreetMap, Thunderforest and MapBox.
- [Extra-Marker Leaflet plugin](https://github.com/coryasilva/Leaflet.ExtraMarkers)
- [Timothy Armes](github.com/timothyarmes) - for Thunderforest improvement.
- @A---- for improving the providers and reducing dependencies
- @lazybadger for correcting templates for Gantry

## To Do
- Update README to reflect move to Extra-marker leaflet plugin (some legacy remarks related to previous marker plugin)
- Generalise the map provider list, initializing plugin from providers.yaml, so to add a new provider can be done by adding an entry to providers.yaml.
    - This will happen if a use case is requested.
- Add geoJSON code
