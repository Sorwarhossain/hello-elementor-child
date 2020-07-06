<?php

// Register Custom Post Type
function vsc_custom_post_types() {

	$labels = array(
		'name'                  => _x( 'Shipping Cities', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Shipping City', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Shipping Cities', 'text_domain' ),
		'name_admin_bar'        => __( 'Post Type', 'text_domain' ),
		'all_items'             => __( 'All Shipping Cities', 'text_domain' ),
		'add_new_item'          => __( 'Add Shpping City', 'text_domain' ),
		'add_new'               => __( 'Add Shpping City', 'text_domain' ),
		'new_item'              => __( 'New Shpping City', 'text_domain' ),
		'edit_item'             => __( 'Edit Shpping City', 'text_domain' ),
		'update_item'           => __( 'Update Shpping City', 'text_domain' ),
		'view_item'             => __( 'View Shpping City', 'text_domain' ),
		'view_items'            => __( 'View Shipping Cities', 'text_domain' ),
        'search_items'          => __( 'Search Shipping City', 'text_domain' ),
    );
	$args = array(
		'label'                 => __( 'Shipping City', 'text_domain' ),
		'description'           => __( 'Shipping City Description', 'text_domain' ),
		'labels'                => $labels,
        'hierarchical'          => false,
        'menu_icon'             => 'dashicons-admin-multisite',
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'shipping_city', $args );

}
add_action( 'init', 'vsc_custom_post_types');