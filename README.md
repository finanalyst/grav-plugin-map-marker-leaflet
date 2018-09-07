# Map Leaflet Plugin

The **Map Leaflet** Plugin is for [Grav CMS](http://github.com/getgrav/grav). Short code to use the [Leaflet js library](https://leafletjs.com), together with a map from [MapBox](https://www.mapbox.com). MapBox has its own js library, but the Leaflet one is used here as it is closest to the original MapQuest plugin this is based on.

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
mapbox_api_key: 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NDg1bDA1cjYzM280NHJ5NzlvNDMifQ.d6e-nNyBDtmQCVwVNivz7A'
mapbox_style: outdoors
```
> WARNING the `api_key` string above is a generic key and should work. You need to [obtain a valid MapBox key](https://www.mapbox.com).

The mapbox style is a part of the Map Box toolkit, not Leaflet.

Note that if you use the admin plugin, a file with your configuration, and named map-leaflet.yaml will be saved in the `user/config/plugins/` folder once the configuration is saved in the admin.

## Usage

The following example demonstrates how to embed an interactive map and place on it two sets of markers.

The plugin provides two shortcodes:
- `[map-leaflet]`
    - options:
        - lat -- the latitude of the centre of the map, defaults to 51.505 (London).
        - lng -- the longitude of the centre of the map  defaults to -0.09
        - zoom -- an integer from 1-24, see MapLeaflet documentation. Defaults to 13.
        - width -- the width in browser coordinates for the map, defaults to 100%
        - height -- the height in browser coordinates, defaults to 530px
    - contents:
        - Empty, in which case only a map is generated.
        - A set of `marker` codes.
- `[marker]`
    - options:
        - primaryColor -- a colour code, defaults to '#22407F'
        - secondaryColor -- a colour code, defaults to '#ff5998'
        - size  -- can be `sm`, `md`, `lg`, see MapLeaflet documentation
        - shadow -- By default False. True if either option is present, or given as shadow=True
        - draggable -- see MapLeaflet documentation. By default False. True if either option is present, or given as draggable=True
        - title -- an array of strings, for the popup for each marker. No provision for the same text in all markers.
        All the strings must be short (see MapLeaflet)
        - type -- a MapLeafletPlugin option. This plugin has been tested only for the values **marker** and **flag**. Others might work.
        - symbol
            - If `type` = **marker**, then a single letter.
            - If `type` = **flag**, then short text, no spaces.
        - enum -- a MapLeafletPlugin option that adds the point index to the symbol. By default False. True if either option is present, or given as draggable=True.
            - if `enum` is True and`type` = **flag**, then index of the array, in the order given in the json, is appended onto `symbol` text
            - if `enum` is True and `type` = **marker** (or other), then index is made to be `symbol` text of marker, and if `symbol`
            is defined within the shortcode, then it is ignored. Note that the index may only go to 999.
        - array_of_hash -- a MapLeafletPlugin option. It should be present for hash type json (see below). By default False. True if either option is present, or given as draggable=True
    - content:
        - A JSON **Array** of points in one of two forms
            1. When no `array_of_hash` present, then an array of points as  
            [ `latitude`, `longitude` ]
            2. When `array_of_hash` present, then points in the form:  
            `{title: 'popup text', lat: 122.222, lng: 22.9, prim: '#123456' , scnd: '#fedcba', size: 'md'}`
        - In the hash `lat` and `lng` are mandatory, and the others are optional. But if a field is given for one marker, it must be given for all markers.
            - `title` -- the text for the popup of the marker
            - `prim` -- primary colour code for the marker. If `prim` is present in a hash, then primaryColor is ignored)
            - `scnd` -- secondary colour code for the marker. If `scnd` is present in a hash, then secondaryColor is ignored)
            - `size` -- the size code for the marker. If `size` is present in a hash, then the `size` option (above) is ignored.

### Example
The following code is in <path to grav>/user/map/default.md
```yaml
---
title: MapLeaflet Test
cache_enable: false
---
[map-leaflet lat=37.7749 lng=-122.4194 zoom=12]
[marker primaryColor='#22407F'
secondaryColor='#ff5998'
size='sm'
symbol='O'
]
[[37.7749, -122.4194]]
[/marker]
[marker primaryColor='#277650'
secondaryColor='#AA5639'
shadow
size='md'
symbol= 'K'
type=flag
enum
]
[ [  37.775,  -122.42 ],
[  37.77,  -122.414 ],
[  37.765,  -122.409 ],
[  37.76,  -122.3995 ],
[  37.755,  -122.399 ]
]
[/marker]
[/map-leaflet]

```
### Comments
- cache_enable should be set to false as there has to be a call to MapLeaflet to get the data.

### Disclaimer
The coordinates in this illustration are derived from a Leaflet example and have no meaning.

### Limitations
- Due to the simple implementation of the shortcode, it is possible that there should only be one map on one page. It is possible that if more maps are defined, the marker will be placed on each, or only one, map.
- In the `marker` option, there are limitations for valid symbols
- In the `flag` option, there are limitations on the text (it seems no spaces and a short character count).

## Credits

Awesome work Leaflet, MapBox and OpenStreetMap.

## To Do
