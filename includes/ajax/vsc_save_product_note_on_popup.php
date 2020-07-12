<?php

add_action('wp_ajax_vsc_ajax_save_product_note_on_popup', 'vsc_ajax_save_product_note_on_popup_ajax_handler');
add_action('wp_ajax_nopriv_vsc_ajax_save_product_note_on_popup', 'vsc_ajax_save_product_note_on_popup_ajax_handler');

function vsc_ajax_save_product_note_on_popup_ajax_handler(){

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $product = wc_get_product( $product_id );
    $value = isset($_POST['value']) ? $_POST['value'] : '';

    // $product->update_meta_data( 'vsc_product_note', sanitize_text_field( $value ) );
    // $product->save();

    echo 'true';

    wp_die();

}