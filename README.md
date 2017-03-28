coinSlider
===========

A Plugin for moziloCMS 2.0

Generates a slideshow with multiple effects with images of a moziloCMS gallery.

## Installation
#### With moziloCMS installer
To add (or update) a plugin in moziloCMS, go to the backend tab *Plugins* and click the item *Manage Plugins*. Here you can choose the plugin archive file (note that it has to be a ZIP file with exactly the same name the plugin has) and click *Install*. Now the coinSlider plugin is listed below and can be activated.

#### Manually
Installing a plugin manually requires FTP Access.
- Upload unpacked plugin folder into moziloCMS plugin directory: ```/<moziloroot>/plugins/```
- Set default permissions (chmod 777 for folders and 666 for files)
- Go to the backend tab *Plugins* and activate the now listed new coinSlider plugin

## Syntax
```
{coinSlider|<name>|<width>|<height>|<spw>|<sph>|<delay>|<sDelay>|<opacity>|<titleSpeed>|<effect>|<navigation>|<links>|<hoverPause>}
```
Inserts the coinSlider.

1. Parameter ```<name>```: The name of an existing gallery. A wrong name or no input leads to an error message.
2. Parameter ```<width>```: Width of the slideshow in px (e.g. 600).
3. Parameter ```<height>```: Height of the slideshow in px (e.g. 350).
4. Parameter ```<spw>```: Number of squares per width.
5. Parameter ```<sph>```: Number of squares per height.
6. Parameter ```<delay>```: The duration of a slide to show in ms (e.g. 4000)
7. Parameter ```<sDelay>```: The duration of a single square to show in ms (e.g. 100)
8. Parameter ```<opacity>```: Opacity of description and navigation (a value between 0.0 and 1.0, e.g. 0.6).
9. Parameter ```<titleSpeed>```: Duration for description to appear in ms (e.g. 500).
10. Parameter ```<effect>```: Effect for slides change. Possible values are ```random```, ```swirl```, ```rain``` and ```straight```.
11. Parameter ```<navigation>```: Show navigation.
12. Parameter ```<links>```: Make slides clickable.
13. Parameter ```<hoverPause>```: Pause on mouse hover.

## License
This Plugin is distributed under *GNU General Public License, Version 3* (see LICENSE) or, at your choice, any further version.

## Documentation
A detailed documentation and demo can be found here:  
https://github.com/devmount-mozilo/coinSlider/wiki/Dokumentation [german]
