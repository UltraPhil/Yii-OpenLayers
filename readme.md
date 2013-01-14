Yii-OpenLayers
=====

What is Yii-OpenLayers?
--------------
Yii-OpenLayers is an extension to include OpenLayers map, OOP-style, PHP, in your YII applications.

What I'm planning to support:
- Map
	- Layers
	- LonLat items
	- Markers
- OL Widget
- JS scripts to help interracting with the map.


Current Version 0.2 (2013-01-14 8:30)
--------------

This is mostly a cleaning update. We are still working to improve the
build quality, since this extension was before a quick-released extension in order
to make OpenLayers work in an OOP way.

Nonetheless, 3 new features has been included:

-OpenLayers updated to last stable release (2.12, up from 2.11)

-It is now possible to pass the desired marker icon's filename.
	-If nothing is given, the constructor will choose "marker.png" as the default icon.

-Added support for a search box with autocomplete.

Some files have been added:

-autoComplete.js base js fle with methods for supporting autocomplete search boxes easily.
-autoCompleteCSS.css self-explanatory.
-urlFunctions.js is a file that was missing in the initial (0.1) release.

OpenLayersWidget.php->registerScripts has been adjusted with the new files.

Version 0.1 (2012-12-21 13:00)
--------------

First Commit !

It includes a working but raw version of a Yii Extension.

Working, out of the box:

- Maps
	- Layers
	- LonLat items
	- Markers
- OL Widget

Once again, this is a rough release. Ongoing versions will include tutorials, refinements, and
more functionalities.

The project contains an asset folder with the OpenLayers source file. The project is delivered
as is. The current version will always be guaranteed to work with the included version of OL.

Should you replace OpenLayers with an other version is at your discretion.