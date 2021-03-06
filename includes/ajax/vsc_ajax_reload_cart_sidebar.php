<?php

add_action('wp_ajax_vsc_ajax_reload_cart_sidebar', 'vsc_ajax_reload_cart_sidebar_handler');
add_action('wp_ajax_nopriv_vsc_ajax_reload_cart_sidebar', 'vsc_ajax_reload_cart_sidebar_handler');

function vsc_ajax_reload_cart_sidebar_handler(){

    
?>


    <?php do_action( 'woocommerce_before_cart_contents' ); ?>

<?php
foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

    $vsc_product = wc_get_product( $product_id );

    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
        ?>
        <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

            <td class="vsc-product-remove">
                <?php
                    echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        'woocommerce_cart_item_remove_link',
                        sprintf(
                            '<a href="#" class="remove" aria-label="%s" product_id="%s" >&times;</a>',
                            esc_html__( 'Remove this item', 'woocommerce' ),
                            esc_attr( $product_id ),
                            esc_attr( $_product->get_sku() )
                        ),
                        $cart_item_key
                    );
                ?>
            </td>

            <td class="product-thumbnail">
            <?php
            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

            if ( ! $product_permalink ) {
                echo $thumbnail; // PHPCS: XSS ok.
            } else {
                printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
            }
            ?>
            </td>

            <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                <?php
                echo '<a href="#">'. get_the_title($product_id) .'</a>';

                $vsc_product_sublabel = $_product->get_meta('vsc_product_sublabel') ? $_product->get_meta('vsc_product_sublabel') : '';
                if(!empty($vsc_product_sublabel)){
                    echo '<h5>'. $vsc_product_sublabel .'</h5>';
                }
                ?>
                <div class="vsc_edit_note">
                    <a href="#" class="vsc_edit_product_note" product_id="<?php echo $product_id; ?>"><i class="far fa-edit"></i></a>
                    <?php 
                    if(isset($cart_item['vsc_product_note_value'])){
                        echo '<span>'. $cart_item['vsc_product_note_value'] .'</span>';
                    }
                    ?>
                    
                </div>
            </td>

            <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                <?php
                    echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                ?>
            </td>

            <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
            <?php
            if ( $_product->is_sold_individually() ) {
                $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
            } else {
                $product_quantity = woocommerce_quantity_input(
                    array(
                        'input_name'   => "cart[{$cart_item_key}][qty]",
                        'input_value'  => $cart_item['quantity'],
                        'max_value'    => $_product->get_max_purchase_quantity(),
                        'min_value'    => '0',
                        'product_name' => $_product->get_name(),
                    ),
                    $_product,
                    false
                );
            }

            echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
            ?>



<?php  
$vsc_product_loop_cart_icons = vsc_get_product_loop_cart_icons($product_id);
echo $vsc_product_loop_cart_icons;
?>









            </td>

            <td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                <div class="vsc_total_price">
                    <?php
                        echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                    ?>
                </div>

                <div class="vsc_item_price">
                    <?php 
                    // check if simple product
                    // if($_product->is_type('simple')){
                    // 	echo $_product->get_price_html();
                    // } else {
                    // 	// else varialbe product
                    // }
                    echo $vsc_product->get_price_html();
                    ?>
                </div>
            </td>
        </tr>
        <?php
    }
}
?>

<?php do_action( 'woocommerce_cart_contents' ); ?>

<tr>
    <td colspan="6" class="actions">
        <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

        <?php do_action( 'woocommerce_cart_actions' ); ?>

        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
    </td>
</tr>

<?php do_action( 'woocommerce_after_cart_contents' ); ?>



    <?php
    wp_die();
}








add_action('wp_ajax_vsc_ajax_reload_cart_sidebar_total_price', 'vsc_ajax_reload_cart_sidebar_total_price_handler');
add_action('wp_ajax_nopriv_vsc_ajax_reload_cart_sidebar_total_price', 'vsc_ajax_reload_cart_sidebar_total_price_handler');

function vsc_ajax_reload_cart_sidebar_total_price_handler(){
    
    wc_cart_totals_order_total_html();

    wp_die();
}



add_action('wp_ajax_vsc_update_cart_count', 'vsc_update_cart_count_handler');
add_action('wp_ajax_nopriv_vsc_update_cart_count', 'vsc_update_cart_count_handler');

function vsc_update_cart_count_handler(){
    
    global $woocommerce;
    $vsc_cart_item_count = $woocommerce->cart->cart_contents_count;

    echo $vsc_cart_item_count;

    wp_die();
}

