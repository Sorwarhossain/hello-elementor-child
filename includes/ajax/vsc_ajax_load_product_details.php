<?php

add_action('wp_ajax_vsc_ajax_load_product_details', 'vsc_ajax_load_product_details_handler');
add_action('wp_ajax_nopriv_vsc_ajax_load_product_details', 'vsc_ajax_load_product_details_handler');

function vsc_ajax_load_product_details_handler(){


    $product_id = $_POST['product_id'];
    if(empty($product_id)){
        wp_die();
    }

    $product = wc_get_product( $product_id );
    $attachment_ids = $product->get_gallery_image_ids();

?>


    <div class="white-popup vsc-product-details-popup vsc-<?php echo $product->get_type(); ?>">
        <div class="vsc-product-details-popup-inner">
            <section id="vsc-product-details">
                <div class="vsc-product-details-container">
                    <div class="product-details-left">
                        <h3 class="product-name"><?php echo get_the_title($product_id); ?></h3>
                        <div class="product-price">
                            <?php echo $product->get_price_html(); ?>
                        </div>
                        
                        <?php
                        //include get_stylesheet_directory() . '/template-parts/add-to-cart.php';
                        //echo ;
                       // include(dirname(__FILE__) . '/custom_template.php'); 
                        //inclue 'template-parts/add-to-cart';
                        $vsc_product_loop_cart_icons = vsc_get_product_loop_cart_icons($product_id);
                        echo $vsc_product_loop_cart_icons;

                        $vsc_product_note = $product->get_meta('vsc_product_note') ? $product->get_meta('vsc_product_note') : '';
                        ?>

                        <div class="vsc_product_note">
                            <input type="text" placeholder="Product Note..." name="vsc_product_note" id="vsc_product_note" value="<?php echo $vsc_product_note; ?>">
                        </div>

                    </div>
                    <div class="product-details-right">

                        <div class="product_featured_images">
                        <?php 
                            if($attachment_ids){
                                echo vsc_get_product_details_gallery($attachment_ids);
                            } else {
                                if(has_post_thumbnail($product_id)){
                                    echo vsc_get_product_details_thumbnail($product_id);
                                }
                            }
                        ?>
                        </div>

                    </div>
                </div>
            </section>
            
            <section id="vsc_product_description">
                <h4 class="section-title"><span>כדאי לדעת</span></h4>
                <?php echo $product->get_description();; ?>
            </section>

            <?php

            $category_slug = vsc_get_product_category_slug_by_id($product_id);

            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'post__not_in' => array( $product_id ),
                'posts_per_page' => 8,
                'orderby' => 'rand',
            );

            if($category_slug){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => $category_slug,
                    ),
                );
            }

            $loop = new WP_Query($args);

            if($loop->have_posts()) :
            ?>

            <section id="vsc_related_products" style="display: none">
                <h4 class="related_products_title">חשבנו שיבוא לך גם</h4>
                <div class="vsc_related_products">
                    <div class="vsc_related_products_slider">
                        <?php
                        while($loop->have_posts()) :
                            $loop->the_post();
                        ?>
                            <div class="vsc_related_item">
<?php 

$product = wc_get_product();
$product_id = $product->get_id();
$post_thumbnail = get_the_post_thumbnail($product_id, 'shop_catalog');
$product_title = get_the_title();

$output .= '<li class="product vsc-product-item vsc-only-product">';

$vsc_product_loop_cart_icons = vsc_get_product_loop_cart_icons($product_id);

$output .= <<<EOT
    <div class="vsc-product-inner">
        <div class="vsc-product-thumb">
            <figure class="figure">
                {$post_thumbnail}
            </figure>
            <div class="vsc-product-thumb-hover">
                {$vsc_product_loop_cart_icons}
            </div>
        </div>
        <div class="vsc-product-content">
            <div class="vsc-product-price">
                {$product->get_price_html()}
            </div>
            <h3 class="title"><a href="#" data-id="{$product_id}">{$product_title}</a></h3>
        </div>
    </div>
EOT;

$output .= '</li>';
echo $output;
?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <p>המחיר הסופי ייקבע לאחר שקילת המוצרים. תמונות המוצר הן להמחשה בלבד וייתכנו אי התאמות בין הסימון המופיע באתר לסימון המופיע על גבי המוצר, ועל כן יש לקרוא את הסימון המופיע על גבי המוצר טרם השימוש בו. בגלישה ממכשיר סלולרי יש ללחוץ לחיצה ארוכה על התמונה ע"מ להגדיל אותה
</p>
            </section>
            <?php endif; ?>
        </div>
    </div>


    <?php

    wp_die();

}