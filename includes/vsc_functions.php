<?php

function vsc_get_item_qty_by_product_id( $product_id ){

    foreach( WC()->cart->get_cart() as $cart_item ){
        if ( $product_id == $cart_item['product_id'] ){
            return $cart_item['quantity'];
            // break;
        }
    }
    return false;
}

function vsc_get_item_line_total_by_product_id( $product_id ){

    foreach( WC()->cart->get_cart() as $cart_item ){
        if ( $product_id == $cart_item['product_id'] ){
            return $cart_item['line_total'];
            // break;
        }
    }
    return false;
}


function vsc_check_variation_already_in_cart( $variation_id ){

    foreach( WC()->cart->get_cart() as $cart_item ){
        if ( $variation_id == $cart_item['variation_id'] ){
            return true;
            // break;
        }
    }
    return false;
}


function vsc_get_item_count_in_cart_by_variation_id( $variation_id ){

    foreach( WC()->cart->get_cart() as $cart_item ){
        if ( $variation_id == $cart_item['variation_id'] ){
            return $cart_item['quantity'];
            // break;
        }
    }
    return false;
}


function vsc_remove_cart_item_by_product_id( $product_id ){

    foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ){
        if ( $product_id == $cart_item['product_id'] ){
            return WC()->cart->remove_cart_item( $cart_item_key );
            // break;
        }
    }
    return false;
}


function vsc_set_cart_item_new_quantity($product_id, $new_quantity){

    foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $cart_product_id = $cart_item['product_id'];
        // Check for specific product IDs and change quantity
        if( $cart_product_id == $product_id && $cart_item['quantity'] != $new_quantity ){
            // Change quantity
            if(WC()->cart->set_quantity( $cart_item_key, $new_quantity, true )){
                return true;
            }
        }
    }

    return false;
}



function vsc_get_product_category_slug_by_id( $id ){
    $term = get_term_by('id', $id, 'product_cat', 'ARRAY_A');
    return $term['slug'];       
}



function vsc_get_formatted_dayname_by_date($date){
   $dayname_of_week = date('l', strtotime($date));

   return $dayname_of_week;
}



function vsc_get_product_unit_type_for_simple_product($product_id){
    $product = wc_get_product($product_id);
    if($product->is_type('simple')){
        return $product->get_meta('vsc_product_unit_label');
    }

    return יח׳;
}