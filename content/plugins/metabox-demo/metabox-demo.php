<?php
/**
 * Plugin Name: Metabox Demo
 * Description: A demo plugin which shows revisioning of a custom metabox field.
 * Author: Juerg Hunziker <juerg.hunziker@gmail.com>
 * Version: 1.0.0
 * Date: 21.12.2015
 *
 * @package metabox-demo
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'cmr_meta_boxes_setup' );
add_action( 'load-post-new.php', 'cmr_meta_boxes_setup' );

/* Meta box setup function. */
function cmr_meta_boxes_setup() {
	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'cmr_add_post_meta_boxes' );
	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'cmr_save_post_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function cmr_add_post_meta_boxes() {
	add_meta_box(
		'cmr-customfield',      // Unique ID
		esc_html__( 'Custom Field', 'cmr' ),    // Title
		'cmr_customfield_meta_box',   // Callback function
		'post',         // Admin page (or post type)
		'side',         // Context
		'default'         // Priority
	);
}

/* Display the post meta box. */
function cmr_customfield_meta_box( $object, $box ) {
	wp_nonce_field( basename( __FILE__ ), 'cmr_customfield_nonce' );

	echo '<p>';
		echo '<label for="cmr-customfield">' . esc_html__( 'Add a custom field', 'cmr' ) . '</label><br />';
		echo  '<input class="widefat" type="text" name="cmr-customfield" id="cmr-customfield" value="' . esc_attr( get_post_meta( $object->ID, 'cmr-customfield', true ) ) . '" size="30" />';
	echo '</p>';
}

/* Save the meta box's post metadata. */
function cmr_save_post_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['cmr_customfield_nonce'] ) || !wp_verify_nonce( $_POST['cmr_customfield_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( isset( $_POST['cmr-customfield'] ) ? sanitize_html_class( $_POST['cmr-customfield'] ) : '' );

	/* Get the meta key. */
	$meta_key = 'cmr-customfield';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value ) {
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );
	} elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
		/* If the new meta value does not match the old value, update it. */
		update_post_meta( $post_id, $meta_key, $new_meta_value );
	} elseif ( '' == $new_meta_value && $meta_value ) {
		/* If there is no new meta value but an old value exists, delete it. */
		delete_post_meta( $post_id, $meta_key, $meta_value );
	}
}