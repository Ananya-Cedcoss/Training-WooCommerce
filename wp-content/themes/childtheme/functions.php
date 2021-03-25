<?php  










// /**
//  * Exclude products from a particular category on the shop page.
//  */
// function custom_pre_get_posts_query( $q ) {

// 	$tax_query = (array) $q->get( 'tax_query' );

// 	$tax_query[] = array(
// 		'taxonomy' => 'product_cat',
// 		'field'    => 'slug',
// 		'terms'    => array( 'clothing' ), // Don't display products in the clothing category on the shop page.
// 		'operator' => 'NOT IN',
// 	);

// 	$q->set( 'tax_query', $tax_query );

// }
// add_action( 'woocommerce_product_query', 'custom_pre_get_posts_query' );





// //  /**
// //  * Add custom tracking code to the thank-you page
// //  */
// // add_action( 'woocommerce_thankyou', 'my_custom_tracking' );

// // function my_custom_tracking( $order_id ) {

// // 	// Lets grab the order
// // 	$order = wc_get_order( $order_id );

// // 	/**
// // 	 * Put your tracking code here
// // 	 * You can get the order total etc e.g. $order->get_total();
// // 	 */
	
// // 	// This is the order total
// // 	$order->get_total();
 
// // 	// This is how to grab line items from the order 
// // 	$line_items = $order->get_items();

// // 	// This loops over line items
// // 	foreach ( $line_items as $item ) {
// //   		// This will be a product
// //   		$product = $order->get_product_from_item( $item );
  
// //   		// This is the products SKU
// // 		$sku = $product->get_sku();
		
// // 		// This is the qty purchased
// // 		$qty = $item['qty'];
		
// // 		// Line item total cost including taxes and rounded
// // 		$total = $order->get_line_total( $item, true, true );
		
// // 		// Line item subtotal (before discounts)
// // 		$subtotal = $order->get_line_subtotal( $item, true, true );
// // 	}
// // }




// function custom_wc_ajax_variation_threshold( $qty, $product ) {
// 	return 100;
// }

// add_filter( 'woocommerce_ajax_variation_threshold', 'custom_wc_ajax_variation_threshold', 10, 2 );



// /**
//  * Allow HTML in term (category, tag) descriptions
//  */
// foreach ( array( 'pre_term_description' ) as $filter ) {
// 	remove_filter( $filter, 'wp_filter_kses' );
// 	if ( ! current_user_can( 'unfiltered_html' ) ) {
// 		add_filter( $filter, 'wp_filter_post_kses' );
// 	}
// }
 
// foreach ( array( 'term_description' ) as $filter ) {
// 	remove_filter( $filter, 'wp_kses_data' );
// }




// /**
//  * Override loop template and show quantities next to add to cart buttons
//  */
// add_filter( 'woocommerce_loop_add_to_cart_link', 'quantity_inputs_for_woocommerce_loop_add_to_cart_link', 10, 2 );
// function quantity_inputs_for_woocommerce_loop_add_to_cart_link( $html, $product ) {
// 	if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
// 		$html = '<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="cart" method="post" enctype="multipart/form-data">';
// 		$html .= woocommerce_quantity_input( array(), $product, false );
// 		$html .= '<button type="submit" class="button alt">' . esc_html( $product->add_to_cart_text() ) . '</button>';
// 		$html .= '</form>';
// 	}
// 	return $html;
// }




// /**
//  * Change the default state and country on the checkout page
//  */
// add_filter( 'default_checkout_billing_country', 'change_default_checkout_country' );
// add_filter( 'default_checkout_billing_state', 'change_default_checkout_state' );

// function change_default_checkout_country() {
//   return 'GB'; // country code
// }

// function change_default_checkout_state() {
//   return 'AZ'; // state code
// }




// /**
//  * Add custom sorting options (asc/desc)
//  */
// add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args' );
// function custom_woocommerce_get_catalog_ordering_args( $args ) {
//   $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
// 	if ( 'random_list' == $orderby_value ) {
// 		$args['orderby'] = 'rand';
// 		$args['order'] = '';
// 		$args['meta_key'] = '';
// 	}
// 	return $args;
// }
// add_filter( 'woocommerce_default_catalog_orderby_options', 'custom_woocommerce_catalog_orderby' );
// add_filter( 'woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby' );
// function custom_woocommerce_catalog_orderby( $sortby ) {
// 	$sortby['random_list'] = 'Random';
// 	return $sortby;
// }






// /**
//  * Make price widget draggable on touch devices
//  */
// add_action( 'wp_enqueue_scripts', 'load_touch_punch_js' , 35 );
// function load_touch_punch_js() {
// 	global $version;

// 	wp_enqueue_script( 'jquery-ui-widget' );
// 	wp_enqueue_script( 'jquery-ui-mouse' );
// 	wp_enqueue_script( 'jquery-ui-slider' );
// 	wp_register_script( 'woo-jquery-touch-punch', get_stylesheet_directory_uri() . "/js/jquery.ui.touch-punch.min.js", array('jquery'), $version, true );
// 	wp_enqueue_script( 'woo-jquery-touch-punch' );
// }




// /**
//  * Remove product data tabs
//  */
// add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

// function woo_remove_product_tabs( $tabs ) {

//   //  unset( $tabs['description'] );      	// Remove the description tab
//    // unset( $tabs['reviews'] ); 			// Remove the reviews tab
//    // unset( $tabs['additional_information'] );  	// Remove the additional information tab

//     return $tabs;
// }

// /**
//  * Rename product data tabs
//  */
// add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
// function woo_rename_tabs( $tabs ) {

