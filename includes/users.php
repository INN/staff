<?php
/**
 * Functions regarding user meta for this plugin
 */
namespace INN\Staff\Users;

/**
 * Display extra profile fields related to staff member status
 *
 * @param $user array The WP_User object for the current profile.
 * @since 0.1.0
 * @since Largo 0.4
 * @todo nonces
 */
function staff_list_fields( $user ) {
	$hide = get_user_meta( $user->ID, 'hide', true );
	?>
	<?php if ( current_user_can( 'edit_users' ) ) { ?>
	<tr>
		<th><label for="staff_widget"><?php esc_html_e( 'Staff status', 'inn-staff' ); ?></label></th>
		<td>
			<input type="checkbox" name="hide" id="hide"
				<?php checked( $hide, 'on' ); }?> />
			<label for="hide"><?php _e( 'Hide in roster', 'inn-staff' ); ?></label><br />
		</td>
	</tr>
	<?php
}
add_action( 'show_user_profile', 'INN\Staff\Users\staff_list_fields' );
add_action( 'edit_user_profile', 'INN\Staff\Users\staff_list_fields' );

/**
 * Save data from form elements added to profile via `more_profile_info`
 *
 * @param $user_id array The ID of the user for the profile being saved.
 * @since 0.4
 * @todo check nonces
 */
function staff_list_fields_save( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	$values = wp_parse_args(
		$_POST,
		array(
			'hide' => 'off',
		)
	);

	update_user_meta( $user_id, 'hide', $values['hide'] );
}
add_action('personal_options_update', 'INN\Staff\Users\staff_list_fields_save');
add_action('edit_user_profile_update', 'INN\Staff\Users\staff_list_fields_save');

/*
 * This section duplicates some Largo functionality in unavoidable ways
 */

/**
 * Display extra profile fields related to staff member status
 *
 * @param $users array The WP_User object for the current profile.
 * @since Largo 0.4
 */
function more_profile_info($user) {
	$show_email = get_user_meta( $user->ID, "show_email", true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="job_title"><?php _e( 'Job title', 'inn-staff' ); ?></label></th>
			<td>
				<input type="text" name="job_title" id="job_title" value="<?php echo esc_attr( get_the_author_meta( 'job_title', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e( 'Please enter your job title.', 'largo' ); ?></span>
			</td>
		</tr>

		<tr>
			<th><label for="show_email"><?php _e( 'Show Email Address', 'largo' ); ?></label></th>
			<td>
				<input type="checkbox" name="show_email" id="show_email"
					<?php if (esc_attr($show_email) == "on" || empty($show_email)) { ?>checked<?php } ?> />
				<label for="show_email"><?php _e( 'Show email address publicly?', 'largo' ); ?></label><br />
			</td>
		</tr>

		<?php do_action('largo_more_profile_information', $user); ?>
	</table>
<?php }
add_action( 'show_user_profile', 'more_profile_info' );
add_action( 'edit_user_profile', 'more_profile_info' );

/**
 * Save data from form elements added to profile via `more_profile_info`
 *
 * @param $user_id array The ID of the user for the profile being saved.
 * @since 0.4
 * @since Largo 0.4
 */
function save_more_profile_info($user_id) {
	if (!current_user_can('edit_user', $user_id ))
		return false;
	
	if ( ! isset($_POST['show_email']) ) {
		$_POST['show_email'] = 'off';
	}

	$values = wp_parse_args($_POST, array(
		'show_email' => 'on',
	));

	update_user_meta($user_id, 'job_title', $values['job_title']);
	update_user_meta($user_id, 'show_email', $values['show_email']);
}
add_action('personal_options_update', 'save_more_profile_info');
add_action('edit_user_profile_update', 'save_more_profile_info');
