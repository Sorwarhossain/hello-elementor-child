<?php

function vsc_loadmore_ajax_handler(){

    $cat_id = $_POST['cat_id'];
    $page = $_POST['page'];

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 20,
        'post_status' => 'publish',
        'paged' => $page,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $cat_id,
            ),
        ),
    );

    $loop = new WP_Query( $args );

    if ( $loop->have_posts() ) {
        while ( $loop->have_posts() ) : $loop->the_post();
            $product = wc_get_product();
            $product_id = $product->get_id();

            if(!vsc_get_item_qty_by_product_id($product_id) > 0){
                $vsc_already_in_cart = 'vsc_already_in_cart';
            } else {
                $vsc_already_in_cart = '';
            }

            $related_product_html = vsc_get_related_product_html($product_id);
            $has_related_product = '';
            if($related_product_html){
                $has_related_product = 'has_related_product';
            } else {
                $related_product_html = '';
            }
            ?>

                <li class="product vsc-product-item vsc-only-product <?php echo $has_related_product; ?> <?php echo $vsc_already_in_cart; ?>">
                    <?php echo $related_product_html; ?>
                    <div class="vsc-product-inner">
                        <div class="vsc-product-thumb">
                            <figure class="figure">
                                <?php the_post_thumbnail($product_id, 'shop_catalog'); ?>
                            </figure>
                            <div class="vsc-product-thumb-hover">
                            <?php
                                $vsc_product_loop_cart_icons = vsc_get_product_loop_cart_icons($product_id);
                                echo $vsc_product_loop_cart_icons;
                            ?>
                            </div>
                            <?php echo vsc_cart_added_items_count_by_product_id($product_id); ?>
                        </div>
                        <div class="vsc-product-content">
                            <div class="vsc-product-price">
                                <?php echo $product->get_price_html(); ?>
                            </div>
                            <h3 class="title"><a href="#" data-id="<?php echo $product_id; ?>"><?php the_title(); ?></a></h3>
                        </div>
                    </div>    
                </li>

        <?php
        endwhile;
        wp_reset_postdata();
    }

    
	die; // here we exit the script and even no wp_reset_query() required!
}
 
 
 
add_action('wp_ajax_vsc_loadmore', 'vsc_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_vsc_loadmore', 'vsc_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}