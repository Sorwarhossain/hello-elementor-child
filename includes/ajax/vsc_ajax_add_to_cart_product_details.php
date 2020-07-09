<?php

add_action('wp_ajax_vsc_ajax_add_to_cart_product_popup', 'vsc_ajax_add_to_cart_product_popup_ajax_handler');
add_action('wp_ajax_nopriv_vsc_ajax_add_to_cart_product_popup', 'vsc_ajax_add_to_cart_product_popup_ajax_handler');

function vsc_ajax_add_to_cart_product_popup_ajax_handler(){

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $product = wc_get_product( $product_id );
    $quantity = 1;

    $vsc_product_unit_type = '';

    if(isset($_POST['vsc_product_note'])){
        $vsc_product_note = $_POST['vsc_product_note'];
    } else {
        $vsc_product_note = false;
    }

    $error = false;

    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    

    if ($passed_validation && 'publish' === $product_status) {

        $added_to_cart = false;

        // If the product is variable product
        if(isset($_POST['variation_per_kg_or_item'])){

            $variation_per_kg_or_item = json_decode($_POST['variation_per_kg_or_item']);

            $variations = $product->get_available_variations();
            $variation_id = false;
            foreach($variations as $variation){

                if($variation_per_kg_or_item){
                    if( $variation['attributes']['attribute_pa_price-type'] == 'price-per-kg' ){
                        $variation_id = $variation['variation_id'];
                        $quantity = 0.5;
                        $vsc_product_unit_type = 'ק״ג';
                    }
                } else {
                    if( $variation['attributes']['attribute_pa_price-type'] == 'price-per-item' ){
                        $variation_id = $variation['variation_id'];
                        $vsc_product_unit_type = 'יח׳';
                    }
                }
                
            }

            
            if($variation_id){
                $added_to_cart = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
            }
            
        } else {
            $added_to_cart = WC()->cart->add_to_cart($product_id, $quantity);
            $vsc_product_unit_type = vsc_get_product_unit_type_for_simple_product($product_id);
        }

        if($added_to_cart){
            do_action('woocommerce_ajax_added_to_cart', $product_id);

            $product_added_cart_count = vsc_get_item_qty_by_product_id($product_id);

            $product_added_popup = vsc_get_product_added_cart_popup($product_id, $vsc_product_unit_type);

            if($vsc_product_note){
                $product->update_meta_data( 'vsc_product_note', sanitize_text_field( $vsc_product_note ) );
                $product->save();
            }

            $data = array(
                'success' => true,
                'current_count' => $product_added_cart_count,
                'added_product_html' => $product_added_popup
            );
            echo (wp_send_json($data));

            WC_AJAX::get_refreshed_fragments();
        } else {
            $error = true;
        }


    } else {

        $error = true;
        
    }

    if($error){
        $data = array(
            'error' => true,
            'error_msg' => 'There is something wrong. The product not added to cart');

        echo wp_send_json($data);
    }

    wp_die();

}