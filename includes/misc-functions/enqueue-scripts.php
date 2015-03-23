<?php
/**
 * This file contains the enqueue scripts function for the googlemaps plugin
 *
 * @since 1.0.0
 *
 * @package    MP Stacks GoogleMaps
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
 * Enqueue JS and CSS for googlemaps 
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */

/**
 * Enqueue css and js
 *
 * Filter: mp_stacks_googlemaps_css_location
 */
function mp_stacks_googlemaps_enqueue_scripts(){
	
	//Enqueue Google Maps Custom CSS
	wp_enqueue_style( 'mp_stacks_googlemaps_css', plugins_url( 'css/googlemaps.css', dirname( __FILE__ ) ), MP_STACKS_GOOGLEMAPS_VERSION );

}
add_action( 'wp_enqueue_scripts', 'mp_stacks_googlemaps_enqueue_scripts' );