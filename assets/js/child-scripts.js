(function ($) {
    "use strict";



    /* ===============================
    // Ajax Add To Cart
    =================================*/ 
    
    $(document).ready(function(){

        // Add new field to the menu cart
        vsc_add_elements_to_cart();


        $('li.product.vsc-product-item h3.title a').live('click', function(e){
            e.preventDefault();

            var product_id = $(this).attr('data-id');

            var data = {
                action: 'vsc_ajax_load_product_details',
                product_id: product_id,
            };

            $.ajax({
                type: 'post',
                url: vsc_loadmore.ajaxurl,
                data: data,
                beforeSend: function (response) {
                    //$thisbutton.removeClass('added').addClass('loading');
                    //console.log(data);
                },
                complete: function (response) {
                    //$thisbutton.addClass('added').removeClass('loading');
                },
                success: function (html) {

                    $.magnificPopup.open({
                        items: {
                            src: html, // can be a HTML string, jQuery object, or CSS selector
                            type: 'inline'
                        }
                    });
                    $('.featured_image_gallery_wrapper').slick({
                        autoplay: false,
                        prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
                        nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
                    });

                },
            });


        });



        // Ajax requests for product details page
        $('.vsc_add_to_cart_on_popup').live('click', function(e){
            e.preventDefault();

            var clicked_button = $(this);

            var product_id = clicked_button.attr('data-id');
            var vsc_unit_type = clicked_button.siblings('.vsc_unit_type').find('#product_unit_switch');

            

            var data = {
                action: 'vsc_ajax_add_to_cart_product_popup',
                product_id: product_id,
            };
            
            if(vsc_unit_type.length > 0){
                data.variation_per_kg_or_item = vsc_unit_type.is(':checked');
            }

            var vsc_product_note = clicked_button.closest('.product-add-to-cart').siblings('.vsc_product_note').find('input#vsc_product_note');
            if(vsc_product_note.val()){
                data.vsc_product_note = vsc_product_note.val();
            }

            $(document.body).trigger('adding_to_cart', [data]);

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
    
                    if (response.error && response.error_msg) {
                        console.log(response.error_msg);
                        return;
                    } else {
                        clicked_button.css('display', 'none');
                        clicked_button.siblings('.vsc_increase_decrease_items_popup').css('display', 'flex');

                        $('li.vsc-product-item').each(function(){
                            var p_id = $(this).attr('data-id');
                            if(p_id == product_id){
                                $(this).find('.vsc_add_to_cart_on_popup').css('display', 'none');
                                $(this).find('.vsc_increase_decrease_items_popup').css('display', 'flex');
                            }
                        });
                        
                        // Update the badge count if only it is 
                        $('.vsc_increase_decrease_count').each(function(){
                            if($(this).attr('data-id') == product_id){
                                if($(this).parents('.vsc_cart_sidebar').length == 0){
                                    $(this).text(response.current_count);
                                }
                            }
                        });

                        // Update the item count badge when it has value
                        $('li.vsc-product-item').each(function(){
                            var p_id = $(this).find('.vsc_add_to_cart_on_popup').attr('data-id');
                            if(p_id == product_id){
                                $(this).find('.vsc-product-thumb').append('<div class="vsc_item_added_cart" data-count-p-id="'+ product_id +'">'+ response.current_count +'</div>');
                            }
                        });

                        // Add vsc_already_in_cart class
                        $('li.vsc-product-item').each(function(){
                            var p_id = $(this).attr('data-id');
                            if(p_id == product_id){
                                $(this).addClass('vsc_already_in_cart');
                            }
                        });
                        

                        
                        $(document.body).trigger('added_to_cart', [{
                            added_product_html: response.added_product_html
                        }]);

                        
                    }
                },
            });
            
        });

        // Increase the item count
        $('.vsc_increase_decrease_plus_button').live('click', function(e){

    

            e.preventDefault();

            var clicked_button = $(this);
            var product_id = clicked_button.attr('data-id');

            

            var data = {
                action: 'vsc_ajax_increase_item_to_cart',
                product_id: product_id,
            };

            var vsc_unit_type = clicked_button.closest('.vsc_increase_decrease_items_popup').siblings('.vsc_unit_type').find('#product_unit_switch');
            if(vsc_unit_type.length > 0){
                data.variation_per_kg_or_item = vsc_unit_type.is(':checked');
            }

            var vsc_product_note = clicked_button.closest('.product-add-to-cart').siblings('.vsc_product_note').find('input#vsc_product_note');
            if(vsc_product_note.val()){
                data.vsc_product_note = vsc_product_note.val();
            }

            $(document.body).trigger('adding_to_cart', [data]);

            $.ajax({
                type: 'post',
                url: vsc_loadmore.ajaxurl,
                data: data,
                beforeSend: function (response) {
                    //$thisbutton.removeClass('added').addClass('loading');\
                },
                complete: function (response) {
                    //$thisbutton.addClass('added').removeClass('loading');
                },
                success: function (response) {
    
                    if (response.error && response.error_msg) {
                        console.log(response.error_msg);
                        return;
                    } else {


                        // Update the badge count if only it is 
                        $('.vsc_increase_decrease_count').each(function(){
                            if($(this).attr('data-id') == product_id){
                                if($(this).parents('.vsc_cart_sidebar').length == 0){
                                    $(this).text(response.current_count);
                                }
                            }
                        });

                        // Update the item count badge when it has value
                        $('.vsc_item_added_cart').each(function(){
                            var item_p_id = $(this).attr('data-count-p-id');

                            if(product_id == item_p_id) {
                                $(this).text(response.current_count);
                            }
                        });

                        // check if it is clicking inside of cart
                        $(document.body).trigger('added_to_cart', [{
                            added_product_html: response.added_product_html
                        }]);
                        
                    }
                },
            });
            
        });

        // Decrease the item count
        $('.vsc_increase_decrease_minus_button').live('click', function(e){
            e.preventDefault();
            var clicked_button = $(this);
            var product_id = clicked_button.attr('data-id');
            var old_quantity = clicked_button.parent().siblings('.vsc_increase_decrease_count').text();



            var data = {
                action: 'vsc_ajax_decrease_item_to_cart',
                product_id: product_id,
                old_quantity: old_quantity
            };

            var vsc_unit_type = clicked_button.closest('.vsc_increase_decrease_items_popup').siblings('.vsc_unit_type').find('#product_unit_switch');

            if(vsc_unit_type.length > 0){
                data.variation_per_kg_or_item = vsc_unit_type.is(':checked');
            }

            var vsc_product_note = clicked_button.closest('.product-add-to-cart').siblings('.vsc_product_note').find('input#vsc_product_note');
            if(vsc_product_note.val()){
                data.vsc_product_note = vsc_product_note.val();
            }


            $(document.body).trigger('adding_to_cart', [data]);

            $.ajax({
                type: 'post',
                url: vsc_loadmore.ajaxurl,
                data: data,
                beforeSend: function (response) {
                    //$thisbutton.removeClass('added').addClass('loading');
                    //console.log(data);
                },
                complete: function (response) {
                    //$thisbutton.addClass('added').removeClass('loading');
                },
                success: function (response) { 

                    // Reload the cart sidebar
                    vsc_ajax_reload_cart_sidebar();
                    vsc_ajax_reload_cart_total_price();
                    vsc_ajax_update_cart_icon_product_count();

                    if (response.error && response.error_msg) {
                        console.log(response.error_msg);
                        return;
                    } else {
                        if(response.current_count){
                            
                            
                            // Update the badge count if only it is 
                            $('.vsc_increase_decrease_count').each(function(){
                                if($(this).attr('data-id') == product_id){
                                    if($(this).parents('.vsc_cart_sidebar').length == 0){
                                        $(this).text(response.current_count);
                                    }
                                }
                            });

                            // Show the popup
                            if (response.added_product_html !== undefined){
                                $('.vsc_popup_cart_menu .elementor-menu-cart__products').html(response.added_product_html);

                                $('.vsc_popup_cart_menu').fadeIn();
                
                                setTimeout(function(){ 
                                    $('.vsc_popup_cart_menu').fadeOut();
                                }, 3000);
                            }

                            // Update the item count badge when it has value
                            $('.vsc_item_added_cart').each(function(){
                                var item_p_id = $(this).attr('data-count-p-id');
                                if(product_id == item_p_id) {
                                    $(this).text(response.current_count);
                                }
                            });
                           
                        } else {

                            // hide the popup if the current count is less then 1
                            $('.vsc_popup_cart_menu').fadeOut();

                            // Update the badge count if only it is 
                            $('.vsc_increase_decrease_count').each(function(){
                                if($(this).attr('data-id') == product_id){
                                    if($(this).parents('.vsc_cart_sidebar').length == 0){
                                        $(this).text('');
                                    }
                                }
                            });

                            // remove the cart badge when it don't have value
                            $('.vsc_item_added_cart').each(function(){
                                var item_p_id = $(this).attr('data-count-p-id');
                                if(product_id == item_p_id) {
                                    $(this).remove();
                                }
                            });


                            clicked_button.closest('.vsc_increase_decrease_items_popup').siblings('.vsc_add_to_cart_on_popup').css('display', 'inline-block');
                            clicked_button.closest('.vsc_increase_decrease_items_popup').css('display', 'none');

                            $('li.vsc-product-item').each(function(){
                                var p_id = $(this).attr('data-id');
                                if(p_id == product_id){
                                    $(this).find('.vsc_add_to_cart_on_popup').css('display', 'inline-block');
                                    $(this).find('.vsc_increase_decrease_items_popup').css('display', 'none');
                                }
                            });


                            $('li.vsc-product-item').each(function(){
                                var p_id = $(this).attr('data-id');
                                if(p_id == product_id){
                                    $(this).removeClass('vsc_already_in_cart');
                                }
                            });
                        
                        }

                    }
                },
            });
            
        });


        
        

        $(document.body).live('added_to_cart', function(event, object){

            if (object.added_product_html !== undefined){
                $('.vsc_popup_cart_menu .elementor-menu-cart__products').html(object.added_product_html);
                $('.vsc_popup_cart_menu').fadeIn();

                // setTimeout(function(){ 
                //     $('.vsc_popup_cart_menu').fadeOut();
                // }, 3000);
            }

            // reload the cart items
            vsc_ajax_reload_cart_sidebar();
            vsc_ajax_reload_cart_total_price();
            vsc_ajax_update_cart_icon_product_count();

        });





        $(document.body).live('removed_from_cart', function(event, object){
            $('.vsc_popup_cart_menu').fadeOut();
        });



        //On submit of city search form
        $('#form-field-vsc_city_name').attr('autocomplete', 'off');
        $('#vsc_find_city_form').on('submit', function(e){
            e.preventDefault();

            var vsc_city_name = $("#vsc_find_city_form :input[id='form-field-vsc_city_name']").val();

            var data = {
                action: 'vsc_find_shipping_city',
                vsc_city_name: vsc_city_name,
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
                    if(response){
                        $('#city_search_no_result').css('display', 'none');
                        $('#city_search_result').css('display', 'block');
                        $('#city_search_result .elementor-widget-wrap').html(response);

                        var add_city_name =  setInterval(function(){
                            $("#vsc_find_city_form :input[id='form-field-vsc_city_name']").val(vsc_city_name);
                        }, 100);

                        $("#vsc_find_city_form :input[id='form-field-vsc_city_name']").live('click', function(){
                            clearInterval(add_city_name);
                        });
                        // $(document).ajaxStop(function() {
                        //     clearInterval(add_city_name);
                        // });

                    } else {
                        $('#city_search_result').css('display', 'none');
                        $('#city_search_no_result').css('display', 'block');

                        var add_city_name =  setInterval(function(){
                            $("#vsc_find_city_form :input[id='form-field-vsc_city_name']").val(vsc_city_name);
                        }, 100);
                        $("#vsc_find_city_form :input[id='form-field-vsc_city_name']").live('click', function(){
                            clearInterval(add_city_name);
                        });
                    }
                },
            });

        });


        



    });
    // end of document ready function


    
    var onScrollCanBeLoaded = true;
    var bottomOffset = 1200;
    var scroll_current_page = 2;
    
    $(window).scroll(function(){

        var cat_id = $('body').attr('loaded_cat');
        var data = {
			'action': 'vsc_loadmore',
            'page' : scroll_current_page,
            'cat_id': cat_id
        };
        if( $(document).scrollTop() > ( $(document).height() - bottomOffset ) && onScrollCanBeLoaded == true ){

            $.ajax({
				url : vsc_loadmore.ajaxurl,
				data:data,
				type:'POST',
				beforeSend: function( xhr ){
                    onScrollCanBeLoaded = false; 
				},
				success:function(data){
					if( data ) {

                        $('#vsc_products_list').append(data);
						onScrollCanBeLoaded = true; // the ajax is completed, now we can run it again
                        scroll_current_page++;
					}
				}
            });
            
        }
    });


    if(Cookies.get('clicked_cat')){
        var cat_id = Cookies.get('clicked_cat');
        $('body').attr('loaded_cat', cat_id);
        Cookies.remove('clicked_cat');
        var data = {
			'action': 'vsc_load_catup_products',
            'cat_id' : cat_id,
        };
        $.ajax({
            url : vsc_loadmore.ajaxurl,
            data:data,
            type:'POST',
            beforeSend: function( xhr ){
                
            },
            success:function(data){
                if( data ) {
                    $('#vsc_products_list').html(data);

                    var data = {
                        'action': 'vsc_get_cat_title',
                        'cat_id' : cat_id,
                    };
                    $.ajax({
                        url : vsc_loadmore.ajaxurl,
                        data:data,
                        type:'POST',
                        success:function(title){
                            if( title ) {
                                $('.vsc_category_title_wrap .vsc_cat_title').text(title);
                            }
                        }
                    });

                }
            }
        });
    } else {

        var cat_id = 25;
        $('body').attr('loaded_cat', cat_id);
        var data = {
			'action': 'vsc_load_catup_products',
            'cat_id' : cat_id,
        };
        $.ajax({
            url : vsc_loadmore.ajaxurl,
            data:data,
            type:'POST',
            beforeSend: function( xhr ){
                
            },
            success:function(data){
                if( data ) {
                    $('#vsc_products_list').html(data);

                    var data = {
                        'action': 'vsc_get_cat_title',
                        'cat_id' : cat_id,
                    };
                    $.ajax({
                        url : vsc_loadmore.ajaxurl,
                        data:data,
                        type:'POST',
                        success:function(title){
                            if( title ) {
                                $('.vsc_category_title_wrap .vsc_cat_title').text(title);
                            }
                        }
                    });

                }
            }
        });

    }



    $('.vsc_categories_item a').on('click', function(e){

        onScrollCanBeLoaded = true;
        scroll_current_page = 2;

        var cat_id = $(this).attr('cat_id');
        $('body').attr('loaded_cat', cat_id);

        if(!$('body').hasClass('home')){
            Cookies.set('clicked_cat', cat_id);
            window.location.href = vsc_loadmore.vsc_home_url;
        }

        e.preventDefault();

        var data = {
			'action': 'vsc_load_catup_products',
            'cat_id' : cat_id,
        };
        $.ajax({
            url : vsc_loadmore.ajaxurl,
            data:data,
            type:'POST',
            beforeSend: function( xhr ){
                
            },
            success:function(data){
                if( data ) {
                    $('#vsc_products_list').html(data);

                    var data = {
                        'action': 'vsc_get_cat_title',
                        'cat_id' : cat_id,
                    };
                    $.ajax({
                        url : vsc_loadmore.ajaxurl,
                        data:data,
                        type:'POST',
                        success:function(title){
                            if( title ) {
                                $('.vsc_category_title_wrap .vsc_cat_title').text(title);
                            }
                        }
                    });

                }
            }
        });

    });

    

   function vsc_add_elements_to_cart(){

        $('.vsc_cart_menu .elementor-menu-cart__products .elementor-menu-cart__product').each(function(){

            var product_id = $(this).find('.product-remove a').attr('data-product_id');

            var selected_product = $(this);

            var data = {
                action: 'vsc_generate_product_quantity_html',
                product_id: product_id,
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
                    if(response) selected_product.find('.product-name').after(response);
                },
            });
        });

    }




    $('.jet-mobile-menu-widget').after('<div class="vsc_menu__toggle_count">'+ vsc_loadmore.vsc_cart_item_count +'</div>');
    $('.vsc_menu__toggle_count').live('click', function(){
        $(this).siblings().find('.jet-mobile-menu__toggle').trigger('click');
    });



    var NotemagnificPopup = $.magnificPopup.instance;

    $('.vsc_edit_product_note').live('click', function(e){
        e.preventDefault();

        var product_id = $(this).attr('product_id');
        var data = {
            action: 'vsc_ajax_load_add_note_popup',
            product_id: product_id,
        };
        $.ajax({
            type: 'post',
            url: vsc_loadmore.ajaxurl,
            data: data,
            beforeSend: function (response) {
                //$thisbutton.removeClass('added').addClass('loading');
                //console.log(data);
            },
            complete: function (response) {
                //$thisbutton.addClass('added').removeClass('loading');
            },
            success: function (html) {

                NotemagnificPopup.open({
                    items: {
                        src: html, // can be a HTML string, jQuery object, or CSS selector
                        type: 'inline'
                    }
                });

            },
        });


    });
    

    $('.vsc_save_note').live('click', function(e){
        e.preventDefault();

        var product_id = $(this).attr('product_id');
        var value = $(this).siblings('#product_popup_note').val();

        var data = {
            action: 'vsc_ajax_save_product_note_on_popup',
            product_id: product_id,
            value: value
        };
        $.ajax({
            type: 'post',
            url: vsc_loadmore.ajaxurl,
            data: data,
            beforeSend: function (response) {

            },
            success: function (response) {
                if(response){

                    $('.vsc_edit_product_note').each(function(){
                        var note_product_id = $(this).attr('product_id');
                        if(note_product_id == product_id){
                            $(this).siblings('span').text(value);
                        }
                    });

                    NotemagnificPopup.close();

                }
            },
        });
        
    });


    $('.vsc-product-remove a.remove').live('click', function(e){
        e.preventDefault();

        var product_id = $(this).attr('product_id');

        var data = {
            action: 'vsc_ajax_remove_product_from_cart',
            product_id: product_id,
        };
        $.ajax({
            type: 'post',
            url: vsc_loadmore.ajaxurl,
            data: data,
            beforeSend: function (response) {

            },
            success: function (response) {
                if(response){

                    vsc_ajax_reload_cart_sidebar();
                    vsc_ajax_reload_cart_total_price();
                    vsc_ajax_update_cart_icon_product_count();
                    

                }
            },
        });
        
    });





    // The function will reload the cart sidebar
    function vsc_ajax_reload_cart_sidebar(){

        var data = {
            action: 'vsc_ajax_reload_cart_sidebar',
        };
        $.ajax({
            type: 'post',
            url: vsc_loadmore.ajaxurl,
            data: data,
            beforeSend: function (response) {

            },
            success: function (response) {
                if(response){

                    $('.vsc_cart_sidebar .woocommerce-cart-form__contents tbody').html(response);

                }
            },
        });


    }


    function vsc_ajax_reload_cart_total_price(){

        var data = {
            action: 'vsc_ajax_reload_cart_sidebar_total_price',
        };
        $.ajax({
            type: 'post',
            url: vsc_loadmore.ajaxurl,
            data: data,
            beforeSend: function (response) {

            },
            success: function (response) {

                if(response){
                    $('.wc-proceed-to-checkout .vsc_total_price').html(response);

                    $('.jet-mobile-menu__instance--slide-out-layout.right-container-position .jet-mobile-menu__container').animate({right: "0"}, 200, 'linear');
                }

            },
        });

    }


    
    function vsc_ajax_update_cart_icon_product_count(){
        var data = {
            action: 'vsc_update_cart_count',
        };
        $.ajax({
            type: 'post',
            url: vsc_loadmore.ajaxurl,
            data: data,
            beforeSend: function (response) {

            },
            success: function (response) {

                if(response){
                    $('.vsc_menu__toggle_count').text(response);
                }

            },
        });
    }



    $('.vsc_cart_sidebar .jet-mobile-menu__toggle').live('click', function(e){
        //e.preventDefault();
        vsc_ajax_reload_cart_sidebar();
        vsc_ajax_reload_cart_total_price(); 
    });



	
}(jQuery));



 
	