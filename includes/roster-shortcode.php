<?php
/**
 * The [roster] shortcode and necessary support functions
 *
 * @since 0.1.0
 */
namespace INN\Staff\Shortcode;

/**
 * Shortcode version of `largo_render_user_list`
 *
 * @param $atts array The attributes of the shortcode.
 *
 * Example of possible attributes:
 *
 * 	[roster roles="author,contributor" include="292,12312" exclude="5002,2320" show_users_with_empty_desc="true"]
 *
 * @since 0.4
 */
function shortcode( $atts = array() ) {
	$options = array();

	$show_users_with_empty_desc = false;
	if (!empty($atts['show_users_with_empty_desc'])) {
		$show_users_with_empty_desc = ($atts['show_users_with_empty_desc'] == 'false')? false : true;
		unset($atts['show_users_with_empty_desc']);
	}

	if (!empty($atts['roles'])) {
		$roles = explode(',', $atts['roles']);
		$options['roles'] = array_map(function($arg) { return trim($arg); }, $roles);
	}

	if (!empty($atts['exclude'])) {
		$exclude = explode(',', $atts['exclude']);
		$options['exclude'] = array_map(function($arg) { return trim($arg); }, $exclude);
	}

	if (!empty($atts['include'])) {
		$exclude = explode(',', $atts['include']);
		$options['include'] = array_map(function($arg) { return trim($arg); }, $exclude);
	}

	$defaults = array(
		'roles' => array(
			'author'
		)
	);
	$args = array_merge($defaults, $options);
	render_user_list(get_user_list($args), $show_users_with_empty_desc);
}
add_shortcode('roster', 'INN\Staff\Shortcode\shortcode');

/**
 * Get users based on a role. Defaults to fetching all authors for the current blog.
 *
 * @param $args array Same as the options one would pass to `get_users` with one extra
 * key -- `roles` -- which should be an array of roles to include in the query.
 * @since Largo 0.4
 * @since 0.1
 */
function get_user_list($args=array()) {
	$roles = (isset($args['roles']))? $args['roles'] : null;
	unset($args['roles']);

	$args = array_merge(array(
		'blog_id' => get_current_blog_id(),
		'include' => array(),
		'exclude' => array(),
		'role' => 'author',
		'orderby' => 'display_name'
	), $args);

	if (empty($roles)) {
		$users = get_users($args);
	} else {
		$users = array();
		foreach ($roles as $role) {
			$args['role'] = $role;
			$result = get_users($args);
			$users = array_merge($users, $result);
		}
	}
	return $users;
}

/**
 * Render a list of user profiles based on the array of users passed
 *
 * @param $users array The WP_User objects to use in rendering the list.
 * @param $show_users_with_empty_desc bool Whether we should skip users that have no bio/description.
 * @since 0.4
 */
function render_user_list($users, $show_users_with_empty_desc=false) {
	echo '<div class="user-list">';
	foreach ($users as $user) {
		$desc = trim($user->description);
		if (empty($desc) && ($show_users_with_empty_desc == false))
			continue;

		$hide = get_user_meta($user->ID, 'hide', true);
		if ($hide == 'on')
			continue;

		$ctx = array('author_obj' => $user);
		echo '<div class="author-box row-fluid">';
		largo_render_template('partials/author-bio', 'description', $ctx);
		echo '</div>';
	}
	echo '</div>';
}
