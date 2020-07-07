<?php


echo '<div class="vsc_shipping_general_comment_wrapper">';

    echo '<div class="vsc_checkout_remark">
        <h3 class="vsc_custom_checkout_title">Remarks</h3>
        <a href="#" id="vsc_close_remark_btn" onclick="return false;" >Add Comments <i class="fas fa-plus"></i></a>
    </div>';

    echo '<div class="vsc_shipping_general_comment_forms">';
        woocommerce_form_field(
            'vsc_shipping_general_comment', 
            array(
                'type' => 'text',
                'class' => array(
                    'form-row-wide'
                ),
                'label' => __('General Comment'),
            //'placeholder' => __('New Custom Field'),
            ),
            $checkout->get_value('vsc_shipping_general_comment')
        );
        woocommerce_form_field(
            'vsc_shipping_note_to_messanger', 
            array(
                'type' => 'text',
                'class' => array(
                    'form-row-wide'
            ),
            'label' => __('Note to the messenger', 'text-domain'),
            'placeholder' => __('For example, leave at the door ...', 'text-domain'),
            ),
            $checkout->get_value('vsc_shipping_note_to_messanger')
        );
    echo '</div>';


echo '</div>';