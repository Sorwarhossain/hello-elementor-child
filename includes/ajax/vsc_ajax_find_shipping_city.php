<?php

add_action('wp_ajax_vsc_find_shipping_city', 'vsc_ajax_vsc_find_shipping_city_ajax_handler');
add_action('wp_ajax_nopriv_vsc_find_shipping_city', 'vsc_ajax_vsc_find_shipping_city_ajax_handler');

function vsc_ajax_vsc_find_shipping_city_ajax_handler(){

    $vsc_city_name = isset($_POST['vsc_city_name']) ? $_POST['vsc_city_name'] : '';

    if(!empty($vsc_city_name)){
        $args = array(      
            'post_type'   => 'shipping_city',
            's'           => $vsc_city_name,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,  
        );
        $wp_query = new WP_Query($args);

        $count = 0;



        if($wp_query->have_posts()){
            while($wp_query->have_posts()){
                $wp_query->the_post();

                if($count > 0){
                    break;
                } 

$weekly_available_times = get_field( "weekly_available_times" );

?>   
        <div class="city_search_result_wrapper">
            <h3>אנחנו באים לעיר שלך</h3>
            
            
            <?php if(!empty($weekly_available_times)) : ?>
            <ul class="vsc_city_shippting_times">
                <li>שעות החלוקה <strong><?php the_title(); ?></strong>:</li>

                <?php foreach($weekly_available_times as $key => $weekly_day) : ?>
                
                    <?php 
                    if($weekly_day['weekend']){
                        continue;
                    }

                    $avail_times = '';
                    if(!empty($weekly_day['available_times'])){

                        $and_content = ' and ';
                        $counter = 1;

                        foreach($weekly_day['available_times'] as $time){
                            if($counter > 1){
                                $avail_times .= $and_content . $time['time_range'];
                            } else {
                                $avail_times .= $time['time_range'];
                            }
                            

                            $counter++;
                        }

                        echo '<li><strong>'.  ucfirst($key) .'</strong>: '. $avail_times .'</li>';
                    }
                    ?>

                    
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

        </div>
                

     <?php           
                $count++;
            }
            wp_reset_query();
            
        }
    }



    wp_die();

}