<?php

// Including vsc_functions.php file
require_once 'includes/vsc_functions.php';

// Include register post type
require_once 'includes/vsc_custom_post_types.php';

// Including Shortcode Files
require_once 'includes/shortcodes/vsc_products_shortcode.php';
require_once 'includes/shortcodes/vsc_list_categories.php';
require_once 'includes/shortcodes/vsc_product_search_form_shortcode.php';

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
require_once 'includes/ajax/vsc_ajax_load_add_note_popup.php';
require_once 'includes/ajax/vsc_save_product_note_on_popup.php';
require_once 'includes/ajax/vsc_ajax_reload_cart_sidebar.php';
require_once 'includes/ajax/vsc_ajax_remove_product_from_cart.php';






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

    global $woocommerce;
    $vsc_cart_item_count = $woocommerce->cart->cart_contents_count;

    wp_localize_script( 'child-scripts', 'vsc_loadmore', array(
        'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
        'vsc_home_url' => site_url(),
        'vsc_cart_item_count' => $vsc_cart_item_count,
    ));

    wp_enqueue_script('child-autocomplete', get_stylesheet_directory_uri() . '/assets/js/child-autocomplete.js', array(), false, true);


    $shipping_cities = get_posts( array(
        'post_type'   => 'shipping_city',
        'numberposts' => -1,
    ) );
    $shipping_city_names = array();
    if(!empty($shipping_cities)){
        foreach($shipping_cities as $city){
            $shipping_city_names[] = $city->post_title;
        }
    }

    $products = get_posts( array(
        'post_type'   => 'product',
        'numberposts' => -1,
    ) );

    //echo var_dump($products);
    $products_array = array();
    if(!empty($products)){
        foreach($products as $product){

            if(has_post_thumbnail( $product->ID )){
                $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $product->ID ), 'thumbnail' );
                $products_array[] = array(
                    'id' => $product->ID,
                    'name' => $product->post_title,
                    'thumb_url' => $thumbnail[0],
                );
            } else {
                $products_array[] = array(
                    'id' => $product->ID,
                    'name' => $product->post_title,
                    'thumb_url' => '',
                );
            }
            
        }
    }

    wp_localize_script( 'child-autocomplete', 'vsc_autocomplete', array(
		'shipping_city_names' => $shipping_city_names, // WordPress AJAX
		'product_names' => $products_array, // WordPress AJAX
    ));

    
    

    // load if checkout page
    if(is_checkout()){
        wp_enqueue_script('checkout-scripts', get_stylesheet_directory_uri() . '/assets/js/checkout-scripts.js', array(), false, true);
        wp_localize_script( 'checkout-scripts', 'vsc_checkout_data', array(
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

function vsc_redirect_to_home() {
    if(!is_admin() && is_product()) {
      wp_redirect(home_url());
      exit();
    }
}
add_action('template_redirect', 'vsc_redirect_to_home');
// Redirect to checkout page if anyone try to visit cart page
function vsc_redirect_to_checkout() {
    if(!is_admin() && is_cart()) {
        global $woocommerce;
        $cw_redirect_url_checkout = $woocommerce->cart->get_checkout_url();
        wp_redirect($cw_redirect_url_checkout);
        exit();
    }
}
add_action('template_redirect', 'vsc_redirect_to_home');







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




add_filter( 'woocommerce_get_price_html', 'vsc_custom_price_html', 9999999, 2 );
function vsc_custom_price_html( $price, $product ){

    // Simple product with sale price
    if(! $product->is_type('variable') && $product->is_on_sale() ){

        $vsc_product_unit_label = $product->get_meta('vsc_product_unit_label');

        $price = str_replace( '<ins>', '<ins><span class="vsc_price_icon">' . $vsc_product_unit_label . ' / </span>', $price );
        
        return $price;
    }

    // Simpple product
    if(!$product->is_type('variable') && !$product->is_on_sale() ){

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

add_filter( 'nav_menu_link_attributes', 'vsc_custom_main_menu_atts', 10, 3 );
function vsc_custom_main_menu_atts( $atts, $item, $args ){
    $atts['cat_id'] = $item->object_id;
    return $atts;
}


// Remove extenal and group product type
add_filter( 'product_type_selector', 'remove_product_types' );
function remove_product_types( $types ){
    unset( $types['grouped'] );
    unset( $types['external'] );

    return $types;
}



// echo var_dump(get_option('test'));