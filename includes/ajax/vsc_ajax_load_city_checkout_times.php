<?php

add_action('wp_ajax_vsc_load_city_checkout_times', 'vsc_load_city_checkout_times_ajax_handler');
add_action('wp_ajax_nopriv_vsc_load_city_checkout_times', 'vsc_load_city_checkout_times_ajax_handler');

function vsc_load_city_checkout_times_ajax_handler(){

    $selected_city = isset($_POST['selected_city']) ? $_POST['selected_city'] : '';

    if(!empty($selected_city)){

        $args = array(      
            'post_type'   => 'shipping_city',
            'name' => $selected_city,
            'posts_per_page' => 1,
            'post_status' => 'publish',
        );
        $wp_query = new WP_Query($args);


        if($wp_query->have_posts()){
            while($wp_query->have_posts()){
                $wp_query->the_post();

                $shipping_day_start_after = get_field( "shipping_day_start_after" );
                $shipping_days_available = get_field( "shipping_days_available" );
                $weekly_available_times = get_field( "weekly_available_times" );

                $after_4_days = strtotime("+ 4days");

    $days_letter_2 = date('j/m/Y', $after_2_days);


                echo $shipping_day_start_after;
                echo $shipping_days_available;



            }
        }    

    }


    wp_die();

}