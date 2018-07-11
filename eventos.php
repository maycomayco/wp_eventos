<?php
/*
Plugin Name: Eventos - KNX
Text Domain: knx-eventos
Plugin URI: http://kinexo.com
Description: Administrar eventos nunca fue tan sencillo!
Version: 0.2
Author: Mayco
Author URI: https://www.linkedin.com/in/mayco-barale-2563815a/
License: GPLv2 o posterior
*/

/*	Registro CP */

if ( ! function_exists('knx_eventos') ) {
	// Register Custom Post Type
	function knx_eventos() {
		$labels = array(
			'name'                  => _x( 'Eventos', 'Post Type General Name', 'knx-eventos' ),
			'singular_name'         => _x( 'Evento', 'Post Type Singular Name', 'knx-eventos' ),
			'menu_name'             => __( 'Eventos', 'knx-eventos' ),
			'name_admin_bar'        => __( 'Evento', 'knx-eventos' ),
			'archives'              => __( 'Items archives', 'knx-eventos' ),
			'attributes'            => __( 'Item Attributes', 'knx-eventos' ),
			'parent_item_colon'     => __( 'Parent Item:', 'knx-eventos' ),
			'all_items'             => __( 'Todos los eventos', 'knx-eventos' ),
			'add_new_item'          => __( 'Agregar nuevo evento', 'knx-eventos' ),
			'add_new'               => __( 'Agregar nuevo', 'knx-eventos' ),
			'new_item'              => __( 'Nuevo evento', 'knx-eventos' ),
			'edit_item'             => __( 'Editar evento', 'knx-eventos' ),
			'update_item'           => __( 'Actualizar evento', 'knx-eventos' ),
			'view_item'             => __( 'Ver evento', 'knx-eventos' ),
			'view_items'            => __( 'Ver eventos', 'knx-eventos' ),
			'search_items'          => __( 'Buscar evento', 'knx-eventos' ),
			'not_found'             => __( 'No encontrado', 'knx-eventos' ),
			'not_found_in_trash'    => __( 'No encontrado en la papelera', 'knx-eventos' ),
			'featured_image'        => __( 'Imagen destacada', 'knx-eventos' ),
			'set_featured_image'    => __( 'Agregar imagen destacada', 'knx-eventos' ),
			'remove_featured_image' => __( 'Remover imagen destacada', 'knx-eventos' ),
			'use_featured_image'    => __( 'Usar como imagen destacada', 'knx-eventos' ),
			'insert_into_item'      => __( 'Insertar en evento', 'knx-eventos' ),
			'uploaded_to_this_item' => __( 'Cargado a este evento', 'knx-eventos' ),
			'items_list'            => __( 'Eventos lista', 'knx-eventos' ),
			'items_list_navigation' => __( 'Eventos lista de navegacion', 'knx-eventos' ),
			'filter_items_list'     => __( 'Filtrar eventos', 'knx-eventos' ),
		);
		$args = array(
			'label'                 => __( 'Evento', 'knx-eventos' ),
			'description'           => __( 'Para administrar eventos', 'knx-eventos' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', ),
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
	add_action( 'init', 'knx_eventos', 0 );
}

/*	Agregamos metabox al CP */
add_action( 'cmb2_admin_init', 'cmb2_promocion_metaboxes' );
/** Define the metabox and field configurations. */
function cmb2_eventos_metaboxes() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_knx-evento_';
	/** Initiate the metabox	 */
	$cmb = new_cmb2_box( array(
		'id'            => 'datos_sobre_evento',
		'title'         => __( 'Datos sobre el evento', 'cmb2' ),
		'object_types'  => array( 'evento', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
	) );
	// vencimiento field
	$cmb->add_field( array(
		'name' => __( 'Fecha del evento', 'knx-eventos' ),
		'id'   => $prefix . 'fecha_evento',
		'type' => 'text_date_timestamp'
	) );
}

/*	Registramos modulo a Visual Composer - DEPRECATED*/ 
if( function_exists( 'vc_manager' ) ) {
	// Before VC Init
	add_action( 'vc_before_init', 'knx_html_eventos' );
	function knx_html_eventos() {
    // Require new custom Element
    require_once( plugin_dir_path( __FILE__ ) . 'vc_elements/evento-shortcode.php' );
	}

}