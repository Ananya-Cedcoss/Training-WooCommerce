<?php
/*
Plugin Name: Learning 3 Plugin
Plugin URI: http://woothemes.com/woocommerce
Description: Practise WooCommerce Add custom columns in shop order, products listing page backend.
Add custom bulk actions in shop orders, products listing page backend.
Add custom order statuses in the woocommerce shop order edit page and should be visible on the order listing page.
Add custom tabs in woocommerce products edit page for (simple and variable product).
Add image gallery option in variations of variable product edit page.

Version: 1.0
Author: learning
Author URI: http://woothemes.com/
	Copyright: © 2009-2011 WooThemes.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/



_x('Notes about your order, e.g. special notes for delivery.', 'placeholder', 'woocommerce');



// Hook in.
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );


/** Our hooked in function - $fields is passed via the filter! */
function custom_override_checkout_fields( $fields ) {
	$fields['order']['order_comments']['placeholder'] = 'My new placeholder';
	$fields['order']['order_comments']['label']       = 'My new label';
	return $fields;
}





// checkbox field.
add_action( 'woocommerce_after_order_notes', 'quadlayers_subscribe_checkout' );
/** Quadlayers_subscribe_checkout in function $checkout variable  */
function quadlayers_subscribe_checkout( $checkout ) {
	woocommerce_form_field( 'subscriber', array(
		'type' => 'checkbox',
// Required' => true.
				'class' => array('custom-field form-row-wide' ),
				'label' => ' Subscribe to our newsletter.',
	), $checkout->get_value( 'subscriber' ) );
}


// Hook in.

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields_shipping' );


/** The custom_override_checkout_fields_shipping in function */
function custom_override_checkout_fields_shipping( $fields ) {

	$fields['shipping']['shipping_phone'] = array(

		'label'       => __( 'Phone xdf', 'woocommerce' ),

		'placeholder' => _x( 'Phone zsdf', 'placeholder', 'woocommerce' ),

		'required'    => false,

		'class'       => array( 'form-row-wide' ),

		'clear'       => true,

	);

	return $fields;

}



add_action( 'woocommerce_admin_order_data_after_shipping_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );


/** The my_custom_checkout_field_display_admin_order_meta in function */
function my_custom_checkout_field_display_admin_order_meta( $order ) {

	global $post_id;

	$order = new WC_Order( $post_id );

	echo ' <p><strong> ' .__('Field Value'). ' :</strong> ' . get_post_meta( $order->get_id(), '_shipping_field_value ', true ) . ' </p> ';

}


// woocommerce_checkout_before_order_review.
// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////.
add_action( 'woocommerce_before_checkout_form', 'bbloomer_cart_on_checkout_page_only', 5 );
/** The bbloomer_cart_on_checkout_page_only in function */
function bbloomer_cart_on_checkout_page_only() {
	if ( is_wc_endpoint_url( 'order-received' ) ) return;
	//echo do_shortcode( '[product id="315"]' );
	$cart_data = WC()->session->get('cart');

	$Number_of_products = count( $cart_data );

	for ( $x = 0; $x < $Number_of_products; $x++ ) {
	$product = wc_get_product( $cart_data[ array_keys( $cart_data )[ $x ] ][ 'product_id' ] );
	printf('<h2>This is the <b>'.$product->get_name().' </b>Product</h2>');
	
	printf('<h5>'.$product->get_image().'</h5>');
	echo '<a href='.get_permalink( $product->get_id() ).'> View Product </a>';
	echo do_shortcode('[add_to_cart id="'.$product->get_id().'" show_price="false" style="border:none;" class="my-addtocart"]');
	}

	// echo $cart_data[ array_keys( $cart_data )[0] ][ 'product_id' ];
	// print_r( $cart_data[ array_keys( $cart_data )[0] ] );
	// print_r( count( $cart_data ) );

	echo do_shortcode( '[woocommerce_cart]' );

}




// Action woocommerce_product_options_advanced.
add_action( 'woocommerce_product_options_advanced', 'misha_adv_product_options' );
/** The misha_adv_product_options in function */
function misha_adv_product_options() {

	echo '<div class="options_group">';

	woocommerce_wp_checkbox( array(
		'id'      => 'super_product',
		'value'   => get_post_meta( get_the_ID(), 'super_product', true ),
		'label'   => 'This is a super product',
		'desc_tip' => true,
		'description' => 'If it is not a regular WooCommerce product',
	) );

	echo '</div>';

}

// Action woocommerce_process_product_meta.
add_action( 'woocommerce_process_product_meta', 'misha_save_fields', 10, 2 );
/** The misha_save_fields in function */
function misha_save_fields( $id, $post ) {
	update_post_meta( $id, 'super_product', $_POST['super_product'] );
}


// Action woocommerce_before_add_to_cart_form.
add_filter( 'woocommerce_before_add_to_cart_form', 'woo_remove_product_tabs', 98 );
/** The woo_remove_product_tabs in function */
function woo_remove_product_tabs( $post ) {

	$a = get_post_meta( get_the_ID(), 'super_product', false );

	if ( $a[0] == 'yes' ){
		echo '<br><div class="super_product"><input type="checkbox" disabled="disabled" checked="checked" > <span  style="margin-left:6px;" >Is Super Product</span></div>';
	}
	else {
		echo '<br><div class="super_product"><input type="checkbox" disabled="disabled"  ><span  style="margin-left:6px;" >Is Super Product</span></div>';
	}

	return $tabs;
}

// woocommerce_after_shop_loop_item_title.
add_filter( 'woocommerce_after_shop_loop_item_title', 'woo_remove_product_tabs', 99 );





////////////////////////////////////////////////////////////////////////////////////////////////////////////


function cfwc_create_custom_field() {
	$args = array(
	'id' => 'custom_text_field_title',
	'label' => __( 'Custom Text Field Title', 'cfwc' ),
	'class' => 'cfwc-custom-field',
	'desc_tip' => true,
	'description' => __( 'Enter the title of your custom text field.', 'ctwc' ),
	);
	woocommerce_wp_text_input( $args );
   }
   add_action( 'woocommerce_product_options_general_product_data', 'cfwc_create_custom_field' );



   function cfwc_save_custom_field( $post_id ) {
	$product = wc_get_product( $post_id );
	$title = isset( $_POST['custom_text_field_title'] ) ? $_POST['custom_text_field_title'] : '';
	$product->update_meta_data( 'custom_text_field_title', sanitize_text_field( $title ) );
	$product->save();
   }
   add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field' );


   function cfwc_display_custom_field() {
	global $post;
	// Check for the custom field value
	
	$product = wc_get_product( $post->ID );

	$title = $product->get_meta( 'custom_text_field_title' );
	
	if( $title ) {
	// Only display our field if we've got a value for the field title
	printf(
	'<div class="cfwc-custom-field-wrapper"><label for="cfwc-title-field">%s</label></div><br>',
	esc_html( $title )
	);
	}
	else{
		printf(
			'<div class="cfwc-custom-field-wrapper"><label for="cfwc-title-field">%s</label></div><br>',
			'No Title Given'
			);

	}
   }
   add_action( 'woocommerce_before_add_to_cart_button', 'cfwc_display_custom_field' );