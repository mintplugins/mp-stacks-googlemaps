<?php 
/**
 * This file contains the function which hooks to a brick's content output
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
 * This function hooks to the brick css output. If it is supposed to be a 'googlemap', then it will add the css 
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_brick_content_output_css_googlemaps( $css_output, $post_id, $first_content_type, $second_content_type ){

	if ( $first_content_type != 'googlemaps' && $second_content_type != 'googlemaps' ){
		return $css_output;	
	}
	
	//Enqueue Google Maps Custom CSS
	wp_enqueue_style( 'mp_stacks_googlemaps_css', plugins_url( 'css/googlemaps.css', dirname( __FILE__ ) ), MP_STACKS_GOOGLEMAPS_VERSION );
	
}
add_filter('mp_brick_additional_css', 'mp_stacks_brick_content_output_css_googlemaps', 10, 4);
	
/**
 * This function hooks to the brick output. If it is supposed to be a 'googlemaps', then it will output the googlemaps
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_brick_content_output_googlemaps($default_content_output, $mp_stacks_content_type, $post_id){
	
	//If this stack content type is set to be an image	
	if ($mp_stacks_content_type != 'googlemaps'){
		
		//Return Default
		return $default_content_output;
	}
	
	$googlemaps_output = NULL;
	
	$google_maps_api_key = mp_core_get_post_meta( $post_id, 'mp_stacks_googlemaps_api_key', NULL );
	$googlemaps_latitude = mp_core_get_post_meta( $post_id, 'mp_stacks_googlemaps_latitude', '40.7127' );//Default to new york
	$googlemaps_longitude = mp_core_get_post_meta( $post_id, 'mp_stacks_googlemaps_longitude', '-74.0059' );//Default to new york
	$googlemaps_show_directions = mp_core_get_post_meta_checkbox( $post_id, 'mp_stacks_googlemaps_show_directions', false );
	$googlemaps_zoom = mp_core_get_post_meta( $post_id, 'mp_stacks_googlemaps_zoom', 80 );
	$googlemaps_zoom = round( $googlemaps_zoom / 7 );
	
	//Get the array of markers we should show on this map
	$markers = mp_core_get_post_meta( $post_id, 'mp_stacks_googlemaps_markers', NULL );
	
	//If no API Key has been entered
	if ( empty( $google_maps_api_key ) ){
		//Output a helpful error message
		return __( 'OOPS! You haven\'t entered an API Key for Google Maps yet! Follow the steps to get one by', 'mp_stacks_googlemaps' ) . ' <a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" target="_blank">' . __( 'clicking here', 'mp_stacks_googlemaps' ) . '</a>.';
	}
	
	wp_enqueue_script( 'mp_stacks_googlemaps_custom_js', esc_url( add_query_arg( array( 'mp_stacks_googlemaps_brick_id' => $post_id ), get_bloginfo( 'wpurl' ) ) ) , array( 'jquery', 'mp_stacks_front_end_js' ), MP_STACKS_GOOGLEMAPS_VERSION, true );
	
	//Enqueue the script from Google Maps in the footer
	wp_enqueue_script( 'mp_stacks_googlemaps_js', 'https://maps.googleapis.com/maps/api/js?key=' . $google_maps_api_key . '&callback=mp_stacks_googlemaps_initialize' , array( 'jquery', 'mp_stacks_front_end_js', 'mp_stacks_googlemaps_custom_js' ), MP_STACKS_GOOGLEMAPS_VERSION, true );
	
	//Get the height of the GoogleMaps
	$googlemaps_height = mp_core_get_post_meta( $post_id, 'googlemaps_height', 500 );
	
	$googlemaps_output .= '<div id="mp-stacks-googlemaps-' . $post_id . '-map-canvas" style="height:' . $googlemaps_height . 'px;"></div>';
	
	//If we should allow people to choose directions
	if ( $googlemaps_show_directions ){
		$googlemaps_output .= '<div id="mp_stacks_googlemaps_directionsPanel_' . $post_id . '"></div>
		<div id="mp-stacks-googlemaps-' . $post_id . '-directions-control" class="mp-stacks-googlemaps-directions-control">
		  <strong>' . __( 'Get directions from', 'mp_stacks_googlemaps' ) . ':</strong>
		  <input id="start" type="text" onchange="mp_stacks_googlemaps_calcRoute' . $post_id . '();" />
		</div>';
	}
			
	//Return
	return $googlemaps_output;
	
}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_googlemaps', 10, 3);