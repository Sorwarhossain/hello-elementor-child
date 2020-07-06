<?php

function vsc_loadmore_ajax_handler(){

    //echo "Working";
    $looped_products = 0;
    $displayed_products = 0;
    $displayable_count = 10;
    $break_loop = false;
    $page = $_POST['page'];
    $skipable_products = ((int)$page - 1) * $displayable_count;
    $show_cat_title = false;


    $cat_args = array(
        'hide_empty' => true,
    );
    $product_categories = get_terms( 'product_cat', $cat_args );


    if($product_categories && !$break_loop){

        foreach($product_categories as $product_cat){
            // If the products are displayed then just break the loop.
            if($break_loop) continue;

            if($show_cat_title){
                echo '<li class="product vsc-product-item category-title" id="vsc_cat_'. $product_cat->slug .'"><h3 class="vsc_cat_title">'. $product_cat->name .'</h3></li>';
            }
            

            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => $product_cat->slug,
                    ),
                ),
            );

            $loop = new WP_Query( $args );
            if ( $loop->have_posts() && !$break_loop) {
                while ( $loop->have_posts() && !$break_loop) : $loop->the_post();

                    // The loop will not work until the above product has gone
                    $looped_products++;
                    if($looped_products <= $skipable_products) {
                        continue;
                    }
                    $show_cat_title = true;

                    $product = wc_get_product();
                    $product_id = $product->get_id();


                    $related_product_html = vsc_get_related_product_html($product_id);
                    $has_related_product = '';
                    if($related_product_html){
                        $has_related_product = 'has_related_product';
                    } else {
                        $related_product_html = '';
                    }
                    ?>

                    <li class="product vsc-product-item vsc-only-product <?php echo $has_related_product; ?>">
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
                    $displayed_products++;
                    if($displayed_products == $displayable_count) $break_loop = true;

                endwhile;
                wp_reset_postdata();
            }
            


        } // end of main category foreach

    } // End of if
    
    
	die; // here we exit the script and even no wp_reset_query() required!
}
 
 
 
add_action('wp_ajax_vsc_loadmore', 'vsc_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_vsc_loadmore', 'vsc_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}