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
        $('#second_col .vsc_place_order_button .button').show();
        $('#second_col').removeClass('disabled');

        

        // Populate the checkout times
        var selected_city = $('#billing_city option').filter(":selected").val();
        $('#vsc_checkout_dates_table').empty().html('<p>Second .. We\'ll already show you the shipping times</p>');
        var data = {
            action: 'vsc_load_city_checkout_times',
            selected_city: selected_city,
        };

        $.ajax({
            type: 'post',
            url: vsc_loadmore.ajaxurl,
            data: data,
            beforeSend: function (response) {
                //$thisbutton.removeClass('added').addClass('loading');
            },
            complete: function (response) {
                //$thisbutton.addClass('added').removeClass('loading');
            },
            success: function (response) {
                console.log(response);
                // if(response){
                //     $('#city_search_no_result').css('display', 'none');
                //     $('#city_search_result').css('display', 'block');
                //     $('#city_search_result .elementor-widget-wrap').html(response);
                // } else {
                //     $('#city_search_result').css('display', 'none');
                //     $('#city_search_no_result').css('display', 'block');
                // }
            },
        });

   


    });



}(jQuery));