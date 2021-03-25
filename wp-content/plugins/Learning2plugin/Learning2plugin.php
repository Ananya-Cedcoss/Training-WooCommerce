<?php
/*
Plugin Name: Learning 2 
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





//manage_product_posts_custom_column
add_filter( 'manage_edit-product_columns', 'related_product_col' );
/** related_product_col function   */
function related_product_col( $columns ) {
	$new_columns = ( is_array ( $columns) ) ? $columns : array();
	$new_columns['RELATED'] = 'Related Product';
	return $new_columns;
}

add_action( 'manage_product_posts_custom_column', 'related_product_col_data', 2 );
/** Related_product_col_data function   */
function related_product_col_data( $column ) {
	global $post;
	$related_product_ids = get_post_meta( $post->ID, '_related_ids', true );
	if ( $column == 'RELATED' ) {
		if ( isset( $related_product_ids ) && !empty( $related_product_ids ) ) {
			echo count( $related_product_ids ) . ' [' . implode( ', ', $related_product_ids ) . ']';
		} else {
			echo '--';
	}
	}
}

add_filter( 'manage_edit-product_sortable_columns', 'related_product_col_sort' );
/** Related_product_col_sort function   */
function related_product_col_sort( $columns ) {
	$custom = array(
		'RELATED' => '_related_ids',
	);
	return wp_parse_args( $custom, $columns );
}




add_filter( 'manage_edit-product_columns', 'change_columns_filter', 10, 1 );
/** Change_columns_filter function   */
function change_columns_filter( $columns ) {
	unset( $columns['sku'] );
	return $columns;
}





// ADDING 2 NEW COLUMNS WITH THEIR TITLES (keeping "Total" and "Actions" columns at the end)
add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column', 20 );
/** Custom_shop_order_column function   */
function custom_shop_order_column( $columns ) {
	$reordered_columns = array();

	// Inserting columns to a specific location.
	foreach ( $columns as $key => $column){
		$reordered_columns[$key] = $column;
		if ( $key == 'order_status' ) {
			// Inserting after "Status" column.
			$reordered_columns['my-column1'] = __( 'Custom Field', 'theme_domain' );		
		}
	}
	return $reordered_columns;
}

// Adding custom fields meta data for each new column (example).
add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 20, 2 );
/** Custom_orders_list_column_content function   */
function custom_orders_list_column_content( $column, $post_id ) {
	switch ( $column )
	{
		case 'my-column1' :
			// Get custom post meta data.
			$my_var_one = get_post_meta( $post_id, '_the_meta_key1', true );
			if ( ! empty ( $my_var_one ) )
				echo $my_var_one;

			// Testing (to be removed) - Empty value case.
			else 
				echo '<small>(<em>no value</em>)</small>';

			break;

	}
}






////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



// Adding to admin order list bulk dropdown a custom action 'custom_downloads'


add_filter( 'bulk_actions-edit-shop_order', 'downloads_bulk_actions_edit_product', 20, 1 );


function downloads_bulk_actions_edit_product( $actions ) {
    $actions['write_downloads'] = __( 'Download orders', 'woocommerce' );
    return $actions;
}

// Make the action from selected orders
add_filter( 'handle_bulk_actions-edit-shop_order', 'downloads_handle_bulk_action_edit_shop_order', 10, 3 );
function downloads_handle_bulk_action_edit_shop_order( $redirect_to, $action, $post_ids ) {
    if ( $action !== 'write_downloads' )
        return $redirect_to; // Exit

    global $attach_download_dir, $attach_download_file; // ???

    $processed_ids = array();

    foreach ( $post_ids as $post_id ) {
        $order = wc_get_order( $post_id );
        $order_data = $order->get_data();

        // Your code to be executed on each selected order
        fwrite($myfile,
            $order_data['date_created']->date('d/M/Y') . '; ' .
            '#' . ( ( $order->get_type() === 'shop_order' ) ? $order->get_id() : $order->get_parent_id() ) . '; ' .
            '#' . $order->get_id()
        );
        $processed_ids[] = $post_id;
    }

    return $redirect_to = add_query_arg( array(
        'write_downloads' => '1',
        'processed_count' => count( $processed_ids ),
        'processed_ids' => implode( ',', $processed_ids ),
    ), $redirect_to );
}

