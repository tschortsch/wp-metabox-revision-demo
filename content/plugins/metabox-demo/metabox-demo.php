<?php
/**
 * Plugin Name: Metabox Demo
 * Description: A demo plugin which shows revisioning of a custom metabox field. Based on http://www.smashingmagazine.com/2011/10/create-custom-post-meta-boxes-wordpress/.
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
add_filter( 'wp_post_revision_title_expanded', 'cmr_meta_add_details_to_list', 10, 2 );

/**
 * Add meta details to the revisions list.
 */
function cmr_meta_add_details_to_list( $title, $revision ) {
	$title .= ' <span class="cmr-custom-meta">Custom meta: ' . get_post_meta( $revision->ID, 'cmr-customfield', true )[0] . '</span>';
	return $title;

}

/* Meta box setup function. */
function cmr_meta_boxes_setup() {
	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'cmr_add_post_meta_boxes' );
	/* Save post meta on the 'edit_post' hook. */
	add_action( 'edit_post', 'cmr_save_post_meta', 10, 2 );
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
		/* If there is no new meta value but an old value exists, clear it. */
		update_post_meta( $post_id, $meta_key, $meta_value );
	}
}

// Use wp-post-meta-revisions plugin to create revisions of custom meta field
function add_meta_keys_to_revision( $keys ) {
	$keys[] = 'cmr-customfield';
	return $keys;
}
add_filter( 'wp_post_revision_meta_keys', 'add_meta_keys_to_revision' );