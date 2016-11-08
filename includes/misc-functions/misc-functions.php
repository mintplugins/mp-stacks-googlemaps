<?php
/**
 * This file contains misc functions for the MP Stacks + GoogleMaps plugin.
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
 * This function outputs all the custom js for a map brick when the url contains the right variables.
 * This outputted file is auto-enqueued in the content-filters.php file in this MP Stacks + googleMaps plugin by the Brick in question.
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_googlemaps_custom_js_page(){

	//If this stack content type is set to be an image
	if ( !isset( $_GET['mp_stacks_googlemaps_brick_id'] ) ){

		//Return Default
		return false;
	}

	//Get the POST ID from the URL
	$post_id = intval( $_GET['mp_stacks_googlemaps_brick_id'] );

	//If the passed post id isn't actually a real post id, get outta here!
	if ( !$post_id ){
		return false;
	}

	header('Content-Type: application/javascript');

	//$google_maps_api_key = mp_core_get_post_meta( $post_id, 'mp_stacks_googlemaps_api_key', NULL );
	$googlemaps_show_directions = mp_core_get_post_meta_checkbox( $post_id, 'mp_stacks_googlemaps_show_directions', false );
	$googlemaps_zoom = mp_core_get_post_meta( $post_id, 'mp_stacks_googlemaps_zoom', 80 );
	$googlemaps_zoom = round( $googlemaps_zoom / 7 );

	$googlemaps_draggable = mp_core_get_post_meta_checkbox( $post_id, 'mp_stacks_googlemaps_draggable', true );
	$googlemaps_draggable = empty( $googlemaps_draggable ) ? 'false' : 'true';

	//Get the array of markers we should show on this map
	$markers = mp_core_get_post_meta( $post_id, 'mp_stacks_googlemaps_markers', NULL );
	//If there are markers to show	, get the default map position using the first marker's values.
	if ( is_array( $markers ) ){

		$marker_counter = 0;

		//Loop through each marker the user has set up
		foreach( $markers as $marker ){

			$googlemaps_latitude = isset( $marker['marker_latitude'] ) && !empty( $marker['marker_latitude'] ) ? $marker['marker_latitude'] : '40.7127';//Default to new york;
			$googlemaps_longitude = isset( $marker['marker_longitude'] ) && !empty( $marker['marker_longitude'] ) ? $marker['marker_longitude'] : '-74.0059';//Default to new york;

		}
	}
	else{
		$googlemaps_latitude = '40.7127';//Default to new york;
		$googlemaps_longitude = '-74.0059';//Default to new york;
	}


	//Enqueue the script from Google Maps in the footer
	//wp_enqueue_script( '', 'https://maps.googleapis.com/maps/api/js?key=' . $google_maps_api_key . '&callback=mp_stacks_googlemaps_initialize' , array( 'jquery', 'mp_stacks_front_end_js' ), MP_STACKS_GOOGLEMAPS_VERSION, true );

	//Enqueue the script from Google Maps in the footer - without any API Key.
	wp_enqueue_script( '', 'https://maps.googleapis.com/maps/api/js?callback=mp_stacks_googlemaps_initialize' , array( 'jquery', 'mp_stacks_front_end_js' ), MP_STACKS_GOOGLEMAPS_VERSION, true );

	//Add the inline JS for the map to the Footer
	$js_output = '

		var mp_stacks_googlemaps_exists;
		var directionsDisplay_' . $post_id . ';
		var directionsService_' . $post_id . ';
		var map_' . $post_id . ';

		function mp_stacks_googlemaps_' . $post_id . '_initialize() {

			mp_stacks_googlemaps_exists = true;

			directionsService_' . $post_id . ' = new google.maps.DirectionsService();
			directionsDisplay_' . $post_id . ' = new google.maps.DirectionsRenderer();

			var mapOptions = {
				draggable: ' . $googlemaps_draggable . ',
				scrollwheel: false,
				center: { lat: ' . $googlemaps_latitude . ', lng: ' . $googlemaps_longitude . '},
				zoom: ' . $googlemaps_zoom . '
			};

			map_' . $post_id . ' = new google.maps.Map(document.getElementById(\'mp-stacks-googlemaps-' . $post_id . '-map-canvas\'), mapOptions);';

			//If we should allow people to choose directions
			if ( $googlemaps_show_directions ){
				$js_output .= 'directionsDisplay_' . $post_id . '.setMap(map_' . $post_id . ');
				directionsDisplay_' . $post_id . '.setPanel(document.getElementById("mp_stacks_googlemaps_directionsPanel_' . $post_id . '"));

				var control = document.getElementById(\'mp-stacks-googlemaps-' . $post_id . '-directions-control\');
				control.style.display = \'block\';
				map_' . $post_id . '.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(control);';
			}

			//If there are markers to show
			if ( is_array( $markers ) ){

				$marker_counter = 0;

				//Loop through each marker the user has set up
				foreach( $markers as $marker ){

					if ( empty( $marker['marker_latitude'] ) ){
						continue;
					}

					if ( $marker_counter == 0 ){
						$directions_destination = 'new google.maps.LatLng(' . $marker['marker_latitude'] . ', ' . $marker['marker_longitude'] . ')';
					}

					$map_icon_width = 100;
					$map_icon_height = 100;

					//If this marker should have a custom icon
					if ( !empty( $marker['marker_image'] ) ){

						//Get the attachment id for this image
						$attachment_id = mp_core_get_attachment_id_from_url( $marker['marker_image'] );
						$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );

						$map_icon_width = isset( $image_attributes[1] ) ? $image_attributes[1] : 100;
						$map_icon_height = isset( $image_attributes[2] ) ? $image_attributes[2] : 100;

						$js_output .= '
						var image_' . $marker_counter . ' = {
							url: \'' . $marker['marker_image'] . '\',
							// Set the width and height to match the width and height of the uploaded image
							size: new google.maps.Size(' . $map_icon_width . ', ' . $map_icon_height . '),
							// The origin for this image is 0,0.
							origin: new google.maps.Point(0,0),
							// The anchor for this image is the bottom center of it (when scaled to halfsize for retina)
							anchor: new google.maps.Point(' . ( $map_icon_width /2) / 2 . ', ' . $map_icon_height / 2 . '),
							scaledSize: new google.maps.Size(' . $map_icon_width /2 . ', ' . $map_icon_height/2 . ')
						};';
					}

					//This is what is displayed in the popup above the marker on the map
					$js_output .= '
					var infowindow_' . $marker_counter . ' = new google.maps.InfoWindow({
					  content: \'<div id="mp-stacks-googlemaps-infowindow">\'+';
					  	if ( !empty(  $marker['marker_title'] ) ){
						  $js_output .= '\'<div id="mp-stacks-googlemaps-infowindow-title">\'+' . json_encode( $marker['marker_title'] ) . '+\'</div>\'+';
						}
						if ( !empty(  $marker['marker_body_text'] ) ){
						  $js_output .= '\'<div id="mp-stacks-googlemaps-infowindow-body">\'+' . json_encode( $marker['marker_body_text'] ) . '+\'</div>\'+';
						}
						if ( !empty(  $marker['marker_address'] ) ){
						   $js_output .= '\'<div id="mp-stacks-googlemaps-infowindow-address">\'+' . json_encode( $marker['marker_address'] ) . '+\'</div>\'+';
						}
						if ( !empty(  $marker['marker_phone_number'] ) ){
						   $js_output .= '\'<div id="mp-stacks-googlemaps-infowindow-phone"><a href="tel:\'+' . json_encode( $marker['marker_phone_number'] ) . '+\'">\'+' . json_encode( $marker['marker_phone_number'] ) . '+\'</a></div>\'+';
						}
						if ( !empty(  $marker['marker_email'] ) ){
						  $js_output .= '\'<div id="mp-stacks-googlemaps-infowindow-email"><a href="mailto:\'+' . json_encode( $marker['marker_email'] ) . '+\'">\'+' . json_encode( $marker['marker_email'] ) . '+\'</a></div>\'+';
						}


					  	$js_output .= '\'</div>\',
					  	pixelOffset: new google.maps.Size(0, ' . -( $map_icon_height / 2) . ')';


					  $js_output .= ',position: new google.maps.LatLng(' . $marker['marker_latitude'] . ', ' . $marker['marker_longitude'] . ')
					});';

					//This creates the marker and puts it on the map
					$js_output .= '
					var marker_' . $marker_counter . ' = new google.maps.Marker({
						position: {lat:' . $marker['marker_latitude'] . ', lng: ' . $marker['marker_longitude'] . '},
						title:"' . $marker['marker_title'] . '",' .
						( !empty( $marker['marker_image'] ) ? 'icon: image_' . $marker_counter . ',' : NULL ) . '
						animation: google.maps.Animation.DROP,
					});';

					//If this is the first marker
					if ( $marker_counter == 0 ){

						//Automatically open the info window above the marker (only for the first marker)
						$js_output .= '
						infowindow_' . $marker_counter . '.open(map_' . $post_id . ');';

					}

					//Make the info window open if the marker is clicked
					$js_output .= 'google.maps.event.addListener(marker_' . $marker_counter . ', \'click\', function() {';

						//Close all open markers on this map
						$marker_close_counter = 0;
						foreach( $markers as $marker_closer ){
							$js_output .= '
							infowindow_' . $marker_close_counter . '.close(map_' . $post_id . ');';
							$marker_close_counter = $marker_close_counter + 1;
						}

						$js_output .= '
						infowindow_' . $marker_counter . '.open(map_' . $post_id . ');
					});

					//Add The marker to the map
					marker_' . $marker_counter . '.setMap(map_' . $post_id . ');
					';

					//Increment the marker counter
					$marker_counter = $marker_counter + 1;
				}
			}

			$js_output .= '
		}

		//Initialize the map upon load
		function mp_stacks_googlemaps_initialize(){
			mp_stacks_googlemaps_' . $post_id . '_initialize();
		}

		//Initialize the map on ajax updates
		if ( mp_stacks_googlemaps_exists ){
			mp_stacks_googlemaps_' . $post_id . '_initialize();
		}

		';

		//If we should allow people to choose directions
		if ( $googlemaps_show_directions ){

			//This function is called when the directions form is submitted
			$js_output .= '
			function mp_stacks_googlemaps_calcRoute' . $post_id . '() {
				var start = document.getElementById("start").value;
				var request = {
					origin:start,
					destination:' . $directions_destination  . ',
					travelMode: google.maps.TravelMode.DRIVING
				};
				directionsService_' . $post_id . '.route(request, function(response, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						directionsDisplay_' . $post_id . '.setDirections(response);
					}
				});
			}';
		}

	//Output the js needed for the Google Map in the Brick in question. This outputted file is auto-enqueued in the content-filters.php file in this MP Stacks + googleMaps plugin.
	echo $js_output;

	die();
}
add_action( 'init', 'mp_stacks_googlemaps_custom_js_page' );
