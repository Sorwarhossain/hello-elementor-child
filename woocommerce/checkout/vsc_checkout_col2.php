<div id="second_col" class="vsc_checkout_col disabled">
    <div class="vsc_checkout_col_heading">
        <h2><span class="icon"><i class="fas fa-truck-moving"></i></span> פרטי משלוח</h2>
    </div>

    <?php if ( $checkout->get_checkout_fields() ) : ?>
        <div class="vsc_checkout_addtional_fields">
            <h3 class="vsc_custom_checkout_title">When is it convenient for us to arrive?</h3>
            <p>Delivery times shown are according to the partition area and the free shipping times when sending the order. A more accurate timeframe of up to two hours can be obtained, close to the shipment date as specified in Section 3.4 of the Site Policies</p>


            <?php do_action( 'woocommerce_checkout_shipping' ); ?>

        </div>
    <?php endif; ?>


    <div class="vsc_place_order_button">
        <a href="#" class="button" id="vsc_go_delivery_stage">In summary and payment</a>
    </div>

</div>