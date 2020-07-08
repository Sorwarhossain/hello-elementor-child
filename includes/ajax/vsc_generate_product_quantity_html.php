<?php

add_action('wp_ajax_vsc_generate_product_quantity_html', 'vsc_ajax_vsc_generate_product_quantity_html_handler');
add_action('wp_ajax_nopriv_vsc_generate_product_quantity_html', 'vsc_ajax_vsc_generate_product_quantity_html_handler');

function vsc_ajax_vsc_generate_product_quantity_html_handler(){


    $product_id = $_POST['product_id'];
    if(empty($product_id)){
        wp_die();
    }
    $product = wc_get_product( $product_id );
?>


<div class="cart-product-add-to-cart">
    <div class="product-add-to-cart">
        <?php 
        $product_added_cart_count = vsc_get_item_qty_by_product_id($product_id);
        if($product_added_cart_count){
            $style1 = 'style="display: flex;"';
            $style2 = 'style="display: none;"';
        } else {
            $style1 = 'style="display: none;"';
            $style2 = 'style="display: inline-block;"';
        }


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

                if($price_per_item && $price_per_kg){
                    echo get_vsc_unit_switcher_html($price_per_kg);
                }

            }
            
        }
        ?>
        

        <div class="vsc_increase_decrease_items_popup" <?php echo $style1; ?>>
            <div class="vsc_increase_decrease_plus">
                <a href="#" class="vsc_increase_decrease_plus_button" data-id="<?php echo $product_id; ?>"></a>
            </div>
            <div class="vsc_increase_decrease_count"><?php echo $product_added_cart_count; ?></div>
            <div class="vsc_increase_decrease_minus">
                <a href="#" class="vsc_increase_decrease_minus_button" data-id="<?php echo $product_id; ?>"></a>
            </div>
        </div>
        
        <button class="vsc_add_to_cart_on_popup" data-id="<?php echo $product_id; ?>" <?php echo $style2; ?>>הוספה לעגלה</button>

    </div>
</div>




    <?php

    wp_die();

}