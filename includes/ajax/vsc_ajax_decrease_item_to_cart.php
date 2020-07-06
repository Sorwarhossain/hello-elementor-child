<?php

add_action('wp_ajax_vsc_ajax_decrease_item_to_cart', 'vsc_ajax_decrease_item_to_cart_ajax_handler');
add_action('wp_ajax_nopriv_vsc_ajax_decrease_item_to_cart', 'vsc_ajax_decrease_item_to_cart_ajax_handler');

function vsc_ajax_decrease_item_to_cart_ajax_handler(){
    
    //echo wp_send_json(array('working'));

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $product = wc_get_product( $product_id );
    $old_quantity = floatval($_POST['old_quantity']);
    $error = false;

    if($old_quantity  < 0.5){
        $data = array(
            'error' => true,
            'error_msg' => 'You can not add below zero',
        );
        echo wp_send_json($data);
    }

    if(isset($_POST['vsc_product_note'])){
        $vsc_product_note = $_POST['vsc_product_note'];
    } else {
        $vsc_product_note = false;
    }


    $product_status = get_post_status($product_id);

    if ('publish' === $product_status) {

        $decreased_from_cart = false;
        // Check if it is a variable product or not
        if(isset($_POST['variation_per_kg_or_item'])){
            // For variable product
            // If the this true then it is price_per_kg
            // If false then it is price_per_item
            $variation_per_kg_or_item = json_decode($_POST['variation_per_kg_or_item']);
            $variations = $product->get_available_variations();
            $variation_id = false;

            foreach($variations as $variation){

                if($variation_per_kg_or_item){
                    // For price per kg
                    if( $variation['attributes']['attribute_pa_price-type'] == 'price-per-kg' ){
                        $variation_id = $variation['variation_id'];
                        $quantity = 0.5;
                    } else {
                        $oposite_variation_id = $variation['variation_id'];
                    }


                } else {
                    // For price per item
                    if( $variation['attributes']['attribute_pa_price-type'] == 'price-per-item' ){
                        $variation_id = $variation['variation_id'];
                        $quantity = 1;
                    } else {
                        $oposite_variation_id = $variation['variation_id'];
                    }
                }
                
            }

            // True if the variation has swtiched
            $is_variation_has_switched = vsc_check_variation_already_in_cart($oposite_variation_id);

            if($is_variation_has_switched){

                $oposite_item_count_in_cart = vsc_get_item_count_in_cart_by_variation_id($oposite_variation_id);
                
                vsc_remove_cart_item_by_product_id($product_id);

                // If the new item price per kg
                if($variation_per_kg_or_item){
                    $new_quantity = $oposite_item_count_in_cart - 0.5;
                } else {
                    $new_quantity = ceil($oposite_item_count_in_cart - 1);
                }

                $decreased_from_cart = WC()->cart->add_to_cart($product_id, $new_quantity, $variation_id);

            } else {

                $old_item_count = vsc_get_item_qty_by_product_id($product_id);
                // If the new item price per kg
                if($variation_per_kg_or_item){
                    $new_quantity = $old_item_count - 0.5;
                } else {
                    $new_quantity = $old_item_count - 1;
                }
                $decreased_from_cart = vsc_set_cart_item_new_quantity($product_id, $new_quantity);
            }

        // start else for simple item
        } else {

            //echo "Hello";
            //For simple product
            $new_quantity = $old_quantity - 1;
            $decreased_from_cart = vsc_set_cart_item_new_quantity($product_id, $new_quantity);
            
        }

        // if the cart item has decreased
        if($decreased_from_cart){

            if($vsc_product_note){
                $product->update_meta_data( 'vsc_product_note', sanitize_text_field( $vsc_product_note ) );
                $product->save();
            }

            $data = array(
                'success' => true,
                'current_count' => vsc_get_item_qty_by_product_id($product_id),
                'added_product_html' => vsc_get_product_added_cart_popup($product_id),
            );
            echo wp_send_json($data);
    
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
            'error_msg' => 'There is something wrong. The product not added to cart'
        );
        echo wp_send_json($data);
    }
    

    wp_die();

}