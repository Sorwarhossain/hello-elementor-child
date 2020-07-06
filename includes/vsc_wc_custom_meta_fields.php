<?php


function vsc_wc_create_general_custom_field() {
    global $post;
    $product = wc_get_product( $post->ID );
    $vsc_product_unit_label = $product->get_meta( 'vsc_product_unit_label' );
    if( empty( $vsc_product_unit_label ) ) $vsc_product_unit_label = 'יח׳';

    $args = array(
        'id'        => 'vsc_product_unit_label',
        'label'     => __( 'Product Unit Label', 'text-domain' ),
        'class' => 'vsc-unit-label',
        'description' => __( 'Enter the unit label of your product.', 'text-domain' ),
        'selected' => true,
        'value'    => $vsc_product_unit_label,
        'options' => [
            'יח׳' => __( 'יח׳', 'woocommerce' ),
            'ק״ג' => __( 'ק״ג', 'woocommerce' ),
            'מארז' => __( 'מארז', 'woocommerce' )
        ]
    );
    woocommerce_wp_select( $args );


    
}
add_action( 'woocommerce_product_options_general_product_data', 'vsc_wc_create_general_custom_field' );



function vsc_wc_create_inventory_custom_field() {

    $args = array(
        'id' => 'vsc_product_sublabel',
        'label' => __( 'Product Sublabel', 'text-domain' ),
        'class' => 'vsc-product-sublabel',
        'description' => __( 'Enter the sublabel of your product', 'text-domain' ),
    );
    woocommerce_wp_text_input( $args );


    $args = array(
        'id' => 'vsc_related_product_id',
        'label' => __( 'Related Product ID', 'text-domain' ),
        'class' => 'vsc-related-product-id',
        'description' => __( 'Enter the related product id', 'text-domain' ),
        'placeholder' => 'example: 116',
    );
    woocommerce_wp_text_input( $args );

    
}
add_action( 'woocommerce_product_options_inventory_product_data', 'vsc_wc_create_inventory_custom_field' );



function cfwc_save_custom_field( $post_id ) {

    $product = wc_get_product( $post_id );

    $vsc_product_unit_label = isset( $_POST['vsc_product_unit_label'] ) ? $_POST['vsc_product_unit_label'] : '';
    $vsc_product_sublabel = isset( $_POST['vsc_product_sublabel'] ) ? $_POST['vsc_product_sublabel'] : '';
    $vsc_related_product_id = isset( $_POST['vsc_related_product_id'] ) ? $_POST['vsc_related_product_id'] : '';
    
    $product->update_meta_data( 'vsc_product_unit_label', sanitize_text_field( $vsc_product_unit_label ) );
    $product->update_meta_data( 'vsc_product_sublabel', sanitize_text_field( $vsc_product_sublabel ) );
    $product->update_meta_data( 'vsc_related_product_id', sanitize_text_field( $vsc_related_product_id ) );

    $product->save();

}
add_action( 'woocommerce_process_product_meta', 'cfwc_save_custom_field' );