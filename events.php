<?php
/*
Plugin Name: WP Events manager
Plugin URI: http://kinexo.com
Description: Managing events has never been so easy!
Version: 0.5
Author: Mayco Barale
Author URI: https://www.linkedin.com/in/mayco-barale-2563815a/
Text Domain: knx-events
Domain Path: /languages
License: GPLv2 o posterior
*/

/*	Registro CP */

if ( ! function_exists('knx_setup_eventos') ) {
	// Register Custom Post Type
	function knx_setup_eventos() {
		$labels = array(
			'name'                  => _x( 'Events', 'Post Type General Name', 'knx-events' ),
			'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'knx-events' ),
			'menu_name'             => __( 'Events', 'knx-events' ),
			'name_admin_bar'        => __( 'Event', 'knx-events' ),
			'archives'              => __( 'Items archives', 'knx-events' ),
			'attributes'            => __( 'Item Attributes', 'knx-events' ),
			'parent_item_colon'     => __( 'Parent Item:', 'knx-events' ),
			'all_items'             => __( 'All items', 'knx-events' ),
			'add_new_item'          => __( 'Add new item', 'knx-events' ),
			'add_new'               => __( 'Add new', 'knx-events' ),
			'new_item'              => __( 'New item', 'knx-events' ),
			'edit_item'             => __( 'Edit item', 'knx-events' ),
			'update_item'           => __( 'Update item', 'knx-events' ),
			'view_item'             => __( 'View item', 'knx-events' ),
			'view_items'            => __( 'View items', 'knx-events' ),
			'search_items'          => __( 'Search items', 'knx-events' ),
			'not_found'             => __( 'Not found', 'knx-events' ),
			'not_found_in_trash'    => __( 'Not found in trash', 'knx-events' ),
			'featured_image'        => __( 'Featured image', 'knx-events' ),
			'set_featured_image'    => __( 'Set featured image', 'knx-events' ),
			'remove_featured_image' => __( 'Remove featured image', 'knx-events' ),
			'use_featured_image'    => __( 'Use featured image', 'knx-events' ),
			'insert_into_item'      => __( 'Insert in to item', 'knx-events' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'knx-events' ),
			'items_list'            => __( 'Items list', 'knx-events' ),
			'items_list_navigation' => __( 'Items list navigation', 'knx-events' ),
			'filter_items_list'     => __( 'Filter items list', 'knx-events' ),
		);
		$args = array(
			'label'                 => __( 'Event', 'knx-events' ),
			'description'           => __( 'To manage events', 'knx-events' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 6,
			'menu_icon'             => 'dashicons-calendar',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'evento', $args );
	}
	add_action( 'init', 'knx_setup_eventos', 0 );
}

/*	Agregamos metabox al CP */
add_action( 'cmb2_admin_init', 'cmb2_eventos_metaboxes' );
/** Define the metabox and field configurations. */
function cmb2_eventos_metaboxes() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_knx-evento_';
	/** Initiate the metabox	 */
	$cmb = new_cmb2_box( array(
		'id'            => 'datos_sobre_evento',
		'title'         => __( 'Event information', 'knx-events' ),
		'object_types'  => array( 'evento', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
	) );
	// vencimiento field
	$cmb->add_field( array(
		'name' => __( 'End date', 'knx-events' ),
		'id'   => $prefix . 'fecha_evento',
		'type' => 'text_date_timestamp',
		'desc' => __( 'End date for the event.', 'knx-events' ),
	) );
}

// Add the custom columns to the evento post type: redefined array of columns
// Mantenemos el nombre EVENTO, porque el Custom Post ya estaba registrado 
add_filter( 'manage_evento_posts_columns', 'evento_columns' );
function evento_columns( $columns ) {
    $columns = array(
      'cb' => $columns['cb'],
      'title' => __( 'Title' ),
      'end_date' => __( 'End Date', 'knx-events' ),
      'date' => __( 'Date'),
    ); 
  return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_evento_posts_custom_column' , 'custom_evento_column', 10, 2 );
function custom_evento_column( $column, $post_id ) {
  switch ( $column ) {
	  case 'end_date' :
      $terms = get_post_meta( $post_id, '_knx-evento_fecha_evento', true );
      echo gmdate('d/m/Y', $terms);
    break;
  }
}

// Make Column 'End Date' Sortable, add column to sortable columns array
add_filter( 'manage_edit-evento_sortable_columns', 'evento_sortable_column');
function evento_sortable_column( $columns ) {
  $columns['end_date'] = '_knx-evento_fecha_evento';
  return $columns;
}

// Make query to order by '_knx-evento_fecha_evento', type of column set to 'text_date_timestamp'
add_action( 'pre_get_posts', 'end_date_posts_orderby' );
function end_date_posts_orderby( $query ) {
  if( ! is_admin() || ! $query->is_main_query() ) {
    return;
  }
  if ( '_knx-evento_fecha_evento' === $query->get( 'orderby') ) {
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'meta_key', '_knx-evento_fecha_evento' );
    $query->set( 'meta_type', 'text_date_timestamp' );
  }
}



/*	Registramos modulo a Visual Composer */ 
if( function_exists( 'vc_manager' ) ) {
	// Before VC Init
	add_action( 'vc_before_init', 'knx_html_eventos' );
	function knx_html_eventos() {
    // Require new custom Element
    require_once( plugin_dir_path( __FILE__ ) . 'vc_elements/evento-shortcode.php' );
	}
}

