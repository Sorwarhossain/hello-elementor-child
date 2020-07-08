<?php

add_action('wp_ajax_vsc_remove_items_from_cart', 'vsc_remove_items_from_cart_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_vsc_remove_items_from_cart', 'vsc_remove_items_from_cart_ajax_handler'); // wp_ajax_nopriv_{action}

function vsc_remove_items_from_cart_ajax_handler(){

    
    $product_id = $_POST['product_id'];
    if(empty($product_id)){
        wp_die();
    }

    if(vsc_remove_cart_item_by_product_id( $product_id )){
        $data = array(
            'success' => true,
        );
        echo (wp_send_json($data));
        WC_AJAX::get_refreshed_fragments();
    } 
    
	die; // here we exit the script and even no wp_reset_query() required!
}
 
 
 
