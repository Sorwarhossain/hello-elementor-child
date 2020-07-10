<?php

add_action('wp_ajax_vsc_ajax_load_add_note_popup', 'vsc_ajax_load_add_note_popup_ajax_handler');
add_action('wp_ajax_nopriv_vsc_ajax_load_add_note_popup', 'vsc_ajax_load_add_note_popup_ajax_handler');

function vsc_ajax_load_add_note_popup_ajax_handler(){

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $product = wc_get_product( $product_id );
    $vsc_product_note =  $product->get_meta('vsc_product_note') ? $product->get_meta('vsc_product_note') : '';
    
    ?>

    <div class="white-popup vsc-add-note-popup">
        <div class="vsc-add-note-popup-inner">
            <h3>כתוב הערה למוצר זה</h3>
            <div class="vsc_note_product_details">
                <?php 
                if(has_post_thumbnail($product_id)) : 
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'thumbnail' );
                ?>
                    <div class="vsc_note_product_details_img">
                        <img src="<?php echo $image[0]; ?>" alt="">
                    </div>
                <?php endif; ?>
                <h4><?php echo $product->get_title(); ?></h4>
            </div>
            <div class="product_note_form">
                <textarea name="product_popup_note" id="product_popup_note" placeholder="הייתי רוצה ש..."><?php echo $vsc_product_note; ?></textarea>
                <a href="#" class="vsc_save_note" product_id="<?php echo $product_id; ?>">שמור הערה</a>
            </div>
        </div>
    </div>
    
<?php
    wp_die();

}