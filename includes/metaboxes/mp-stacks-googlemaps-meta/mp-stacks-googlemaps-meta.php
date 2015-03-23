<?php
/**
 * This page contains functions for modifying the metabox for googlemaps as a media type
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package    MP Stacks GoogleMaps
 * @subpackage Functions
 *
 * @copyright   Copyright (c) 2014, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */
 
/**
 * Add PostGrid as a Media Type to the dropdown
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param    array $args See link for description.
 * @return   void
 */
function mp_stacks_googlemaps_create_meta_box(){	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_googlemaps_add_meta_box = array(
		'metabox_id' => 'mp_stacks_googlemaps_metabox', 
		'metabox_title' => __( '"GoogleMaps" Content-Type', 'mp_stacks_googlemaps'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'low' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_googlemaps_items_array = array(
		
			array(
					'field_id'			=> 'mp_stacks_googlemaps_api_key',
					'field_title' 	=> __( 'Google Maps API Key', 'mp_stacks_googlemaps'),
					'field_description' 	=> __( 'Enter your Google Maps API Key. Don\'t Have one? 
					To create your API key:
						<ol>
						
							<li><a href="https://console.developers.google.com/project" target="_blank">Click here</a> and then click on "Create Project". Call it anything you like. </li>
							<li>Once you\'ve created the "Project", Click "Enable an API" under "Boost your app with a Google API". </li>
							<li>Enable the "Google Maps JavaScript API v3" (you may have to scroll down to find it). </li>
							<li>Go to <a href="https://console.developers.google.com/project" target="_blank">https://console.developers.google.com/project</a> and click on the "Project" you just made.</li>
							<li>On the left-hand side, under "APIs and Auth", click on "Credentials".</li>
							<li>Under "Public API access" click on "Create New Key".</li>
							<li>Choose "Browser Key" in the "Create New Key" popup.</li>
							<li>Leave the text box blank under "ACCEPT REQUESTS FROM THESE HTTP REFERERS (WEB SITES)" in the popup.</li>
							<li>Copy the string of text listed beside "API KEY" and paste it below.</li>
							<li>That\'s it! The rest of the heavy-lifting is done by the MP Stacks + GoogleMaps Add-On.</li>
				
				
						</ol>', 'mp_stacks_googlemaps' ),
					'field_type' 	=> 'textbox',
					'field_value' 	=> '',
					'field_placeholder' 	=> __( 'Your Google Maps API Key', 'mp_stacks_googlemaps' ),
			),
			array(
					'field_id'			=> 'mp_stacks_googlemaps_latitude',
					'field_title' 	=> __( 'Latitude', 'mp_stacks_googlemaps'),
					'field_description' 	=> __( 'At which Latitude should this Map Begin? Not sure how to find this?', 'mp_stacks_googlemaps' ) . __( 'Not sure how to find this? To get the coordinates of a place you find on the map with your browser, like Chrome, Firefox, or Internet Explorer:
<p>Option 1:</p> 					
<ol>
	<li>Use a Geocoding service like <a href-"http://www.latlong.net/" target="_blank">http://www.latlong.net/</a></li>
</ol>	
Option 2:			
<ol>
<li><a href="https://maps.google.com" target="_blank">Open Google Maps (click here)</a>.</li>
<li>Right-click the place or area on the map.</li>
<li>Select What\'s here?</li>
<li>Under the search box, an info card with coordinates will appear.</li>
</ol>', 'mp_stacks_googlemaps' ) . '<img src="' . plugins_url( 'assets/images/how-to-find-latitude.jpg', dirname( dirname( dirname( __FILE__ ) ) ) ) . '" width="300" height="auto">',
					'field_type' 	=> 'textbox',
					'field_value' 	=> '',
			),
			array(
					'field_id'			=> 'mp_stacks_googlemaps_longitude',
					'field_title' 	=> __( 'Longitude', 'mp_stacks_googlemaps'),
					'field_description' 	=> __( 'At which Longitude should this Map Begin? Not sure how to find this?', 'mp_stacks_googlemaps' ) . __( 'Not sure how to find this? To get the coordinates of a place you find on the map with your browser, like Chrome, Firefox, or Internet Explorer:
<p>Option 1:</p> 					
<ol>
	<li>Use a Geocoding service like <a href-"http://www.latlong.net/" target="_blank">http://www.latlong.net/</a></li>
</ol>	
Option 2:				
<ol>
<li><a href="https://maps.google.com" target="_blank">Open Google Maps (click here)</a>.</li>
<li>Right-click the place or area on the map.</li>
<li>Select What\'s here?</li>
<li>Under the search box, an info card with coordinates will appear.</li>
</ol>', 'mp_stacks_googlemaps' ) . '<img src="' . plugins_url( 'assets/images/how-to-find-longitude.jpg', dirname( dirname( dirname( __FILE__ ) ) ) ) . '" width="300" height="auto">',
					'field_type' 	=> 'textbox',
					'field_value' 	=> '',
					'field_placeholder' => 'longitude',
			),
			array(
					'field_id'			=> 'mp_stacks_googlemaps_zoom',
					'field_title' 	=> __( 'Map Zoom Level', 'mp_stacks_googlemaps'),
					'field_description' 	=> __( 'How Zoomed in should this map be?', 'mp_stacks_googlemaps' ),
					'field_type' 	=> 'input_range',
					'field_value' 	=> '80',
			),
			array(
					'field_id'			=> 'mp_stacks_googlemaps_height',
					'field_title' 	=> __( 'GoogleMaps Height', 'mp_stacks_googlemaps'),
					'field_description' 	=> __( 'How many pixels high should the GoogleMaps be? Default: 500. Note: The Width is always controlled under "Brick Size Settings"', 'mp_stacks_googlemaps' ) ,
					'field_type' 	=> 'number',
					'field_value' 	=> '500',
			),
			array(
					'field_id'			=> 'mp_stacks_googlemaps_show_directions',
					'field_title' 	=> __( 'Show "Directions" Option?', 'mp_stacks_googlemaps'),
					'field_description' 	=> __( 'Do you want people to be able to "Get Directions" from their own location to the first Marker? (First Marker is below)', 'mp_stacks_googlemaps' ) ,
					'field_type' 	=> 'checkbox',
					'field_value' 	=> '',
			),
				array(
						'field_id'			=> 'marker_title',
						'field_title' 	=> __( 'Marker Title', 'mp_stacks_googlemaps'),
						'field_description' 	=> __( 'What is the title of this Marker? EG: "My Store"', 'mp_stacks_googlemaps' ) ,
						'field_type' 	=> 'textbox',
						'field_value' 	=> '',
						'field_repeater' => 'mp_stacks_googlemaps_markers'
				),
				array(
						'field_id'			=> 'marker_body_text',
						'field_title' 	=> __( 'Marker Body text', 'mp_stacks_googlemaps'),
						'field_description' 	=> __( 'What is the body text of this Marker? EG: "A description of my store"', 'mp_stacks_googlemaps' ) ,
						'field_type' 	=> 'textarea',
						'field_value' 	=> '',
						'field_repeater' => 'mp_stacks_googlemaps_markers'
				),
				array(
						'field_id'			=> 'marker_latitude',
						'field_title' 	=> __( 'Marker Latitude (Required)', 'mp_stacks_googlemaps'),
						'field_description' => __( 'At which Latitude should this Marker sit? Find this on Google Maps:', 'mp_stacks_googlemaps' ) . ' <a href="http://www.latlong.net/" target="_blank">Open latlong.net (click here)</a> OR <a href="https://maps.google.com" target="_blank">Open Google Maps (click here)</a>.',
						'field_type' 	=> 'textbox',
						'field_value' 	=> '',
						'field_repeater' => 'mp_stacks_googlemaps_markers'
				),
				array(
						'field_id'			=> 'marker_longitude',
						'field_title' 	=> __( 'Marker Longitude (Required)', 'mp_stacks_googlemaps'),
						'field_description' => __( 'At which Longitude should this Marker sit? Find this on Google Maps:', 'mp_stacks_googlemaps' ) . ' <a href="http://www.latlong.net/" target="_blank">Open latlong.net (click here)</a> OR <a href="https://maps.google.com" target="_blank">Open Google Maps (click here)</a>.',
						'field_type' 	=> 'textbox',
						'field_value' 	=> '',
						'field_repeater' => 'mp_stacks_googlemaps_markers'
				),
				array(
						'field_id'			=> 'marker_address',
						'field_title' 	=> __( 'Marker Address (For Display Only, Optional)', 'mp_stacks_googlemaps'),
						'field_description' 	=> __( 'If you\'d like to display the address in this Marker, enter it here.', 'mp_stacks_googlemaps' ) ,
						'field_type' 	=> 'textarea',
						'field_value' 	=> '',
						'field_repeater' => 'mp_stacks_googlemaps_markers'
				),
				array(
						'field_id'			=> 'marker_phone_number',
						'field_title' 	=> __( 'Marker Phone Number (For Display Only, Optional)', 'mp_stacks_googlemaps'),
						'field_description' 	=> __( 'If you\'d like to display a phone number in this Marker, enter it here.', 'mp_stacks_googlemaps' ) ,
						'field_type' 	=> 'textbox',
						'field_value' 	=> '',
						'field_repeater' => 'mp_stacks_googlemaps_markers'
				),
				array(
						'field_id'			=> 'marker_email',
						'field_title' 	=> __( 'Marker Email Address (For Display Only, Optional)', 'mp_stacks_googlemaps'),
						'field_description' 	=> __( 'If you\'d like to display an email address in this Marker, enter it here.', 'mp_stacks_googlemaps' ) ,
						'field_type' 	=> 'textbox',
						'field_value' 	=> '',
						'field_repeater' => 'mp_stacks_googlemaps_markers'
				),
				array(
						'field_id'			=> 'marker_image',
						'field_title' 	=> __( 'Marker Image (For Display Only, Optional)', 'mp_stacks_googlemaps'),
						'field_description' 	=> __( 'Be default this marker will use Google\'s Default Map Marker icon. If you\'d like to use your own, upload it here.', 'mp_stacks_googlemaps' ) ,
						'field_type' 	=> 'mediaupload',
						'field_value' 	=> '',
						'field_repeater' => 'mp_stacks_googlemaps_markers'
				),
			array(
					'field_id'			=> 'mp_stacks_googlemaps_full_brick_tip',
					'field_title' 	=> __( 'Full-Brick Tip:', 'mp_stacks_googlemaps'),
					'field_description' 	=> __( 'If you want this GoogleMaps to fill up the ENTIRE brick, make sure the Brick Alignment is set to "Centered". Then, on the right side under "Brick Size Settings" > "Maximum Content Width", set it to be "999999999999". Then check the option listed under "Content-Type Margins" > "Full Width Content-Types".', 'mp_stacks_googlemaps' ) ,
					'field_type' 	=> 'basictext',
					'field_value' 	=> '0',
			),
		
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_googlemaps_add_meta_box = has_filter('mp_stacks_googlemaps_meta_box_array') ? apply_filters( 'mp_stacks_googlemaps_meta_box_array', $mp_stacks_googlemaps_add_meta_box) : $mp_stacks_googlemaps_add_meta_box;
	
	//Globalize the and populate mp_stacks_features_items_array (do this before filter hooks are run)
	global $global_mp_stacks_googlemaps_items_array;
	$global_mp_stacks_googlemaps_items_array = $mp_stacks_googlemaps_items_array;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_googlemaps_items_array = has_filter('mp_stacks_googlemaps_items_array') ? apply_filters( 'mp_stacks_googlemaps_items_array', $mp_stacks_googlemaps_items_array) : $mp_stacks_googlemaps_items_array;
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_googlemaps_meta_box;
	$mp_stacks_googlemaps_meta_box = new MP_CORE_Metabox($mp_stacks_googlemaps_add_meta_box, $mp_stacks_googlemaps_items_array);
}
add_action('mp_brick_metabox', 'mp_stacks_googlemaps_create_meta_box');