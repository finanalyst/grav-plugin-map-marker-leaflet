#v1.0.7
## 15 June 2018
1. [](#Update)
    * Allow enum, shadow, array_of_hash, draggable to be given as 'Option' or 'Option'=True

# v1.0.6
## 14 June 2018
1. [](#Enhancement)
    * Enum option is made available for marker as well as flag
    * Added option to provide primaryColor, secondaryColor, and size for each marker individually from within json content of marker shortcode.
# v1.0.5
## 11 June 2018
1. [](Bugfix)
    * TileLayer should be set to 'map' even if id of <div> element is set by 'mapname'.
    * If Markercode returns empty array then, output is Null, not Empty Array.

# v1.0.4
## 7 June 2018
1. [](Update)
    * Add shortcode dependency to blueprints

# v1.0.3
## 4 June 2018
1. [](Update)
    * If no json for marker data cannot be decoded, then null is returned, so only map shown

# v1.0.2
## 20 May 2018
1. [](Update)
    * instantiate blank titles and points in case of no valid json in marker shortcode.

# v1.0.1
## 15 May 2018
1. [](update)
    * Some typos
    * Better handling of twig inside Shortcode
    * Added draggable to marker
    * Added title for points
    * Allowed points to be hash as well as array

# v0.1.0
##  05/11/2018

1. [](#new)
    * ChangeLog started...
