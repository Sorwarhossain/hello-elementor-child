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
                $(this).siblings('.vsc_shipping_general_comment_forms').slideUp();
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

        var error = false;
        
        // Check if there is a empty field
        $('#first_col .form-row.validate-required').each(function(){
            if($(this).find('input').length > 0){
                if(!$(this).find('input').val()){
                    alert('Please fill all the required fields');
                    error = true;
                    return false;
                }
            };
            if($(this).find('select').length > 0){
                if( !$(this).find('select option').filter(":selected").val() ){
                    alert('Please fill all the required fields');
                    error = true;
                    return false;
                }
            };
        });

        // If there is error then just return the request false
        if(error){
            return false;
        }

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
            url: vsc_checkout_data.ajaxurl,
            data: data,
            beforeSend: function (response) {
                //$thisbutton.removeClass('added').addClass('loading');
                console.log(data);
            },
            complete: function (response) {
                //$thisbutton.addClass('added').removeClass('loading');
            },
            success: function (response) {
                if(response) $('#vsc_checkout_dates_table').html(response);    
            },
        });

   


    });



    // When select the checkout time
    $('.vsc_selectable_date').live('click', function(e){
        e.preventDefault();
        var selected_time = $(this).text();
        var selected_date = $(this).parent().siblings('td').find('p').text();

        $('.vsc_selectable_date').removeClass('selected_date');
        $(this).addClass('selected_date');

        // Update the field value
        $('#vsc_checkout_delivery_date').val(selected_date);
        $('#vsc_checkout_delivery_time').val(selected_time);
    });



    // ON click at first step
    $('#vsc_go_payment_stage').on('click', function(e){
        e.preventDefault();

        var error = false;

        // Check if there is a empty field
        $('#second_col .form-row.validate-required').each(function(){
            if($(this).find('input').length > 0){
                if(!$(this).find('input').val()){
                    alert('Please select checkout date and time');
                    error = true;
                    return false;
                }
            };
        });


        // If there is error then just return the request false
        if(error){
            return false;
        }

        // Proceed to next step
        $('body').attr('checkout-step', 'third');
        $('#third_col .vsc_place_order_button .button').show();
        $('#third_col').removeClass('disabled');

    });



}(jQuery));