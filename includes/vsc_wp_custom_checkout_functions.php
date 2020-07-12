<?php

add_filter('woocommerce_billing_fields', 'vsc_customized_checkout_billing_fields', 99);
function vsc_customized_checkout_billing_fields($address_fields){

    // Remove items
    unset($address_fields['billing_company']);
    unset($address_fields['billing_country']);
    unset($address_fields['billing_postcode']);

    $address_fields['billing_email']['priority'] = 21;
    $address_fields['billing_phone']['priority'] = 22;
    $address_fields['billing_phone']['class'] = array('form-row-first');
    $address_fields['billing_extra_phone']['priority'] = 23;

    $address_fields['billing_city']['priority'] = 41;
    $address_fields['billing_city']['type'] = 'select';


    global $wpdb;
    $post_type = 'shipping_city';
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT post_title as title, post_name as slug FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $post_type ), ARRAY_A );

    $cities_array = array('' => 'Select a city from the list',);
    if(!empty($results)){
        foreach($results as $result){
            $slug = $result['slug'];
            $cities_array[$slug] = $result['title'];
        }
    }
    

    $address_fields['billing_city']['options'] = $cities_array;





    $address_fields['billing_city']['label'] = 'City';

    $address_fields['billing_address_1']['label'] = 'Street';
    $address_fields['billing_address_1']['placeholder'] = '';
    $address_fields['billing_address_1']['class'] = array('form-row-wide');
    

    $address_fields['billing_address_2']['label'] = 'House number';
    $address_fields['billing_address_2']['placeholder'] = '';
    $address_fields['billing_address_2']['required'] = true;
    $address_fields['billing_address_2']['class'] = array('form-row-first');
    

    


    // /update_option('test', $address_fields);
    

    return $address_fields;
}

//echo var_dump(get_option('test'));

add_action( 'woocommerce_form_field_text','reigel_custom_heading', 999, 2 );
function reigel_custom_heading( $field, $key ){
    // will only execute if the field is billing_company and we are on the checkout page...
    if ( is_checkout() && ( $key == 'billing_extra_phone') ) {
        $field .= '<div class="clear"></div><div class="vsc_shipping_address_wrapper"><h3 class="vsc_custom_checkout_title">' . __('Shipping Address') . '</h3></div>';
    }
    return $field;
}




add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {

    $fields['billing']['billing_extra_phone'] = array(
        'label'     => __('Additional Phone', 'woocommerce'),
        'required'  => false,
        'class'     => array('form-row-last'),
        'clear'     => true,
        'order'     => 23,
    );

    $fields['billing']['billing_apartment'] = array(
        'label'     => __('Apartment', 'woocommerce'),
        'required'  => false,
        'class'     => array('form-row-last'),
        'clear'     => true,
        'order'     => 62,
    );

    $fields['billing']['billing_floor'] = array(
        'label'     => __('Floor', 'woocommerce'),
        'required'  => false,
        'class'     => array('form-row-first'),
        'clear'     => true,
        'order'     => 64,
    );

    $fields['billing']['billing_entry_code'] = array(
        'label'     => __('Entry Code', 'woocommerce'),
        'required'  => false,
        'class'     => array('form-row-last'),
        'clear'     => true,
        'order'     => 66,
    );

    return $fields;
}





