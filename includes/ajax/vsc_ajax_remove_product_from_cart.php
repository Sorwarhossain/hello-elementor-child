<?php

add_action('wp_ajax_vsc_ajax_remove_product_from_cart', 'vsc_ajax_remove_product_from_cart_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_vsc_ajax_remove_product_from_cart', 'vsc_ajax_remove_product_from_cart_handler'); // wp_ajax_nopriv_{action}

function vsc_ajax_remove_product_from_cart_handler(){

    
    $product_id = $_POST['product_id'];
    if(empty($product_id)){
        wp_die();
    }

    if(vsc_remove_cart_item_by_product_id( $product_id )){
        echo 'true';
    } 
    
	die; // here we exit the script and even no wp_reset_query() required!
}
 