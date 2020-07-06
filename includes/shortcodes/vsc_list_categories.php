<?php



function vsc_vsc_list_categories_shortcode( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'class' => '',
	), $atts );

    $output = '';

    $cat_args = array(
        'hide_empty' => true,
    );
    $product_categories = get_terms( 'product_cat', $cat_args );
    if(!$product_categories){
        return $output; 
    }

    $output .= '<div class="vsc_categories_filter_container">';

        $output .= '<div class="vsc_categories_filter_tilte"><h3>Product Categories</h3></div>';

        $output .= '<div class="vsc_categories_filter_items"><ul class="vsc_categories" id="vsc_categories_list">';

        foreach($product_categories as $product_cat) {
            $output .= '<li class="vsc_categories_item"><a class="vsc_category_tag" href="#vsc_cat_'. $product_cat->slug .'" cat-id="'. $product_cat->term_id .'">'. $product_cat->name .'</a></li>';
        }
        $output .= '</ul></div>';

    $output .= '</div>';

	return $output;
}
add_shortcode('vsc_list_categories', 'vsc_vsc_list_categories_shortcode');

