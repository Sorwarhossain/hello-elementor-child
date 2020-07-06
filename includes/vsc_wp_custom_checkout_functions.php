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

    $after_2_days = strtotime("+ 2days");
    $after_3_days = strtotime("+ 3days");
    $after_4_days = strtotime("+ 4days");

    $days_letter_2 = date('j-m-Y', $after_2_days);
    $days_letter_3 = date('j-m-Y', $after_3_days);
    $days_letter_4 = date('j-m-Y', $after_4_days);


    echo '<div id="vsc_checkout_dates">';

    

        echo '<table id="vsc_checkout_dates_table">';
            echo '<tr>';

                echo '<td><h4>'. vsc_get_formatted_dayname_by_date($days_letter_2) .'</h4><p>'. $days_letter_2 .'</p></td>';
                echo '<td><button class="vsc_selectable_date">10pm - 8pm</button></td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td><h4>'. vsc_get_formatted_dayname_by_date($days_letter_3) .'</h4><p>'. $days_letter_3 .'</p></td>';
                echo '<td><button class="vsc_selectable_date">10pm - 8pm</button></td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td><h4>'. vsc_get_formatted_dayname_by_date($days_letter_4) .'</h4><p>'. $days_letter_4 .'</p></td>';
                echo '<td><button class="vsc_selectable_date">10pm - 8pm</button></td>';
            echo '</tr>';
        echo '</table>';

        echo '<a href="#" id="vsc_view_more_checkout_times">View more shipping times></a>';

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




