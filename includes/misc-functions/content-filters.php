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
	
	//Enqueue the script from Google Maps in the footer
	wp_enqueue_script( 'mp_stacks_googlemaps_js', 'https://maps.googleapis.com/maps/api/js?key=' . $google_maps_api_key . '&callback=mp_stacks_googlemaps_initialize' , array( 'jquery', 'mp_stacks_front_end_js' ), MP_STACKS_GOOGLEMAPS_VERSION, true );
	
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
					
					//If this marker should have a custom icon
					if ( !empty( $marker['marker_image'] ) ){
						
						//Get the attachment id for this image
						$attachment_id = mp_core_get_attachment_id_from_url( $marker['marker_image'] );
						$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );
						
						$js_output .= '
						var image_' . $marker_counter . ' = {
							url: \'' . $marker['marker_image'] . '\',
							// Set the width and height to match the width and height of the uploaded image
							size: new google.maps.Size(' . $image_attributes[1] . ', ' . $image_attributes[2] . '),
							// The origin for this image is 0,0.
							origin: new google.maps.Point(0,0),
							// The anchor for this image is the bottom center of it (when scaled to halfsize for retina)
							anchor: new google.maps.Point(' . ( $image_attributes[1] /2) / 2 . ', ' . $image_attributes[2] / 2 . '),
							scaledSize: new google.maps.Size(' . $image_attributes[1]/2 . ', ' . $image_attributes[2]/2 . ')
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
						
						if ( isset( $image_attributes[2] ) ){
					  		$js_output .= '\'</div>\',
					  		pixelOffset: new google.maps.Size(0, ' . -($image_attributes[2] / 2) . ')';
						}
						else{
					  		$js_output .= '\'</div>\',
					  		pixelOffset: new google.maps.Size(0, ' . -30 . ')';
						}
						
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
			
	//Pull in the existing MP Stacks inline js string which is output the Footer.
	global $mp_stacks_footer_inline_js;
	$mp_stacks_footer_inline_js[ 'mp-stacks-googlemaps-' . $post_id ] = $js_output;
			
	//Return
	return $googlemaps_output;
	
}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_googlemaps', 10, 3);