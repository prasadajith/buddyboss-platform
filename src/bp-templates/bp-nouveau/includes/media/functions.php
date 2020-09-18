<?php
/**
 * Media functions
 *
 * @since BuddyBoss 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Register Scripts for the Media component
 *
 * @since BuddyBoss 1.0.0
 *
 * @param array $scripts The array of scripts to register
 *
 * @return array The same array with the specific media scripts.
 */
function bp_nouveau_media_register_scripts( $scripts = array() ) {
	if ( ! isset( $scripts['bp-nouveau'] ) ) {
		return $scripts;
	}

	return array_merge( $scripts, array(
		'bp-nouveau-media' => array(
			'file'         => 'js/buddypress-media%s.js',
			'dependencies' => array( 'bp-nouveau' ),
			'footer'       => true,
		)
	) );
}

/**
 * Enqueue the media scripts
 *
 * @since BuddyBoss 1.0.0
 */
function bp_nouveau_media_enqueue_scripts() {

	wp_enqueue_script( 'bp-nouveau-media-document-data-table' );

	if ( bp_is_user_media() ||
	     bp_is_single_album() ||
	     bp_is_media_directory() ||
	     bp_is_activity_component() ||
	     bp_is_group_activity() ||
	     bp_is_group_media() ||
	     bp_is_group_albums() ||
	     bp_is_group_document() ||
	     bp_is_group_folders() ||
	     bp_is_group_messages() ||
	     bp_is_messages_component()
	) {

		$gif = false;
		if ( bp_is_profiles_gif_support_enabled() || bp_is_groups_gif_support_enabled() || bp_is_messages_gif_support_enabled() ) {
			wp_enqueue_script( 'giphy' );
			$gif = true;
		}

		$emoji = false;
		if ( bp_is_profiles_emoji_support_enabled() || bp_is_groups_emoji_support_enabled() || bp_is_messages_emoji_support_enabled() ) {
			wp_enqueue_script( 'emojionearea' );
			wp_enqueue_style( 'emojionearea' );
			$emoji = true;
		}

		if ( bp_is_profile_media_support_enabled() || bp_is_group_document_support_enabled() || bp_is_group_media_support_enabled() || bp_is_group_albums_support_enabled() || bp_is_messages_media_support_enabled() || $gif || $emoji || bp_is_group_messages() ) {
			wp_enqueue_script( 'bp-media-dropzone' );
			wp_enqueue_script( 'bp-nouveau-codemirror' );
			wp_enqueue_script( 'bp-nouveau-codemirror-css' );
			wp_enqueue_script( 'bp-nouveau-media' );
			wp_enqueue_script( 'bp-exif' );
		}
	}

}

/**
 * Localize the strings needed for the messages UI
 *
 * @since BuddyPress 3.0.0
 *
 * @param  array $params Associative array containing the JS Strings needed by scripts
 * @return array         The same array with specific strings for the messages UI if needed.
 */
function bp_nouveau_media_localize_scripts( $params = array() ) {

	//initialize media vars because it is used globally
	$params['media'] = array(
		'max_upload_size'              => bp_media_file_upload_max_size(),
		'profile_media'                 => bp_is_profile_media_support_enabled(),
		'profile_album'                 => bp_is_profile_albums_support_enabled(),
		'group_media'                  => bp_is_group_media_support_enabled(),
		'group_album'                  => bp_is_group_albums_support_enabled(),
		'messages_media'               => bp_is_messages_media_support_enabled(),
		'dropzone_media_message'       => __( 'Drop images here to upload', 'buddyboss' ),
		'media_select_error'           => __( 'This file type is not supported for photo uploads.', 'buddyboss' ),
		'empty_media_type'             => __( 'Empty media file will not be uploaded.', 'buddyboss' ),
		'invalid_media_type'           => __( 'Unable to upload the file', 'buddyboss' ),
		'media_size_error_header'      => __( 'File too large ', 'buddyboss' ),
		'media_size_error_description' => __( 'This file type is too large.', 'buddyboss' ),
		'dictFileTooBig'               => __( "File is too large ({{filesize}} MB). Max filesize: {{maxFilesize}} MB.", 'buddyboss' ),
		'maxFiles'                     => apply_filters( 'bp_media_upload_chunk_limit', 10 ),
		'cover_photo_size_error_header'=> __( 'Unable to reposition the image. ', 'buddyboss' ),
		'cover_photo_size_error_description'=> __( 'To reposition your cover photo, please upload a larger image and then try again.', 'buddyboss' ),
	);

	if ( bp_is_single_album() ) {
		$params['media']['album_id'] = (int) bp_action_variable( 0 );
	}

	if ( bp_is_single_folder() ) {
		$params['document']['folder_id'] = (int) bp_action_variable( 0 );
	}

	if ( bp_is_active( 'groups' ) && bp_is_group() ) {
		$params['media']['group_id'] = bp_get_current_group_id();
    }

	$params['media']['emoji']            = array(
		'profile'  => bp_is_profiles_emoji_support_enabled(),
		'groups'   => bp_is_groups_emoji_support_enabled(),
		'messages' => bp_is_messages_emoji_support_enabled(),
		'forums'   => bp_is_forums_emoji_support_enabled(),
		'document' => bp_is_forums_document_support_enabled(),
	);
	$params['media']['emoji_filter_url'] = buddypress()->plugin_url . 'bp-core/images/emojifilter/';

	$params['media']['gif']         = array(
		'profile'  => bp_is_profiles_gif_support_enabled(),
		'groups'   => bp_is_groups_gif_support_enabled(),
		'messages' => bp_is_messages_gif_support_enabled(),
		'forums'   => bp_is_forums_gif_support_enabled(),
		'document' => bp_is_forums_document_support_enabled(),
	);
	$params['media']['gif_api_key'] = bp_media_get_gif_api_key();

	$params['media']['i18n_strings'] = array(
		'select'               => __( 'Select', 'buddyboss' ),
		'unselect'             => __( 'Unselect', 'buddyboss' ),
		'selectall'            => __( 'Select All', 'buddyboss' ),
		'unselectall'          => __( 'Unselect All', 'buddyboss' ),
		'no_photos_found'      => __( 'Sorry, no photos were found', 'buddyboss' ),
		'upload'               => __( 'Upload', 'buddyboss' ),
		'uploading'            => __( 'Uploading', 'buddyboss' ),
		'upload_status'        => __( '%d out of %d uploaded', 'buddyboss' ),
		'album_delete_confirm' => __( 'Are you sure you want to delete this album? Photos in this album will also be deleted.', 'buddyboss' ),
		'album_delete_error'   => __( 'There was a problem deleting the album.', 'buddyboss' ),
	);

	return $params;
}