// 	$tabs['description']['title'] = __( 'More Information' );		// Rename the description tab
// 	$tabs['reviews']['title'] = __( 'Ratings' );				// Rename the reviews tab
// 	$tabs['additional_information']['title'] = __( 'Product Data' );	// Rename the additional information tab

// 	return $tabs;

// }


// /**
//  * Hide category product count in product archives
//  */
// add_filter( 'woocommerce_subcategory_count_html', '__return_false' );



// /**
//  * Remove product content based on category
//  */
// add_action( 'wp', 'remove_product_content' );
// function remove_product_content() {
// 	// If a product in the 'Cookware' category is being viewed…
// 	if ( is_product() && has_term( 'Cookware', 'product_cat' ) ) {
// 		//… Remove the images
// 		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
// 		// For a full list of what can be removed please see woocommerce-hooks.php
// 	}
// }



// // /**
// //  * Auto Complete all WooCommerce orders.
// //  */
// // add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );
// // function custom_woocommerce_auto_complete_order( $order_id ) { 
	
// //     if ( ! $order_id ) {
// //         return;
// //     }

// //     $order = wc_get_order( $order_id );	
// //     $order->update_status( 'processing' );
// // }





// // /**
// //  * Add a 1% surcharge to your cart / checkout
// //  * change the $percentage to set the surcharge to a value to suit
// //  */
// // add_action( 'woocommerce_cart_calculate_fees','woocommerce_custom_surcharge' );
// // function woocommerce_custom_surcharge() {
// //   global $woocommerce;

// // 	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
// // 		return;

// // 	$percentage = 0.01;
	
// // 	$surcharge = ( $woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total ) * $percentage;	
// // 	$woocommerce->cart->add_fee( 'Surcharge', $surcharge, true, '' );

// // }

// /**
//  * Rename "home" in breadcrumb
//  */
// add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_home_text' );
// function wcc_change_breadcrumb_home_text( $defaults ) {
//     // Change the breadcrumb home text from 'Home' to 'Apartment'

// 	$defaults['home'] = 'Apartment';
// 	return $defaults;
// }


// /**
//  * Change the breadcrumb separator
//  */
// add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter' );
// function wcc_change_breadcrumb_delimiter( $defaults ) {
// 	// Change the breadcrumb delimeter from '/' to '>'
// 	$defaults['delimiter'] = ' > ';
// 	return $defaults;
// }

// add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter', 20 );


// // /**
// //  * Change several of the breadcrumb defaults
// //  */
// // add_filter( 'woocommerce_breadcrumb_defaults', 'jk_woocommerce_breadcrumbs' );
// // function jk_woocommerce_breadcrumbs() {
// //     return array(
// //             'delimiter'   => ' &#47; ',
// //             'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
// //             'wrap_after'  => '</nav>',
// //             'before'      => '',
// //             'after'       => '',
// //             'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
// //         );
// // }
// // add_filter( 'woocommerce_breadcrumb_defaults', 'jk_woocommerce_breadcrumbs', 20 );



// /**
//  * Replace the home link URL
//  */
// add_filter( 'woocommerce_breadcrumb_home_url', 'woo_custom_breadrumb_home_url' );
// function woo_custom_breadrumb_home_url() {
//     return 'http://localhost:10036/shop/';
// }

// /**
//  * Remove breadcrumbs for Storefront theme
//  */
// add_action( 'init', 'wc_remove_storefront_breadcrumbs');

// function wc_remove_storefront_breadcrumbs() {
//   remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
// }





// /**
// * Apply a coupon for minimum cart total
// */

// add_action( 'woocommerce_before_cart' , 'add_coupon_notice' );
// add_action( 'woocommerce_before_checkout_form' , 'add_coupon_notice' );

// function add_coupon_notice() {

//         $cart_total = WC()->cart->get_subtotal();
//         $minimum_amount = 50;
//         $currency_code = get_woocommerce_currency();
//         wc_clear_notices();

//        if ( $cart_total < $minimum_amount ) {
//               WC()->cart->remove_coupon( '	xhzghp2x' );
//               wc_print_notice( "Get 50% off if you spend more than $minimum_amount $currency_code!", 'notice' );
//         } else {
//               WC()->cart->apply_coupon( '	xhzghp2x' );
//               wc_print_notice( 'You just got 50% off your order!', 'notice' );
//         }
//           wc_clear_notices();
// }





/**
 * Allow shortcodes in product excerpts
 */
if (!function_exists('woocommerce_template_single_excerpt')) {
    function woocommerce_template_single_excerpt( $post ) {
           global $post;
		  
		   if ($post->post_excerpt) echo '<div itemprop="description">' . do_shortcode(wpautop(wptexturize($post->post_excerpt))) . '</div>';
    }
 }




/**
 * Adjust the quantity input values
 */
add_filter( 'woocommerce_quantity_input_args', 'jk_woocommerce_quantity_input_args', 10, 2 ); // Simple products

function jk_woocommerce_quantity_input_args( $args, $product ) {
	if ( is_singular( 'product' ) ) {
		$args['input_value'] 	= 2;	// Starting value (we only want to affect product pages, not cart)
	}
	$args['max_value'] 	= 80; 	// Maximum value
	$args['min_value'] 	= 2;   	// Minimum value
	$args['step'] 		= 2;    // Quantity steps
	return $args;
}

add_filter( 'woocommerce_available_variation', 'jk_woocommerce_available_variation' ); // Variations

function jk_woocommerce_available_variation( $args ) {
	$args['max_qty'] = 80; 		// Maximum value (variations)
	$args['min_qty'] = 2;   	// Minimum value (variations)
	return $args;
}	


