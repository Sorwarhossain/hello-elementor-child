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



        $('.add_product_plus').live('click', function(e){
            e.preventDefault();

            var product_id = $(this).attr('data-id');
            var data = {
                action: 'vsc_ajax_add_to_cart',
                product_id: product_id,
            };

            var clicked_button = $(this);

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
                        clicked_button.siblings('.vsc_plus_minus_icon').css('display', 'block');
                        clicked_button.siblings('.vsc_plus_minus_icon').find('.product_number').text(response.current_count);
                        
                        $(document.body).trigger('added_to_cart', [{
                            added_product_html: response.added_product_html
                        }]);
                    }
                },
            });
            
        });

        // Increase the item count
        $('.increase_product_plus').live('click', function(e){
            e.preventDefault();

            var product_id = $(this).attr('data-id');
            var data = {
                action: 'vsc_ajax_increase_item_to_cart',
                product_id: product_id,
            };

            var clicked_button = $(this);

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
                        clicked_button.siblings('.product_number').text(response.current_count);

                        $(document.body).trigger('added_to_cart', [{
                            added_product_html: response.added_product_html
                        }]);

                    }
                },
            });
            
        });




        // Decrease the item count
        $('.decrease_product_minus').live('click', function(e){
            e.preventDefault();

            var product_id = $(this).attr('data-id');
            var clicked_button = $(this);
            var old_quantity = clicked_button.siblings('.product_number').text();

            var data = {
                action: 'vsc_ajax_decrease_item_to_cart',
                product_id: product_id,
                old_quantity: old_quantity
            };

            

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
                        if(response.current_count){
                            clicked_button.siblings('.product_number').text(response.current_count);

                            // Show the popup
                            if (response.added_product_html !== undefined){
                                $('.vsc_popup_cart_menu .elementor-menu-cart__products').html(response.added_product_html);
                                $('.vsc_popup_cart_menu').fadeIn();
                
                                setTimeout(function(){ 
                                    $('.vsc_popup_cart_menu').fadeOut();
                                }, 4000);
                            }
                           
                        } else {

                            // hide the popup if the current count is less then 1
                            $('.vsc_popup_cart_menu').fadeOut();

                            clicked_button.siblings('.product_number').text('');
                            clicked_button.closest('.vsc_plus_minus_icon').siblings('.add_product_plus').css('display', 'block');
                            clicked_button.closest('.vsc_plus_minus_icon').css('display', 'none');
                        }
                    
                    }
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
                        clicked_button.siblings('.vsc_increase_decrease_items_popup').find('.vsc_increase_decrease_count').text(response.current_count);

                        // Update the item count badge when it has value
                        
                            clicked_button.closest('.vsc-product-thumb-hover').after('<div class="vsc_item_added_cart" data-count-p-id="'+ product_id +'">'+ response.current_count +'</div>');
                            

   
                        
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
                        clicked_button.parent().siblings('.vsc_increase_decrease_count').text(response.current_count);

                        // Update the item count badge when it has value
                        $('.vsc_item_added_cart').each(function(){
                            var item_p_id = $(this).attr('data-count-p-id');

                            if(product_id == item_p_id) {
                                $(this).text(response.current_count);
                            }
                        });

                        // check if it is clicking inside of cart
                        if(clicked_button.closest('.product-add-to-cart').parent('.cart-product-add-to-cart').length > 0){

                        } else {
                            $(document.body).trigger('added_to_cart', [{
                                added_product_html: response.added_product_html
                            }]);
                        }
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
                    if (response.error && response.error_msg) {
                        console.log(response.error_msg);
                        return;
                    } else {
                        if(response.current_count){
                            clicked_button.parent().siblings('.vsc_increase_decrease_count').text(response.current_count);

                            // Show the popup
                            if (response.added_product_html !== undefined){
                                $('.vsc_popup_cart_menu .elementor-menu-cart__products').html(response.added_product_html);
                                $('.vsc_popup_cart_menu').fadeIn();
                
                                setTimeout(function(){ 
                                    $('.vsc_popup_cart_menu').fadeOut();
                                }, 4000);
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

                            clicked_button.parent().siblings('.vsc_increase_decrease_count').text('');
                            clicked_button.closest('.vsc_increase_decrease_items_popup').siblings('.vsc_add_to_cart_on_popup').css('display', 'inline-block');
                            clicked_button.closest('.vsc_increase_decrease_items_popup').css('display', 'none');

                            // remove the cart badge when it don't have value
                            $('.vsc_item_added_cart').each(function(){
                                var item_p_id = $(this).attr('data-count-p-id');
                                if(product_id == item_p_id) {
                                    $(this).remove();
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

                setTimeout(function(){ 
                    $('.vsc_popup_cart_menu').fadeOut();
                }, 3000);

            }

        });





        $(document.body).live('removed_from_cart', function(event, object){
            if (object.added_product_html !== undefined){
                $('.vsc_popup_cart_menu .elementor-menu-cart__products').html(object.added_product_html);
                $('.vsc_popup_cart_menu').fadeIn();

                setTimeout(function(){ 
                    $('.vsc_popup_cart_menu').fadeOut();
                }, 3000);
            }
        });





        
        $(document).ajaxComplete(function(){
            var product_html = $('.dgwt-wcas-suggestion-product').parent().html();
            console.log(product_html);
            $('.dgwt-wcas-suggestion.dgwt-wcas-suggestion-product.dgwt-wcas-suggestion-selected').unbind();
        });

        $('.dgwt-wcas-suggestion.dgwt-wcas-suggestion-product.dgwt-wcas-suggestion-selected').unbind();

        $('.dgwt-wcas-suggestion.dgwt-wcas-suggestion-product.dgwt-wcas-suggestion-selected').live('click', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            $(this).unbind();
            alert('clicked');
        });

        $(document).live('keypress', function(e){
            
            if(e.keyCode == 13) {
                alert('Enter pressed');
                e.preventDefault();
                e.stopImmediatePropagation();
            }
            // e.preventDefault();
            // e.stopImmediatePropagation();
            // $(this).unbind();
            // alert('clicked');
        });

        $('form.dgwt-wcas-search-form').bind("keypress", function(e) {
            if (e.keyCode == 13) {               
              e.preventDefault();
              return false;
            }
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


    
    var canBeLoaded = true; // this param allows to initiate the AJAX call only if necessary
    var bottomOffset = 1200;
    var current_page = 2;
    
    $(window).scroll(function(){
        
        var data = {
			'action': 'vsc_loadmore',
			'page' : current_page
        };
        if( $(document).scrollTop() > ( $(document).height() - bottomOffset ) && canBeLoaded == true ){

            $.ajax({
				url : vsc_loadmore.ajaxurl,
				data:data,
				type:'POST',
				beforeSend: function( xhr ){
                    canBeLoaded = false; 
                    //$('#vsc_products_list').after('<div class=="loading_more_products">Loading...</div>');
				},
				success:function(data){
					if( data ) {

                        $('#vsc_products_list').append(data);

                        // Update the loaded_products to body
                        var products_count = $('#vsc_products_list li.vsc-only-product').length;
                        $('body').attr('loaded-p', parseInt(products_count));

						//$('#main').find('article:last-of-type').after( data ); // where to insert posts
						canBeLoaded = true; // the ajax is completed, now we can run it again
                        current_page++;
					}
				}
            });
            
        }
    });


    // Set loaded products to the body as attribute
    var products_count = $('#vsc_products_list li.vsc-only-product').length;
    $('body').attr('loaded-p', products_count);


    $('.vsc_categories_item a').on('click', function(e){

        e.preventDefault();
        
        var cat_id = $(this).attr('cat-id');
        var cat_href = $(this).attr('href');

        if($(cat_href).length){
            return;
        }
        var loaded_posts = $('body').attr('loaded-p');
        var data = {
			'action': 'vsc_load_catup_products',
            'cat_id' : cat_id,
            'loaded_posts': loaded_posts
        };
        $.ajax({
            url : vsc_loadmore.ajaxurl,
            data:data,
            type:'POST',
            beforeSend: function( xhr ){
                
            },
            success:function(data){
                if( data ) {
                    $('#vsc_products_list').append(data);

                    // Update the loaded_products to body
                    var products_count = $('#vsc_products_list li.vsc-only-product').length;
                    $('body').attr('loaded-p', parseInt(products_count));  
                    
                    var position = $(cat_href).offset().top - 160;

                    $('html, body').animate({
                        scrollTop: position
                    }, 300);

                }
            }
        });


        
        $('#vsc_categories_list').navpoints({offset: 160});
        $('#menu-1-1f30ab1').navpoints({offset: 160});

    });

    $('#vsc_categories_list .vsc_categories_item').each(function(){
        var item_heref = $(this).find('a').attr('href');
        var item_cat_id = $(this).find('a').attr('cat-id');

        $('ul.elementor-nav-menu li.vsc_categories_item').each(function(){
            var selected_href = $(this).find('a').attr('href');
            if(item_heref == selected_href){
                $(this).find('a').attr('cat-id', item_cat_id);
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






 
    $(document).ready(function(){

        $('.vsc_cart_sidebar .product-remove a').live('click', function(e){

            e.preventDefault();

            var product_id = $(this).attr('product_id');
    
            var data = {
                action: 'vsc_remove_items_from_cart',
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
                    if(response.success){
                        $('.vsc_cart_sidebar button[name="update_cart"]').trigger('click');
                    } else {
                        console.log('Cart Item not removed for an unknown issue!');
                    }
                },
            });

        });

        

    });

    



	
}(jQuery));



 
	