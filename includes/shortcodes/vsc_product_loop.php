<?php

$product = wc_get_product();
$product_id = $product->get_id();
$post_thumbnail = get_the_post_thumbnail($product_id, 'shop_catalog');
$product_title = get_the_title();

$vsc_product_loop_cart_icons = vsc_get_product_loop_cart_icons($product_id);

$product_added_cart_count = vsc_get_item_qty_by_product_id($product_id);

$added_item_count_html = vsc_cart_added_items_count_by_product_id($product_id);


$related_product_html = vsc_get_related_product_html($product_id);
$has_related_product = '';
if($related_product_html){
    $has_related_product = 'has_related_product';
} else {
    $related_product_html = '';
}

$output .= '<li class="product vsc-product-item vsc-only-product '. $has_related_product .'">';

$output .= <<<EOT
    {$related_product_html}
    <div class="vsc-product-inner">
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
EOT;

$output .= '</li>';