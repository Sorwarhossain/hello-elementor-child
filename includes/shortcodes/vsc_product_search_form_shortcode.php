<?php

function vsc_search_form_shortcode( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'class' => '',
	), $atts );

    $output = '';

    $output .= '<div class="_vsc_search_form_wrapper">';

        $output .= '
            <div class="vsc_search_field">
                <input id="vsc_product_search_field" type="text" placeholder="חיפוש..." autocomplete="off">
                <i class="fa fa-search"></i>
            </div>
        ';
    $output .= '</div>';

	return $output;
}
add_shortcode('vsc_search_form', 'vsc_search_form_shortcode');

