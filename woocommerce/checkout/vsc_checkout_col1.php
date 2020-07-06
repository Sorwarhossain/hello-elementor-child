<div id="first_col" class="vsc_checkout_col disabled">
    <div class="vsc_checkout_col_heading">
        <h2><span class="icon"><i class="fas fa-user"></i></span> הפרטים שלך</h2>
    </div>

    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>


    <?php if ( $checkout->get_checkout_fields() ) : ?>

        <div class="vsc_checkout_billing_fields">
            <?php do_action( 'woocommerce_checkout_billing' ); ?>
        </div>

    <?php endif; ?>

    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

    <div class="vsc_place_order_button">
        <a href="#" class="button" id="vsc_go_delivery_stage">To coordinate the shipment</a>
    </div>
</div>