/**
 * Get the nav items for the Media directory
 *
 * @since BuddyBoss 1.0.0
 *
 * @return array An associative array of nav items.
 */
function bp_nouveau_get_media_directory_nav_items() {
	$nav_items = array();


	$nav_items['all'] = array(
		'component' => 'media',
		'slug'      => 'all', // slug is used because BP_Core_Nav requires it, but it's the scope.
		'li_class'  => array(),
		'link'      => bp_get_media_directory_permalink(),
		'text'      => __( 'All Photos', 'buddyboss' ),
		'count'     => bp_get_total_media_count(),
		'position'  => 5,
	);

	if ( is_user_logged_in() && bp_is_profile_media_support_enabled() ) {
		$nav_items['personal'] = array(
			'component' => 'media',
			'slug'      => 'personal', // slug is used because BP_Core_Nav requires it, but it's the scope.
			'li_class'  => array(),
			'link'      => bp_loggedin_user_domain() . bp_get_media_slug() . '/my-media/',
			'text'      => __( 'My Photos', 'buddyboss' ),
			'count'     => bp_media_get_total_media_count(),
			'position'  => 15,
		);
	}

	if ( is_user_logged_in() && bp_is_group_media_support_enabled() ) {
		$nav_items['group'] = array(
			'component' => 'media',
			'slug'      => 'groups', // slug is used because BP_Core_Nav requires it, but it's the scope.
			'li_class'  => array(),
			'link'      => bp_loggedin_user_domain() . bp_get_document_slug() . '/groups-media/',
			'text'      => __( 'My Groups', 'buddyboss' ),
			'count'     => bp_media_get_user_total_group_media_count(),
			'position'  => 15,
		);
	}

	/**
	 * Use this filter to introduce your custom nav items for the media directory.
	 *
	 * @since BuddyBoss 1.0.0
	 *
	 * @param array $nav_items The list of the media directory nav items.
	 */
	return apply_filters( 'bp_nouveau_get_media_directory_nav_items', $nav_items );
}

function bp_media_download_file( $attachment_id, $type = 'media' ) {

	// Add action to prevent issues in IE.
	add_action( 'nocache_headers', 'bp_media_ie_nocache_headers_fix' );

	if ( 'media' === $type ) {

		$the_file = wp_get_attachment_url( $attachment_id );

		if ( ! $the_file ) {
			return;
		}

		// clean the file url.
		$file_url = stripslashes( trim( $the_file ) );

		// get filename.
		$file_name = basename( $the_file );

		bp_media_download_file_force( $the_file, $file_name );
	}

}

/**
 * Edit button alter when media activity other than activity page.
 *
 * @param array $buttons     Array of Buttons visible on activity entry.
 * @param int   $activity_id Activity ID.
 *
 * @return mixed
 * @since BuddyBoss 1.5.1
 */
function bp_nouveau_media_activity_edit_button( $buttons, $activity_id ) {
	if ( isset( $buttons['activity_edit'] ) && ( bp_is_media_component() || ! bp_is_activity_component() ) && ! empty( $_REQUEST['action'] ) && 'media_get_activity' === $_REQUEST['action'] ) {
		$activity = new BP_Activity_Activity( $activity_id );

		if ( ! empty( $activity->id ) && 'media' !== $activity->privacy ) {
			$buttons['activity_edit']['button_attr']['href']  = bp_activity_get_permalink( $activity_id ) . 'edit';

			$classes  = explode( ' ', $buttons['activity_edit']['button_attr']['class'] );
			$edit_key = array_search( 'edit', $classes, true );
			if ( ! empty( $edit_key ) ) {
				unset( $classes[ $edit_key ] );
			}
			$buttons['activity_edit']['button_attr']['class'] = implode( ' ', $classes );
		}
	}

	return $buttons;
}