/** =================================================
* Add addtional fields to second checkout column
* ================================================ */
add_action('woocommerce_before_order_notes', 'vsc_add_custom_additional_checkout_fields');
function vsc_add_custom_additional_checkout_fields($checkout){

    $days_letter_2 = date('j-m-Y', strtotime("+ 2days"));
    $days_letter_3 = date('j-m-Y', strtotime("+ 3days"));
    $days_letter_4 = date('j-m-Y', strtotime("+ 4days"));


    echo '<div id="vsc_checkout_dates">';

        echo '<table id="vsc_checkout_dates_table">';
            echo '<tr>';
                echo '<td><h4>'. vsc_get_formatted_dayname_by_date($days_letter_2) .'</h4><p>'. $days_letter_2 .'</p></td>';
                echo '<td><a href="#" class="vsc_selectable_date">10pm - 8pm</a></td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td><h4>'. vsc_get_formatted_dayname_by_date($days_letter_3) .'</h4><p>'. $days_letter_3 .'</p></td>';
                echo '<td><a href="#" class="vsc_selectable_date">10pm - 8pm</a></td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td><h4>'. vsc_get_formatted_dayname_by_date($days_letter_4) .'</h4><p>'. $days_letter_4 .'</p></td>';
                echo '<td><a href="#" class="vsc_selectable_date">10pm - 8pm</a></td>';
            echo '</tr>';
        echo '</table>';

        woocommerce_form_field(
            'vsc_checkout_delivery_date', 
            array(
                'type' => 'text',
                'label' => __('Product Delivery Date'),
                'class' => array(
                    'form-row-wide'
                ),
                'required' => true,
            ),
            $checkout->get_value('vsc_checkout_delivery_date')
        );

        woocommerce_form_field(
            'vsc_checkout_delivery_time', 
            array(
                'type' => 'text',
                'label' => __('Product Delivery Time'),
                'class' => array(
                    'form-row-wide'
                ),
                'required' => true,
            ),
            $checkout->get_value('vsc_checkout_delivery_time')
        );

    echo '</div>';

    woocommerce_form_field(
        'vsc_want_alternative_prducts', 
        array(
            'type' => 'checkbox',
            'class' => array(
                'form-row-wide'
            ),
            'label' => __('I want alternative products'),
            //'placeholder' => __('New Custom Field'),
        ),
        $checkout->get_value('vsc_want_alternative_prducts')
    );

    woocommerce_form_field(
        'vsc_shipping_without_plastic_bag', 
        array(
            'type' => 'checkbox',
            'class' => array(
                'form-row-wide'
            ),
            'label' => __('Shipping without plastic bags'),
            //'placeholder' => __('New Custom Field'),
        ),
        $checkout->get_value('vsc_shipping_without_plastic_bag')
    );



    include 'vsc_checkout_general_comment.php';
    


    
    //Shipping without plastic bags

}




/**
 * Process the checkout
 */
add_action('woocommerce_checkout_process', 'vsc_my_custom_checkout_field_process');

function vsc_my_custom_checkout_field_process() {
    // Check if set, if its not set add an error.
    if ( ! $_POST['vsc_checkout_delivery_date'] )
        wc_add_notice( __( 'Please select a delivery date.' ), 'error' );

    if ( ! $_POST['vsc_checkout_delivery_time'] )
        wc_add_notice( __( 'Please select a delivery time.' ), 'error' );

    if ( ! $_POST['vsc_payment_terms_services'] )
        wc_add_notice( __( 'Please accept the terms and services.' ), 'error' );
}



/**
 * Update the order meta with field value
 */
add_action( 'woocommerce_checkout_update_order_meta', 'vsc_my_custom_checkout_field_update_order_meta', 999 );

function vsc_my_custom_checkout_field_update_order_meta( $order_id ) {


    if ( ! empty( $_POST['vsc_checkout_delivery_date'] ) ) {
        update_post_meta( $order_id, 'vsc_checkout_delivery_date', sanitize_text_field( $_POST['vsc_checkout_delivery_date'] ) );
    }

    if ( ! empty( $_POST['vsc_checkout_delivery_time'] ) ) {
        update_post_meta( $order_id, 'vsc_checkout_delivery_time', sanitize_text_field( $_POST['vsc_checkout_delivery_time'] ) );
    }

    if ( ! empty( $_POST['vsc_want_alternative_prducts'] ) ) {

        if($_POST['vsc_want_alternative_prducts'] == 1){
            $vsc_want_alternative_prducts = 'Yes';
        } else {
            $vsc_want_alternative_prducts = 'No';
        }
        update_post_meta( $order_id, 'vsc_want_alternative_prducts', sanitize_text_field( $vsc_want_alternative_prducts ) );

    }

    if ( ! empty( $_POST['vsc_shipping_without_plastic_bag'] ) ) {
        if($_POST['vsc_shipping_without_plastic_bag'] == 1){
            $vsc_shipping_without_plastic_bag = 'Yes';
        } else {
            $vsc_shipping_without_plastic_bag = 'No';
        }
        update_post_meta( $order_id, 'vsc_shipping_without_plastic_bag', sanitize_text_field( $vsc_shipping_without_plastic_bag ) );

    }

    if ( ! empty( $_POST['vsc_shipping_general_comment'] ) ) {
        update_post_meta( $order_id, 'vsc_shipping_general_comment', sanitize_text_field( $_POST['vsc_shipping_general_comment'] ) );
    }

    if ( ! empty( $_POST['vsc_shipping_note_to_messanger'] ) ) {
        update_post_meta( $order_id, 'vsc_shipping_note_to_messanger', sanitize_text_field( $_POST['vsc_shipping_note_to_messanger'] ) );
    }

    if ( ! empty( $_POST['vsc_payment_terms_services'] ) ) {

        if($_POST['vsc_payment_terms_services'] == 1){
            $vsc_payment_terms_services = 'Yes';
        } else {
            $vsc_payment_terms_services = 'No';
        }
        update_post_meta( $order_id, 'vsc_payment_terms_services', sanitize_text_field( $vsc_payment_terms_services ) );

    }

    if ( ! empty( $_POST['vsc_payment_newsletter_subscribe'] ) ) {

        if($_POST['vsc_payment_newsletter_subscribe'] == 1){
            $vsc_payment_newsletter_subscribe = 'Yes';
        } else {
            $vsc_payment_newsletter_subscribe = 'No';
        }
        update_post_meta( $order_id, 'vsc_payment_newsletter_subscribe', sanitize_text_field( $vsc_payment_newsletter_subscribe ) );

    }
    

}





