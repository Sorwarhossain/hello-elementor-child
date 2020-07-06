<?php

function vsc_load_catup_products_ajax_handler(){

    $cat_id = $_POST['cat_id'];
    $displayable_count = 10;
    $loaded_posts = $_POST['loaded_posts'];
    $looped_products = 0;


    $cat_args = array(
        'hide_empty' => true,
    );


    $product_cat_order = 0;

    $product_categories = get_terms( 'product_cat', $cat_args );
    $new_product_categories = array();

    if($product_categories){
        foreach($product_categories as $product_cat){
            $new_product_categories[] = $product_cat;
            if($product_cat->term_id == $cat_id){
                break;
            }
        }
    }

    if($new_product_categories){

        $numItems = count($new_product_categories);
        $i = 0;
        foreach($new_product_categories as $product_cat){

            $itemNumOfCategory = 0;

            // If the last category then just add 10 products maximum
            $posts_per_page = -1;
            if(++$i === $numItems) {
                $posts_per_page = $displayable_count;
            }

            $args = array(
                'post_type' => 'product',
                'posts_per_page' => $posts_per_page,
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
            if ( $loop->have_posts() ) {
                while ( $loop->have_posts() && !$break_loop) : $loop->the_post();

                $itemNumOfCategory++;

                $looped_products++;
                if($looped_products <= $loaded_posts){
                    continue;
                }

                
                if($itemNumOfCategory == 1){
                    echo '<li class="product vsc-product-item category-title" id="vsc_cat_'. $product_cat->slug .'"><h3 class="vsc_cat_title">'. $product_cat->name .'</h3></li>';
                }


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

                endwhile;
            }


        } // end of category foreach


    }    
    
	die; // here we exit the script and even no wp_reset_query() required!
}
 
 
 
add_action('wp_ajax_vsc_load_catup_products', 'vsc_load_catup_products_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_vsc_load_catup_products', 'vsc_load_catup_products_ajax_handler'); // wp_ajax_nopriv_{action}