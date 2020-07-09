<?php
function vsc_products_shortcode( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'class' => 'caption',
	), $atts );

    $output = '';

    $output .= '<div class="vsc-products-list-container">';

        $cat_id = 25;
        if( $term = get_term_by( 'id', $cat_id, 'product_cat' ) ){
            $output .= '<div class="vsc_category_title_wrap"><h3 class="vsc_cat_title"></h3></div>';
        }

        $output .= '<ul id="vsc_products_list" class="products vsc-products-wrapper">';

            // $args = array(
            //     'post_type' => 'product',
            //     'posts_per_page' => 20,
            //     'post_status' => 'publish',
            //     'tax_query' => array(
            //         array(
            //             'taxonomy' => 'product_cat',
            //             'field'    => 'term_id',
            //             'terms'    => $cat_id,
            //         ),
            //     ),
            // );
            // $loop = new WP_Query( $args );
            // if ( $loop->have_posts()) {



            //     while ( $loop->have_posts() ) : $loop->the_post();

            //         include 'vsc_product_loop.php';
                    
            //     endwhile;

            //     wp_reset_postdata();


            // } 
            
        $output .= '</ul>';


    $output .= '</div>';

	return $output;
}
add_shortcode('vsc_products', 'vsc_products_shortcode');