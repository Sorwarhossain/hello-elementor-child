<div id="third_col" class="vsc_checkout_col disabled">
			<div class="vsc_checkout_col_heading">
				<h2><span class="icon"><i class="fas fa-credit-card"></i></span> פרטי תשלום</h2>
			</div>


			<div class="vsc_checkout_payment_fields">
				<h3 class="vsc_custom_checkout_title">Summary</h3>

<?php

woocommerce_form_field(
	'vsc_payment_terms_services', 
	array(
		'type' => 'checkbox',
		'class' => array(
			'form-row-wide'
		),
		'required' => true,
		'label' => __('I have read and approved the <a href="#" target="_blank">terms of use</a>'),
	),
	$checkout->get_value('vsc_payment_terms_services')
);

woocommerce_form_field(
	'vsc_payment_newsletter_subscribe', 
	array(
		'type' => 'checkbox',
		'class' => array(
			'form-row-wide'
		),
		'label' => __('I would like to receive updates on seasonal promotions and surprises by email'),
	),
	$checkout->get_value('vsc_payment_newsletter_subscribe')
);
?>


<?php if ( wc_coupons_enabled() ) : ?>

<div class="vsc_coupon_section">
	<div class="vsc_coupon_enable">
		<h4>Redeem coupon</h4>
		<div class="vsc_coupon_open_icon"><a class="vsc_coupon_open_icon_button"></a></div>
	</div>
	<div class="vsc_coupon_form">
		<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none">
			<p class="form-row form-row-first">
				<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />
			</p>
			<p class="form-row form-row-last">
				<button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
			</p>
			<div class="clear"></div>
		</form>
	</div>
</div>
<?php endif; ?>


<div class="vsc_checkout_price">
	<table class="vsc_checkout_price_table">
		<!-- <tr>
			<td>Products</td> 
			<td><?php //wc_cart_totals_subtotal_html(); ?></td>
		</tr> -->
		<tr class="vsc_checkout_total">
			<td>Total (revaluation)</td> 
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>
	</table>

	<p class="vsc_checkout_price_table_notice">The final billing cost will be determined after weighing the products on the day of ordering. Prices are updated as of <?php echo get_the_date( 'j/m/Y' ); ?></p>
</div>


<div class="vsc_payment_methods">

	<h3 class="vsc_custom_checkout_title">payment method</h3>
	

	<?php if ( WC()->cart->needs_payment() ) : 
	$available_gateways = WC()->payment_gateways->get_available_payment_gateways();	
	?>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
			}
			?>
		</ul>
	<?php endif; ?>
</div>

<div class="vsc_place_order_button">	
	<?php do_action( 'woocommerce_review_order_before_submit' ); ?>	

	<?php 
	$order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Continue to pay by credit card', 'woocommerce' ) );
	?>

	<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

	<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

	<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
</div>
<!-- end of place order button -->








			</div>


</div>