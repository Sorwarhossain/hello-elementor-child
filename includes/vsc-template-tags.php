<?php

function vsc_get_product_loop_cart_icons($product_id){

    $product_quantity = vsc_get_item_qty_by_product_id($product_id);
    $product = wc_get_product($product_id);

    $output = '<div class="product-add-to-cart">';

    $product_added_cart_count = vsc_get_item_qty_by_product_id($product_id);

    


    if($product_added_cart_count){
        $style1 = 'style="display: flex;"';
        $style2 = 'style="display: none;"';
    } else {
        $style1 = 'style="display: none;"';
        $style2 = 'style="display: inline-block;"';
    }

    $output .= '<div class="vsc_increase_decrease_items_popup" '. $style1 .'>
        <div class="vsc_increase_decrease_plus">
            <a href="#" class="vsc_increase_decrease_plus_button" data-id="'.  $product_id .'"></a>
        </div>
        <div class="vsc_increase_decrease_count">'. $product_added_cart_count .'</div>
        <div class="vsc_increase_decrease_minus">
            <a href="#" class="vsc_increase_decrease_minus_button" data-id="'. $product_id .'"></a>
        </div>
    </div>';
    
    $output .= '<button class="vsc_add_to_cart_on_popup" data-id="'. $product_id .'" '. $style2 .' >הוספה לעגלה</button>';

    $output .= '<div class="vsc_unit_type">';

    if($product->is_type('variable')){

        $price_per_item = false;
        $price_per_kg = false;

        $variations = $product->get_available_variations();


        if( !empty($variations) ){

            foreach($variations as $variation){

                if( isset($variation['attributes']['attribute_pa_price-type']) ){


                    if($variation['attributes']['attribute_pa_price-type'] == 'price-per-item' ) {
                        $price_per_item = $variation['display_price'];
                    } 

                    if( $variation['attributes']['attribute_pa_price-type'] == 'price-per-kg' ) {
                        $price_per_kg = $variation['display_price'];
                    }
                }
            }

            // Get the unit type on the cart
            $vsc_added_item_unit_type = '';
            if($product_added_cart_count){
                $vsc_added_item_unit_type = vsc_get_unit_type_of_added_item_in_cart($product_id);
            }

            if($price_per_item && $price_per_kg){
                $output .= get_vsc_unit_switcher_html($price_per_kg, $vsc_added_item_unit_type);
            }

        }
        
    } else {
        // If simple product
        $unit_type = $product->get_meta( 'vsc_product_unit_label' ) ? $product->get_meta( 'vsc_product_unit_label' ) : 'יח׳';
        $output .= $unit_type;
        
    }
    $output .= '</div>';

    $output .= '</div>';

    return $output;

}



function get_vsc_unit_switcher_html($price_per_kg, $vsc_added_item_unit_type = ''){

    $output = '<label class="switch">';

        if(!empty($vsc_added_item_unit_type) && $vsc_added_item_unit_type == 'price-per-item'){
            $checked = '';
        } else {
            $checked = 'checked="checked"';
        }
        

        $output .= '<input type="checkbox" id="product_unit_switch" '. $checked .'>';
        $output .= '
            <div class="slider round">
                <span class="off">יח׳</span>
                <span class="on">ק״ג</span>
            </div>';
    $output .= '</label>';

    return $output;
}




function vsc_get_product_added_cart_popup($product_id, $vsc_product_unit_type){

    $product_thumbnail = get_the_post_thumbnail($product_id, 'thumbnail');
    $product_name = get_the_title($product_id);
    $product_added_cart_count = vsc_get_item_qty_by_product_id($product_id);
    $product_line_total = wc_price(vsc_get_item_line_total_by_product_id($product_id));

    $product_added = <<<HTML
        <div class="elementor-menu-cart__product">
            <div class="elementor-menu-cart__product-image product-thumbnail">
                {$product_thumbnail}
            </div>
            <div class="elementor-menu-cart__product-name product-name" data-title="Product">
                <a href="">{$product_name}</a>			
            </div>
            <div class="elementor-menu-cart__cart_quantity cart_quantity">
                <div class="vsc_cart_plus_minus_icon_top">
                    <h4 class="product_number">{$product_added_cart_count}</h4>
                    <p>{$vsc_product_unit_type}</p>
                </div>
            </div>
            <div class="elementor-menu-cart__product-price product-price" data-title="Price">
                {$product_line_total}
            </div>
        </div>
HTML;

return $product_added;

}



function vsc_get_product_details_gallery($attachment_ids){

    if(empty($attachment_ids)){
        return false;
    }

    $output = '<div class="featured_image_gallery"><div class="featured_image_gallery_wrapper">';

    foreach($attachment_ids as $attachment_id){
        $image_attrs = wp_get_attachment_image_src($attachment_id, 'full');
        $output .= '<div class="featured_image_item"><img src="'. $image_attrs[0] .'" alt=""></div>';
    }
        
    $output .= ' </div></div>';

    return $output;

}




function vsc_get_product_details_thumbnail($product_id){

    if(empty($product_id)){
        return false;
    }
    
    $output = '<div class="featured_image">' . get_the_post_thumbnail($product_id) . '</div>';
                            
    return $output;
                        
}


                                            
function vsc_cart_added_items_count_by_product_id($product_id){
    $add_html = '';

    $product_added_cart_count = vsc_get_item_qty_by_product_id($product_id);

    if($product_added_cart_count > 0){
        $add_html = '
        <div class="vsc_item_added_cart" data-count-p-id="'. $product_id .'">'. $product_added_cart_count .'</div>';
    }

    return $add_html;
}


function vsc_get_related_product_html($product_id){

    $product = wc_get_product($product_id);
    $product_related_id = $product->get_meta('vsc_related_product_id');
    if(!$product_related_id){
        return false;
    }

    $product_id = absint($product_related_id);
    $product = wc_get_product($product_id);
    
    $post_thumbnail = get_the_post_thumbnail($product_id, 'shop_catalog');
    $product_title = get_the_title($product_id);
    $vsc_product_loop_cart_icons = vsc_get_product_loop_cart_icons($product_id);
    $added_item_count_html = vsc_cart_added_items_count_by_product_id($product_id);



    $related_product_html = <<<RELATED_HTML
    <div class="product vsc-product-item vsc-only-product vsc-related_product">
        <div class="vsc-product-inner">
            <h3 class="related_title">You might also like</h3>
            <div class="vsc-product-thumb">
                <figure class="figure">
                    {$post_thumbnail}
                </figure>
                <div class="vsc-product-thumb-hover">
                    {$vsc_product_loop_cart_icons}
                </div>
                {$added_item_count_html}
            </div>
            <div class="vsc-product-content">
                <div class="vsc-product-price">
                    {$product->get_price_html()}
                </div>
                <h3 class="title"><a href="#" data-id="{$product_id}">{$product_title}</a></h3>
            </div>
        </div>
    </div>
RELATED_HTML;

    return $related_product_html;
}

