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