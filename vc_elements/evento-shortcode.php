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
  	            'icon' => 'vc-icon',
  	            'params' => array(
  	            	array(
  	            		"type" => "dropdown",
  	            		"class" => "",
  	            		"heading" => __( "Columnas", "knx-eventos" ),
  	            		"param_name" => "grid_columns",
  	            		"value"       => array(
  	            			'1 Column'   	=> '1',
  	            			'2 Columns'  	=> '2',
  	            			'3 Columns'		=> '3',
  	            			'4 Columns'  	=> '4',
  	            			'5 Columns'  	=> '5',
  	            			'6 Columns'  	=> '6',
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

	   			$output_pro .= '<div class="event-container-pro">';


	   			$postIds = $event_cat_pro; // get custom field value
   		    $arrayIds = explode(',', $postIds); // explode value into an array of ids
   		    if(count($arrayIds) <= 1) // if array contains one element or less, there's spaces after comma's, or you only entered one id
   		    {
   		        if( strpos($arrayIds[0], ',') !== false )// if the first array value has commas, there were spaces after ids entered
   		        {
   		            $arrayIds = array(); // reset array
   		            $arrayIds = explode(', ', $postIds); // explode ids with space after comma's
   		        }
   		    }

	   		  // WP_Query arguments
	   		  $args = array(
	   		  	'post_type'              => array( 'event' ),
	   		  	'post_status'            => array( 'publish' ),
	   		  	'posts_per_page'         => $event_posts_pro,
	   		  	'meta_query'             => array(
	   		  			array(
	   		  				'key'     => 'wpcf-fecha-de-vencimiento',	//esto registra la hora tambien.
	   		  				'value'   => date( "U" ),
	   		  				'compare' => '>',	//la comparacion es por el dia directamente.
	   		  			),
	   		  		),
	   		  );

	   		  // The Query
	   		  $carousel_query = new WP_Query( $args );

	   			$count = 1; $count_2 = 1;

	   			ob_start();
	   			?>
	   				<div class="event-list-pro">
	   					<?php

	   					// Esto hay que hacerlo general, porque esta vista funciona solo para el template de Ovalle
	   					$count = 1;
	   					$col_count_progression = $grid_columns;
	   					while ($carousel_query->have_posts()) : $carousel_query->the_post();

	   						// $fv = get_post_meta( get_the_ID(), 'wpcf-fecha-de-vencimiento' );
	   						// if ($fv[0] > date('U')) {	?>

		   						<div class="event-item-pro grid<?php echo esc_attr($grid_columns) ?>column-progression <?php if ($count == $grid_columns) { echo 'lastcolumn-progression';}; ?>">
		   							<?php get_template_part( 'template-parts/visual-composer/content', 'evento-vc' );	?>
		   						</div>
	   						<?php
	   							if ($count == $grid_columns) { echo '<div class="clearfix-pro"></div>'; $count = 0;};
	   						// } end del if que agregue yo
   							?>
	   					<?php  $count ++; endwhile ; ?>
	   					<div class="clearfix-pro"></div>
	   					<?php wp_reset_query(); ?>
	   				</div>

	   		   <?php

	   		   	return '</div><!-- close .event-container-pro --><div class="clearfix-pro"></div>' . $output_pro. ob_get_clean();

	    }

	} // End Element Class

	// Element Class Init
	new vcEventos();