/**
 * Display field value on the order edit page
 */
 
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'vsc_my_custom_checkout_field_display_admin_order_meta', 10, 1 );
function vsc_my_custom_checkout_field_display_admin_order_meta($order){

    echo var_dump(get_post_meta($order->get_id()));

    echo '<p><strong>'.__('Additional Phone').':</strong> ' . get_post_meta( $order->get_id(), '_billing_extra_phone', true ) . '</p>';

    echo '<p><strong>'.__('Appartment').':</strong> ' . get_post_meta( $order->get_id(), '_billing_apartment', true ) . '</p>';

    echo '<p><strong>'.__('Floor').':</strong> ' . get_post_meta( $order->get_id(), '_billing_floor', true ) . '</p>';

    echo '<p><strong>'.__('Entry Code').':</strong> ' . get_post_meta( $order->get_id(), '_billing_entry_code', true ) . '</p>';

    echo '<p><strong>'.__('Delivery Date').':</strong> ' . get_post_meta( $order->get_id(), 'vsc_checkout_delivery_date', true ) . '</p>';

    echo '<p><strong>'.__('Delivery Time').':</strong> ' . get_post_meta( $order->get_id(), 'vsc_checkout_delivery_time', true ) . '</p>';


    echo '<p><strong>'.__('Want Alternate Products').':</strong> ' . get_post_meta( $order->get_id(), 'vsc_want_alternative_prducts', true ) . '</p>';

    echo '<p><strong>'.__('Shipping Without Plastic Bag').':</strong> ' . get_post_meta( $order->get_id(), 'vsc_shipping_without_plastic_bag', true ) . '</p>';

    echo '<p><strong>'.__('General Comment').':</strong> ' . get_post_meta( $order->get_id(), 'vsc_shipping_general_comment', true ) . '</p>';

    echo '<p><strong>'.__('Note to messanger').':</strong> ' . get_post_meta( $order->get_id(), 'vsc_shipping_note_to_messanger', true ) . '</p>';

    echo '<p><strong>'.__('Terms and Services').':</strong> ' . get_post_meta( $order->get_id(), 'vsc_payment_terms_services', true ) . '</p>';

    echo '<p><strong>'.__('Newsletter Subscribe').':</strong> ' . get_post_meta( $order->get_id(), 'vsc_payment_newsletter_subscribe', true ) . '</p>';
   

}





add_filter('woocommerce_email_order_meta_keys', 'vsc_my_woocommerce_email_order_meta_keys');
function vsc_my_woocommerce_email_order_meta_keys( $keys ) {

    $keys['Extra Phone'] = '_billing_extra_phone';
    $keys['Extra Phone'] = '_billing_apartment';
    $keys['Extra Phone'] = '_billing_floor';
    $keys['Extra Phone'] = '_billing_entry_code';

    $keys['Extra Phone'] = 'vsc_checkout_delivery_date';
    $keys['Extra Phone'] = 'vsc_checkout_delivery_time';
    $keys['Extra Phone'] = 'vsc_want_alternative_prducts';
    $keys['Extra Phone'] = 'vsc_shipping_without_plastic_bag';
    $keys['Extra Phone'] = 'vsc_shipping_general_comment';
    $keys['Extra Phone'] = 'vsc_shipping_note_to_messanger';

    $keys['Extra Phone'] = 'vsc_payment_terms_services';
    $keys['Extra Phone'] = 'vsc_payment_newsletter_subscribe';


    return $keys;

} 