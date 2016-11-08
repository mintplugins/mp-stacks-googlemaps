=== MP Stacks + GoogleMaps ===
Contributors: johnstonphilip
Donate link: http://mintplugins.com/
Tags: message bar, header
Requires at least: 3.5
Tested up to: 4.6
Stable tag: 1.0.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display custom Google Maps in Bricks from MP Stacks.

== Description ==

Display custom Google Maps in Bricks from MP Stacks.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the 'mp-stacks-googlemaps' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
5. Put Stacks on pages using the "Add Stack"� button.

== Frequently Asked Questions ==

See full instructions at http://mintplugins.com/doc/mp-stacks

== Screenshots ==


== Changelog ==

= 1.0.0.9 = November 8, 2016
* Set mime headers for custom JS file

= 1.0.0.8 = July 5, 2016
* Add requirement for Google API Keys back in. Google now requires API keys for Google Maps to work as of June 2016.

= 1.0.0.7 = May 26, 2016
* Added option for making the map draggable or not.

= 1.0.0.6 = November 14, 2015
* Removed all API Key things from the plugin as they are not needed for the functionality.
* Removed the 'double' lat/long settings from the plugin and set the map position to use the first marker as the map starting position.

= 1.0.0.5 = October 15, 2015
* Changed inline js to be enqueued so it is in correct order

= 1.0.0.4 = September 21, 2015
* Brick Metabox controls now load using ajax.
* Admin Meta Scripts now enqueued only when needed.
* Front End Scripts now enqueued only when needed.

= 1.0.0.3 = May 12, 2015
* Bug fix: was adding 2 plus signs sidebyside if no custom marker icon used and no email. Error was "Invalid left-hand side expression in postfix operation".

= 1.0.0.2 = April 1, 2015
* Fixed bug where no marker icon exists.

= 1.0.0.1 = March 27, 2015
* Fixed Syntax error in link to latlong.net
* Disabled map scrolling/zooming

= 1.0.0.0 = March 23, 2015
* Initial release.
