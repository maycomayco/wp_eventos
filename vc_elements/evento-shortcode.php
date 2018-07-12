<?php

/*
Element Description: Eventos
*/

// Element Class
class vcEventos extends WPBakeryShortCode {
		// Element Init
	function __construct() {
		add_action( 'init', array( $this, 'vc_eventos_mapping' ) );
		add_shortcode( 'vc_eventos', array( $this, 'vc_eventos_html' ) );
	}
	// Element Mapping
	public function vc_eventos_mapping() {
		// Stop all if VC is not enabled
		if ( !defined( 'WPB_VC_VERSION' ) ) {
			return;
		}
		// Map the block with vc_map()
		vc_map(
			array(
				'name' => __('Eventos - KNX', 'knx-eventos'),
				'base' => 'vc_eventos',	//igual al usado en el constructor
				'description' => __('Mostrar eventos vigentes', 'knx-eventos'),
				'category' => __('KNX Elementos', 'knx-eventos'),
				// 'icon' => 'vc-icon',
				'icon' => 'dashicons-calendar',
				'params' => array(
					array(
						"type" => "dropdown",
						"class" => "",
						"heading" => __( "Columnas", "knx-eventos" ),
						"param_name" => "grid_columns",
						"value"       => array(
							'2 Columns'  	=> '2',
							'3 Columns'		=> '3',
							'4 Columns'  	=> '4',
						),
						"std"         => '3',
					),
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",
						"heading" => __( "Numero de eventos", "knx-eventos" ),
						"param_name" => "event_posts_pro",
						"value" => __( "", "pro-elements" ),
						"description" => __( "Cuantos eventos usted desea mostrar?", "knx-eventos" ),
					),
				)
			)
		);
	}

	// Element HTML
	public function vc_eventos_html( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'grid_columns' => '3',
			'event_posts_pro' => '99',
		), $atts ) );
		$output_pro = '';
		$output_pro .= '<div class="knx_wrapper_general">';
		$postIds = $event_cat_pro; // get custom field value
		$arrayIds = explode(',', $postIds); // explode value into an array of ids
		if(count($arrayIds) <= 1) {
			// if array contains one element or less, there's spaces after comma's, or you only entered one id
			if( strpos($arrayIds[0], ',') !== false ) {
				// if the first array value has commas, there were spaces after ids entered
				$arrayIds = array(); // reset array
				$arrayIds = explode(', ', $postIds); // explode ids with space after comma's
			}
		}
		// WP_Query arguments
		// $args = array(
		// 	'post_type'              => array( 'event' ),
		// 	'post_status'            => array( 'publish' ),
		// 	'posts_per_page'         => $event_posts_pro,
		// 	'meta_query'             => array(
		// 		array(
		// 			'key'     => 'wpcf-fecha-de-vencimiento',	//esto registra la hora tambien.
		// 			'value'   => date( "U" ),
		// 			'compare' => '>',	//la comparacion es por el dia directamente.
		// 		),
		// 	),
		// );
		// The Query
		$carousel_query  = new WP_Query(
			array(
				'post_type'      => 'evento',
				// 'post_status'    => 'publish',
				'posts_per_page' => $event_posts_pro,
				'orderby'        => 'menu_order _knx-evento_fecha_evento title',	// 3 tipos de orden admite order, fecha y titulo
				'order'          => 'ASC',
				'meta_key'       => '_knx-evento_fecha_evento',	// field por el cual ordenamos
				'meta_value'     => date( "U" ), // fecha formato UNIX
				'meta_compare'   => '>',
			)
		);
		// comienzo HTML
		$count = 1; 
		$count_2 = 1;
		if ($grid_columns == 3) {	// identifico la clase de Visual Composer a utilizar
				$columns = 'vc_col-md-4 vc_col-lg-4 ';
			} else {
				if ($grid_columns == 4) {
 				$columns = 'vc_col-md-3 vc_col-lg-3 ';
				} else {
 				$columns = 'vc_col-md-6 vc_col-lg-6 ';
				}		
			}
		ob_start();	?>
		
		<div class="knx_list">
			<?php 
			$count = 1;
			$col_count_progression = $grid_columns;
			while ($carousel_query->have_posts()) : $carousel_query->the_post(); ?>
				<div class="knx_item <?php echo $columns; ?>">
						<?php include('evento-view.php'); ?>
				</div>
				<?php
				if ($count == $grid_columns) { echo '<div class="knx_clearfix"></div>'; $count = 0;};
				// } end del if que agregue yo
				?>
			<?php  $count ++; endwhile ; ?>
			<div class="knx_clearfix"></div>
			<?php wp_reset_query(); ?>
		</div>
		<?php
			return '</div><!-- close .event-container-pro --><div class="knx_clearfix"></div>' . $output_pro. ob_get_clean();
		}
	} // End Element Class

// Element Class Init
new vcEventos();