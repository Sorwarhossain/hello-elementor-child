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

                $shipping_day_start_after = get_field( "shipping_day_start_after" ) + 1;
                $shipping_days_available = get_field( "shipping_days_available" );

                $weekly_available_times = get_field( "weekly_available_times" );

                if($shipping_day_start_after == 1){
                    $checkout_date = date('j-m-Y', strtotime("+ ". $shipping_day_start_after ."day"));
                } else {
                    $checkout_date = date('j-m-Y', strtotime("+ ". $shipping_day_start_after ."days"));
                }

                $output = '';

                for($i = 1; $i <= $shipping_days_available; $i++){
                    
                    $checkout_day_name = strtolower(vsc_get_formatted_dayname_by_date($checkout_date));

                    echo $checkout_date;
                    echo $checkout_day_name . ' ';

                    foreach($weekly_available_times as $dayname => $times){
                        // works if found the day name
                        if($dayname === $checkout_day_name){
                            // check if it is not weekend
                            if(!$times['weekend']){
                                
                                $output .= '<tr>';

                                    $output .= '<td><h4>'. ucfirst($times['label']) .'</h4><p>'. $checkout_date .'</p></td>';

                                    $output .= '<td>';
                                    foreach($times['available_times'] as $time){
                                        if(!empty($time['time_range'])){
                                            $output .= '<a href="#" class="vsc_selectable_date">'. $time['time_range'] .'</a>';
                                        }
                                    }
                                    $output .= '</td>';

                                $output .= '</tr>';

                                

                            } else {
                                // this is weekend
                                $i--;
                            }
                        }
                    }

                    // Update checkout date for next day
                    $checkout_date = date ('j-m-Y', strtotime('+1 day', strtotime($checkout_date)));
                }
                


                echo $output;



            }
        }    

    }


    wp_die();

}