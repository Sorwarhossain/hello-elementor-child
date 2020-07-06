<?php
function vsc_products_shortcode( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'class' => 'caption',
	), $atts );

    $output = '';

    $output .= '<div class="vsc-products-list-container">';

    $displayed_products = 0;
    $displayable_count = 10;
    $break_loop = false;

    $cat_args = array(
        'hide_empty' => true,
    );
    $product_categories = get_terms( 'product_cat', $cat_args );

    if($product_categories && !$break_loop){

        $output .= '<ul id="vsc_products_list" class="products vsc-products-wrapper">';

        foreach($product_categories as $product_cat){

            // If the products are displayed then just break the loop.
            if($break_loop) continue;
        
            $output .= '<li class="product vsc-product-item category-title" id="vsc_cat_'. $product_cat->slug .'"><h3 class="vsc_cat_title">'. $product_cat->name .'</h3></li>';

            
            
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => $displayable_count,
                'post_status' => 'publish',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => $product_cat->slug,
                    ),
                ),
            );
            $loop = new WP_Query( $args );
            if ( $loop->have_posts() && !$break_loop) {



                while ( $loop->have_posts() && !$break_loop) : $loop->the_post();

                    include 'vsc_product_loop.php';
                    
                    $displayed_products++;
                    if($displayed_products == $displayable_count) $break_loop = true;
                endwhile;

                wp_reset_postdata();


            } 
            
            
        }
        $output .= '</ul>';
        

    }



    $output .= '</div>';

	return $output;
}
add_shortcode('vsc_products', 'vsc_products_shortcode');