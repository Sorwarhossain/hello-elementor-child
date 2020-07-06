(function ($) {
    "use strict";

    $(document).ready(function(){


        // Enable coupon form on checkout page
        $('.vsc_coupon_enable h4').on('click', function(){
            if($(this).parent().hasClass('active')){

                $(this).parent().removeClass('active');
                $(this).parent().siblings('.vsc_coupon_form').hide();

            } else {

                $(this).parent().addClass('active');
                $(this).parent().siblings('.vsc_coupon_form').show();

            }
            
        });

        // Enable general comment on the checkout page
        $('.vsc_checkout_remark').on('click', function(){
            if($(this).hasClass('active')){
                $(this).removeClass('active');
                $(this).find('#vsc_close_remark_btn i').removeClass('fa-minus');
                $(this).find('#vsc_close_remark_btn i').addClass('fa-plus');
                $(this).siblings('.vsc_shipping_general_comment_forms').slideUpy();
            } else {
                $(this).addClass('active');
                $(this).find('#vsc_close_remark_btn i').removeClass('fa-plus');
                $(this).find('#vsc_close_remark_btn i').addClass('fa-minus');
                $(this).siblings('.vsc_shipping_general_comment_forms').slideDown();
            }
            
        });


    });
    // End of document ready


    // Customize the checkout fields layout
    var billing_first_name_field = $("#billing_first_name_field")[0].outerHTML;
    var billing_last_name_field = $("#billing_last_name_field")[0].outerHTML;
    var billing_email_field = $("#billing_email_field")[0].outerHTML;
    var billing_phone_field = $("#billing_phone_field")[0].outerHTML;
    var billing_extra_phone_field = $("#billing_extra_phone_field")[0].outerHTML;

    $("#billing_first_name_field").remove();
    $("#billing_last_name_field").remove();
    $("#billing_email_field").remove();
    $("#billing_phone_field").remove();
    $("#billing_extra_phone_field").remove();

    $('.vsc_checkout_billing_fields .woocommerce-billing-fields__field-wrapper').prepend('<div class="vsc_billing_input_fields">'+ billing_first_name_field + billing_last_name_field + billing_email_field + billing_phone_field + billing_extra_phone_field +'</div>');


    billing_city_field
    billing_address_1_field
    billing_address_2_field
    billing_state_field
    billing_apartment_field
    billing_floor_field
    billing_entry_code_field
    
    
    



    // checkout page scripts
    if($('body').hasClass('woocommerce-checkout')){
        $('body').attr('checkout-step', 'first');

        $('#second_col .vsc_place_order_button .button').hide();
        $('#third_col .vsc_place_order_button .button').hide();

        $('#first_col').removeClass('disabled');
    }

    // ON click at first step
    $('#vsc_go_delivery_stage').on('click', function(e){
        e.preventDefault();

        
        // Check if there is a empty field
        $('#first_col .form-row.validate-required').each(function(){
            if($(this).find('input').length > 0){
                if(!$(this).find('input').val()){
                    alert('Please fill all the required fields');
                    return false;
                }
            };
            if($(this).find('select').length > 0){
                if( !$(this).find('select option').filter(":selected").val() ){
                    alert('Please fill all the required fields');
                    return false;
                }
            };
        });

        // Proceed to next step
        $('body').attr('checkout-step', 'second');
        $('#first_col .vsc_place_order_button .button').hide();
        $('#second_col .vsc_place_order_button .button').show();
        $('#second_col').removeClass('disabled');




    });



}(jQuery));