// The results notice from bulk action on orders
add_action( 'admin_notices', 'downloads_bulk_action_admin_notice' );
function downloads_bulk_action_admin_notice() {
    if ( empty( $_REQUEST['write_downloads'] ) ) return; // Exit

    $count = intval( $_REQUEST['processed_count'] );

    printf( '<div id="message" class="updated fade"><p>' .
        _n( 'Processed %s Order for downloads.',
        'Processed %s Orders for downloads.',
        $count,
        'write_downloads'
    ) . '</p></div>', $count );
}




add_filter( 'bulk_actions-edit-product', 'register_my_bulk_actions' );
 
function register_my_bulk_actions($bulk_actions) {
  $bulk_actions['email_to_eric'] = __( 'Bulk Email', 'email_to_eric');
  return $bulk_actions;
}



add_filter( 'handle_bulk_actions-edit-product', 'my_bulk_action_handler', 10, 3 );
 
function my_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
  if ( $doaction !== 'email_to_eric' ) {
    return $redirect_to;
  }
  foreach ( $post_ids as $post_id ) {
    // Perform action for each post.
  }
  $redirect_to = add_query_arg( 'bulk_emailed_posts', count( $post_ids ), $redirect_to );
  return $redirect_to;
}



add_action( 'admin_notices', 'my_bulk_action_admin_notice' );
 
function my_bulk_action_admin_notice() {
  if ( ! empty( $_REQUEST['bulk_emailed_posts'] ) ) {
    $emailed_count = intval( $_REQUEST['bulk_emailed_posts'] );
    printf( '<div id="message" class="updated fade">' .
      _n( 'Emailed %s post in Bulk.',
        'Emailed %s posts in Bulk.',
        $emailed_count,
        'email_to_eric'
      ) . '</div>', $emailed_count );
  }
}



/////////////////////////////////////////////////////////////////////////////////



function register_shipment_arrival_order_status() {
    register_post_status( 'wc-arrival-shipment', array(
        'label'                     => 'Shipment Arrival',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Shipment Arrival <span class="count">(%s)</span>', 'Shipment Arrival <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_shipment_arrival_order_status' );
function add_awaiting_shipment_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-arrival-shipment'] = 'Shipment Arrival';
        }
    }
    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_awaiting_shipment_to_order_statuses' );


/////////////////////////////////////////////////////////////////////////////////////////////////


add_filter( 'woocommerce_product_data_tabs', 'wk_custom_product_tab', 10, 1 );



function wk_custom_product_tab( $default_tabs ) {
    $default_tabs['custom_tab'] = array(
        'label'   =>  __( 'Custom Tab', 'domain' ),
        'target'  =>  'wk_custom_tab_data',
        'priority' => 60,
		'class'    => array( 'custom_tab', 'show_if_simple', 'show_if_variable' ), 
    );
    return $default_tabs;
}





add_action( 'woocommerce_product_data_panels', 'wk_custom_tab_data' );

function wk_custom_tab_data() {
   echo '<div id="wk_custom_tab_data" class="panel woocommerce_options_panel">// add content here</div>';
}


///////////////////////////////////////////////////////////////



add_action( 'woocommerce_variable_product_before_variations', 'misha_option_group' );
 
function misha_option_group() {
	echo '<div class="option_group add_product_images hide-if-no-js"> <a href="#" data-choose="Add images to product gallery" data-update="Add to gallery" data-delete="Delete image" data-text="Delete"> Additional Images </a></div>';

}


//require_once( plugin_dir_path( __FILE__ ) . '/libraries/action-scheduler/action-scheduler.php' );


function eg_schedule_midnight_log() {
	if ( false === as_next_scheduled_action( 'eg_midnight_log' ) ) {
		as_schedule_recurring_action( strtotime( 'tomorrow' ), DAY_IN_SECONDS, 'eg_midnight_log' );
	}
}
add_action( 'init', 'eg_schedule_midnight_log' );

/**
 * A callback to run when the 'eg_midnight_log' scheduled action is run.
 */
function eg_log_action_data() {
	error_log( 'It is just after midnight on ' . date( 'Y-m-d' ) );
}
add_action( 'eg_midnight_log', 'eg_log_action_data' );