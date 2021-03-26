<?php
/**
 * Plugin Name: customizationwooplugin
 * Plugin URI: https://woocommerce.com/
 * Description:customizationwooplugin. Beautifully.
 * Version: 1.0.0
 * Author: Automattic
 * Text Domain: woocommerce
 * Domain Path: /i18n/languages/
 * Requires at least: 5.4
 * Requires PHP: 7.0
 */






add_filter('woocommerce_currency_symbol', 'add_my_currency_symbol', 10, 2);

function add_my_currency_symbol( $currency_symbol, $currency ) {
 
     switch( $currency ) {
          case 'ABC': $currency_symbol = '$'; break;
     }


     return $currency_symbol;
}




 // Add a new country to countries list

add_filter( 'woocommerce_countries',  'handsome_bearded_guy_add_my_country' );
function handsome_bearded_guy_add_my_country( $countries ) {
  $new_countries = array(
	                    'I Love India'  => __( 'Northern Ireland Love India', 'woocommerce' ),
	                    );

	return array_merge( $countries, $new_countries );
}

add_filter( 'woocommerce_continents', 'handsome_bearded_guy_add_my_country_to_continents' );
function handsome_bearded_guy_add_my_country_to_continents( $continents ) {
	$continents['EU']['countries'][] = 'I Love India';
	return $continents;
}



/**
 * Allow customers to access wp-admin
 */

add_filter( 'woocommerce_prevent_admin_access', '__return_false' );
add_filter( 'woocommerce_disable_admin_bar', '__return_false' );


/**
 * Automatically add product to cart on visit
 */
add_action( 'template_redirect', 'add_product_to_cart' );
function add_product_to_cart() {
	if ( ! is_admin() ) {
		$product_id = 00; //replace with your own product id
		$found = false;
		//check if product already in cart
		if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
			foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				if ( $_product->get_id() == $product_id )
					$found = true;
			}
           
			// if product not found, add it
			if ( ! $found )
				WC()->cart->add_to_cart( $product_id );
		} else {
			// if no products in cart, add it
			WC()->cart->add_to_cart( $product_id );
           
        
		}
       
	}
}


/**
 * Show product weight on archive pages
 */
add_action( 'woocommerce_after_shop_loop_item', 'rs_show_weights', 9 );

function rs_show_weights() {

    global $product;
    $weight = $product->get_weight();
   

    if ( $product->has_weight() ) {
        echo '<div class="product-meta"><span class="product-meta-label">Weight: </span>' . $weight . get_option('woocommerce_weight_unit') . '</div></br>';
    }
}



/**
 * Prevent PO box shipping
 */
add_action('woocommerce_after_checkout_validation', 'deny_pobox_postcode');

function deny_pobox_postcode( $posted ) {
  global $woocommerce;

  $address  = ( isset( $posted['shipping_address_1'] ) ) ? $posted['shipping_address_1'] : $posted['billing_address_1'];
  $postcode = ( isset( $posted['shipping_postcode'] ) ) ? $posted['shipping_postcode'] : $posted['billing_postcode'];

  $replace  = array(" ", ".", ",");
  $address  = strtolower( str_replace( $replace, '', $address ) );
  $postcode = strtolower( str_replace( $replace, '', $postcode ) );

  if ( strstr( $address, 'pobox' ) || strstr( $postcode, 'pobox' ) ) {
    wc_add_notice( sprintf( __( "Sorry, we cannot ship to PO BOX addresses.") ) ,'error' );
  }
}


/**
 * Notify admin when a new customer account is created
 */
add_action( 'woocommerce_created_customer', 'woocommerce_created_customer_admin_notification' );
function woocommerce_created_customer_admin_notification( $customer_id ) {
  wp_send_new_user_notifications( $customer_id, 'admin' );
}


/**
 * Trim zeros in price decimals
 **/
add_filter( 'woocommerce_price_trim_zeros', '__return_true' );



/**
 * Show product dimensions on archive pages for WC 3+
 */
add_action( 'woocommerce_after_shop_loop_item', 'rs_show_dimensions', 9 );

function rs_show_dimensions() {
    global $product;
    $dimensions = wc_format_dimensions($product->get_dimensions(false));

        if ( $product->has_dimensions() ) {
                echo '<div class="product-meta"><span class="product-meta-label">Dimensions: </span>' . $dimensions . '</div>';
        }
}




/**
 * Add or modify States
 */
add_filter( 'woocommerce_states', 'custom_woocommerce_states' );

function custom_woocommerce_states( $states ) {

//   $states['IN'] = array(
//     'XX1' => 'State 1', 
//     'XX2' => 'State 2'
//   );

  return $states;
}




/**
 * Hide shipping rates when free shipping is available.
 * Updated to support WooCommerce 2.6 Shipping Zones.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
function my_hide_shipping_when_free_is_available( $rates ) {
	$free = array();
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}

	return ! empty( $free ) ? $free : $rates;
}
add_filter( 'woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 100 );



/**
 * Rename a country
 */
add_filter( 'woocommerce_countries', 'rename_ireland' );

function rename_ireland( $countries ) {
   $countries['IE'] = 'Ireland';
   return $countries;
}


/**
 * Set a minimum order amount for checkout
 */
add_action( 'woocommerce_checkout_process', 'wc_minimum_order_amount' );
add_action( 'woocommerce_before_cart' , 'wc_minimum_order_amount' );
 
