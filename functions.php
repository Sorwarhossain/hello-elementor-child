<?php

// Including vsc_functions.php file
require_once 'includes/vsc_functions.php';

// Include register post type
require_once 'includes/vsc_custom_post_types.php';

// Including Shortcode Files
require_once 'includes/shortcodes/vsc_products_shortcode.php';
require_once 'includes/shortcodes/vsc_list_categories.php';

// Including Ajax Files
require_once 'includes/ajax/vsc_loadmore_ajax.php';
require_once 'includes/ajax/vsc_load_catup_products.php';
require_once 'includes/ajax/vsc_ajax_add_to_cart.php';
require_once 'includes/ajax/vsc_ajax_increase_item_to_cart.php';
require_once 'includes/ajax/vsc_ajax_decrease_item_to_cart.php';
require_once 'includes/ajax/vsc_ajax_load_product_details.php';
require_once 'includes/ajax/vsc_ajax_add_to_cart_product_details.php';
require_once 'includes/ajax/vsc_ajax_find_shipping_city.php';
require_once 'includes/ajax/vsc_ajax_load_city_checkout_times.php';
require_once 'includes/ajax/vsc_generate_product_quantity_html.php';
require_once 'includes/ajax/vsc_remove_items_from_cart.php';



// Require Template Tags
require_once 'includes/vsc-template-tags.php';

// Require WooCommerce Custom Fields
require_once 'includes/vsc_wc_custom_meta_fields.php';

// Customize woocommerce checkout fields
require_once 'includes/vsc_wp_custom_checkout_functions.php';



// Add theme css and js files
function vsc_child_enqueue() {
    
    wp_enqueue_style( 'magnific-css', get_stylesheet_directory_uri() . '/assets/css/magnific-popup.css' );
    wp_enqueue_style( 'magnific-css', get_stylesheet_directory_uri() . '/assets/css/slick.css' );

    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ), '1.0.0');

    wp_enqueue_script('child-navpoints', get_stylesheet_directory_uri() . '/assets/js/jquery.navpoints.js', array(), false, true);
    wp_enqueue_script('magnific-js', get_stylesheet_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array(), false, true);
    wp_enqueue_script('slick-js', get_stylesheet_directory_uri() . '/assets/js/slick.min.js', array(), false, true);
    wp_enqueue_script('child-scripts', get_stylesheet_directory_uri() . '/assets/js/child-scripts.js', array(), false, true);

    
    
    wp_localize_script( 'child-scripts', 'vsc_loadmore', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
    ));
    

    // load if checkout page
    if(is_checkout()){
        wp_enqueue_script('checkout-scripts', get_stylesheet_directory_uri() . '/assets/js/checkout-scripts.js', array(), false, true);
        wp_localize_script( 'checkout-scripts', 'vsc_loadmore', array(
            'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
        ));
    }
    
        
}
add_action( 'wp_enqueue_scripts', 'vsc_child_enqueue', PHP_INT_MAX);

// Support SVG file upload
function add_file_types_to_uploads($file_types){
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes );
    return $file_types;
}
add_filter('upload_mimes', 'add_file_types_to_uploads');







// Disable single product page
// it will redirect to homepage

// function vsc_redirect_to_home() {
//     if(!is_admin() && is_product()) {
//       wp_redirect(home_url());
//       exit();
//     }
//   }
//   add_action('template_redirect', 'vsc_redirect_to_home');


// Redirect to checkout page if anyone try to visit cart page
function vsc_redirect_to_home() {
    if(!is_admin() && is_cart()) {
        global $woocommerce;
        $cw_redirect_url_checkout = $woocommerce->cart->get_checkout_url();
        wp_redirect($cw_redirect_url_checkout);
        exit();
    }
  }
  add_action('template_redirect', 'vsc_redirect_to_home');

add_filter('add_to_cart_redirect', 'vsc_redirect_add_to_cart');
function vsc_redirect_add_to_cart() {
    global $woocommerce;
    $cw_redirect_url_checkout = $woocommerce->cart->get_checkout_url();
    return $cw_redirect_url_checkout;
}






// Add min value to the quantity field (default = 1)
add_filter('woocommerce_quantity_input_min', 'min_decimal');
function min_decimal($val) {
    return 0.5;
}
 
// Add step value to the quantity field (default = 1)
add_filter('woocommerce_quantity_input_step', 'nsk_allow_decimal');
function nsk_allow_decimal($val) {
    return 0.5;
}
 
// Removes the WooCommerce filter, that is validating the quantity to be an int
remove_filter('woocommerce_stock_amount', 'intval');
 
// Add a filter, that validates the quantity to be a float
add_filter('woocommerce_stock_amount', 'floatval');




add_filter( 'woocommerce_get_price_html', 'vsc_custom_price_html', 100, 2 );
function vsc_custom_price_html( $price, $product ){

    // Simple product with sale price
    if($product->is_type('variable') != 'variable'&& $product->is_on_sale() ){
        $vsc_product_unit_label = $product->get_meta('vsc_product_unit_label');

        $price = str_replace( '<ins>', '<ins><span class="vsc_price_icon">' . $vsc_product_unit_label . ' / </span>', $price );
        
        return $price;
    }

    // Simpple product
    if($product->is_type('variable') != 'variable'&& !$product->is_on_sale() ){
        $vsc_product_unit_label = $product->get_meta('vsc_product_unit_label');

        $price = str_replace( '<span class="woocommerce-Price-amount amount">', '<span class="woocommerce-Price-amount amount"><span class="vsc_price_icon">' . $vsc_product_unit_label . ' / </span>', $price );

        return $price;
    }


    if($product->is_type('variable')){

        $p_price = 0;

        $variations = $product->get_available_variations();

        foreach($variations as $variation){
            if( $variation['attributes']['attribute_pa_price-type'] == 'price-per-kg' ){
                $p_price = $variation['display_price'];
            }
        }

        $price = '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₪</span>' . number_format($p_price, 2) . '<span class="vsc_price_icon"> / ק״ג</span></span>';

        return $price;
    }
    

    return $price;
}


//echo var_dump(get_option('test'));