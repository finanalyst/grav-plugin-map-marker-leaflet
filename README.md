# Map Quest Plugin

The **Map Quest** Plugin is for [Grav CMS](http://github.com/getgrav/grav). Short code to embed a MapQuest map into a page.

## Installation

Installing the Map Quest plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install map-quest

This will install the Map Quest plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/map-quest`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `map-quest`. You can find these files on [GitHub](https://github.com/finanalyst/grav-plugin-map-quest) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/map-quest

> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

### Admin Plugin

If you use the admin plugin, you can install directly through the admin plugin by browsing the `Plugins` tab and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/map-quest/map-quest.yaml` to `user/config/plugins/map-quest.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
api_key: 'qwertyuiop'
```
> WARNING the `api_key` string above is illustrative and should not work. You need to [obtain a valid MapQuest key](https://developer.mapquest.com/user/register).

Note that if you use the admin plugin, a file with your configuration, and named map-quest.yaml will be saved in the `user/config/plugins/` folder once the configuration is saved in the admin.

## Usage

The following example demonstrates how to embed an interactive map and place on it two sets of markers.

The plugin provides two shortcodes:
- `[map-quest]`
    - options:
        - lat -- the latitude of the centre of the map, defaults to the data in the example.
        - lng -- the longitude of the centre of the map
        - zoom -- an integer from 1-20, see MapQuest documentation. Defaults to 15.
    - contents:
        - Empty, in which case only a map is generated.
        - A set of `marker` codes.
- `[marker]`
    - options:
        - primaryColor -- a colour code, defaults to '#22407F'
        - secondaryColor -- a colour code, defaults to '#ff5998'
        - size  -- can be `sm`, `md`, `lg`, see MapQuest documentation
        - shadow -- By default False. True if either option is present, or given as shadow=True
        - draggable -- see MapQuest documentation. By default False. True if either option is present, or given as draggable=True
        - title -- an array of strings, for the popup for each marker. No provision for the same text in all markers.
        All the strings must be short (see MapQuest)
        - type -- a MapQuestPlugin option. This plugin has been tested only for the values **marker** and **flag**. Others might work.
        - symbol
            - If `type` = **marker**, then a single letter.
            - If `type` = **flag**, then short text, no spaces.
        - enum -- a MapQuestPlugin option that adds the point index to the symbol. By default False. True if either option is present, or given as draggable=True.
            - if `enum` is True and`type` = **flag**, then index of the array, in the order given in the json, is appended onto `symbol` text
            - if `enum` is True and `type` = **marker** (or other), then index is made to be `symbol` text of marker, and if `symbol`
            is defined within the shortcode, then it is ignored. Note that the index may only go to 999.
        - array_of_hash -- a MapQuestPlugin option. It should be present for hash type json (see below). By default False. True if either option is present, or given as draggable=True
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
title: MapQuest Test
cache_enable: false
---
[map-quest lat=37.7749 lng=-122.4194 zoom=12]
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
[/map-quest]

```
### Comments
- cache_enable should be set to false as there has to be a call to MapQuest to get the data.

### Disclaimer
The coordinates in this illustration are derived from a MapQuest example and have no meaning.

### Limitations
- Due to the simple implementation of the shortcode, it is possible that there should only be one map on one page. It is possible that if more maps are defined, the marker will be placed on each, or only one, map.
- In the `marker` option, there are limitations for valid symbols
- In the `flag` option, there are limitations on the text (it seems no spaces and a short character count).

## Credits

Awesome work by [MapQuest](https://www.mapquest.com). Their [software](https://developer.mapquest.com) is much more sophisticated than this simple plugin can handle.

## To Do

- [ ] This plugin provides an interactive map. But a static variant is possible. So add `static` keyword and generate a static map.
