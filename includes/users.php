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
 */
function fields( $user ) {
	$hide = get_user_meta( $user->ID, 'hide', true );
	?>
	<h3><?php esc_html_e( 'Staff Roster Metadata', 'inn-staff' ); ?></h3>
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
add_action( 'show_user_profile', 'INN\Staff\Users\fields' );
add_action( 'edit_user_profile', 'INN\Staff\Users\fields' );

/**
 * Save data from form elements added to profile via `more_profile_info`
 *
 * @param $user_id array The ID of the user for the profile being saved.
 * @since 0.4
 */
function save( $user_id ) {
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
add_action('personal_options_update', 'INN\Staff\Users\save');
add_action('edit_user_profile_update', 'INN\Staff\Users\save');