function wc_minimum_order_amount() {
    // Set this variable to specify a minimum order value
    $minimum = 30;

    if ( WC()->cart->total < $minimum ) {

        if( is_cart() ) {

            wc_print_notice( 
                sprintf( 'Your current order total is %s — you must have an order with a minimum of %s to place your order ' , 
                    wc_price( WC()->cart->total ), 
                    wc_price( $minimum )
                ), 'error' 
            );

        } else {

            wc_add_notice( 
                sprintf( 'Your current order total is %s — you must have an order with a minimum of %s to place your order' , 
                    wc_price( WC()->cart->total ), 
                    wc_price( $minimum )
                ), 'error' 
            );

        }
    }
}







class WC_Settings_Tab_Demo {

    /*
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_tab_demo', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_tab_demo', __CLASS__ . '::update_settings' );
    }
    
    
    /*
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_demo'] = __( 'Settings Demo Tab', 'woocommerce-settings-tab-demo' );
        return $settings_tabs;
    }


    /*
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }


    /*
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }


    /*
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {

        $settings = array(
            'section_title' => array(
                'name'     => __( 'Section Title', 'woocommerce-settings-tab-demo' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_settings_tab_demo_section_title'
            ),
            'title' => array(
                'name' => __( 'Title', 'woocommerce-settings-tab-demo' ),
                'type' => 'text',
                'desc' => __( 'This is some helper text', 'woocommerce-settings-tab-demo' ),
                'id'   => 'wc_settings_tab_demo_title'
            ),
            'description' => array(
                'name' => __( 'Description', 'woocommerce-settings-tab-demo' ),
                'type' => 'textarea',
                'desc' => __( 'This is a paragraph describing the setting. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda.', 'woocommerce-settings-tab-demo' ),
                'id'   => 'wc_settings_tab_demo_description'
            ),
            'section_end' => array(
                 'type' => 'sectionend',
                 'id' => 'wc_settings_tab_demo_section_end'
            )
        );

        return apply_filters( 'wc_settings_tab_demo_settings', $settings );
    }

}

WC_Settings_Tab_Demo::init();





add_action( 'woocommerce_product_options_general_product_data', 'misha_option_group' );
 
function misha_option_group() {
	echo '<div class="option_group">test</div>';
}


add_action( 'woocommerce_product_options_advanced', 'misha_adv_product_options');
function misha_adv_product_options(){
 
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
 
 
add_action( 'woocommerce_process_product_meta', 'misha_save_fields', 10, 2 );
function misha_save_fields( $id, $post ){
 
	if( !empty( $_POST['super_product'] ) ) {
		update_post_meta( $id, 'super_product', $_POST['super_product'] );
	} else {
		delete_post_meta( $id, 'super_product' );
	}
 
}







$address_fields = apply_filters('woocommerce_billing_fields', $address_fields);

$address_fields = apply_filters('woocommerce_shipping_fields', $address_fields);



// $this->checkout_fields['billing']    = $woocommerce->countries->get_address_fields( $this->get_value('billing_country'), 'billing_' );
// $this->checkout_fields['shipping']   = $woocommerce->countries->get_address_fields( $this->get_value('shipping_country'), 'shipping_' );
// $this->checkout_fields['account']    = array(
//     'account_username' => array(
//         'type' => 'text',
//         'label' => __('Account username', 'woocommerce'),
//         'placeholder' => _x('Username', 'placeholder', 'woocommerce')
//         ),
//     'account_password' => array(
//         'type' => 'password',
//         'label' => __('Account password', 'woocommerce'),
//         'placeholder' => _x('Password', 'placeholder', 'woocommerce'),
//         'class' => array('form-row-first')
//         ),
//     'account_password-2' => array(
//         'type' => 'password',
//         'label' => __('Account password', 'woocommerce'),
//         'placeholder' => _x('Password', 'placeholder', 'woocommerce'),
//         'class' => array('form-row-last'),
//         'label_class' => array('hidden')
//         )
//     );
// $this->checkout_fields['order']  = array(
//     'order_comments' => array(
//         'type' => 'textarea',
//         'class' => array('notes'),
//         'label' => __('Order Notes', 'woocommerce'),
//         'placeholder' => _x('Notes about your order, e.g. special notes for delivery.', 'placeholder', 'woocommerce')
//         )
//     );

//	$this->checkout_fields = apply_filters('woocommerce_checkout_fields', $this->checkout_fields);


	//_x('Notes about your order, e.g. special notes for delivery.', 'placeholder', 'woocommerce');


// //	Hook in
// add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// // Our hooked in function - $fields is passed via the filter!
// function custom_override_checkout_fields( $fields ) {
//      $fields['order']['order_comments']['placeholder'] = 'My new placeholder';
//      return $fields;
// }


// // Hook in
// add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// // Our hooked in function - $fields is passed via the filter!
// function custom_override_checkout_fields( $fields ) {
//      unset($fields['order']['order_comments']);

//      return $fields;
// }



// Hook in
add_filter( 'woocommerce_default_address_fields' , 'custom_override_default_address_fields' );

// Our hooked in function - $address_fields is passed via the filter!
function custom_override_default_address_fields( $address_fields ) {
     $address_fields['address_1']['required'] = false;

     return $address_fields;
}


$fields['billing']['your_field']['options'] = array(
	'option_1' => 'Option 1 text',
	'option_2' => 'Option 2 text'
  );


  // Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function – $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
     $fields['shipping']['shipping_phone'] = array(
        'label'     => __('Phone', 'woocommerce'),
    'placeholder'   => _x('Phone', 'placeholder', 'woocommerce'),
    'required'  => false,
    'class'     => array('form-row-wide'),
    'clear'     => true
     );

     return $fields;
}

/**
 * Display field value on the order edit page
 */
 
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Phone From Checkout Form').':</strong> ' . get_post_meta( $order->get_id(), '_shipping_phone', true ) . '</p>';
}