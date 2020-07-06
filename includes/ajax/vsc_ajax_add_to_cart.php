<?php

add_action('wp_ajax_vsc_ajax_add_to_cart', 'vsc_ajax_add_to_cart_ajax_handler');
add_action('wp_ajax_nopriv_vsc_ajax_add_to_cart', 'vsc_ajax_add_to_cart_ajax_handler');

function vsc_ajax_add_to_cart_ajax_handler(){

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = 1;

    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity) && 'publish' === $product_status) {

        do_action('woocommerce_ajax_added_to_cart', $product_id);

        $product_added_cart_count = vsc_get_item_qty_by_product_id($product_id);

        $product_added_popup = vsc_get_product_added_cart_popup($product_id);

        

        $data = array(
            'success' => true,
            'current_count' => $product_added_cart_count,
            'added_product_html' => $product_added_popup
        );
        echo (wp_send_json($data));

        WC_AJAX::get_refreshed_fragments();


    } else {

        $data = array(
            'error' => true,
            'error_msg' => 'There is something wrong. The product not added to cart');

        echo wp_send_json($data);
    }

    wp_die();

}