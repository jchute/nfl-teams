<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://jereross.com
 * @since      1.0.0
 *
 * @package    Nfl_Teams
 * @subpackage Nfl_Teams/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nfl_Teams
 * @subpackage Nfl_Teams/public
 * @author     Jeremy Ross <jeremyrwross@gmail.com>
 */
class Nfl_Teams_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name       The name of the plugin.
	 * @param string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nfl_Teams_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nfl_Teams_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nfl-teams-public.css', array(), $this->version, 'all' );

		// Enqueue jQuery UI styles from Google CDN
		$wp_scripts = wp_scripts();
		wp_enqueue_style(
			'jquery-ui-theme-smoothness',
				sprintf(
				'https://ajax.googleapis.com/ajax/libs/jqueryui/%s/themes/smoothness/jquery-ui.min.css',
				$wp_scripts->registered['jquery-ui-core']->ver
			),
			array(),
			$wp_scripts->registered['jquery-ui-core']->ver
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nfl_Teams_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nfl_Teams_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nfl-teams-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'jquery-effects-core' );
		wp_enqueue_script( 'jquery-ui-tabs' );

	}

	/**
	 * Registers shortcodes
	 */
	public function register_shortcodes() {

		add_shortcode( 'nfl_teams', array( $this, 'nfl_teams_list' ) );

	}

	/**
	 * Registers shortcodes
	 */
	public function nfl_teams_list() {

		$response = '';
		$api_url  = 'http://delivery.chalk247.com/team_list/NFL.JSON?api_key=';
		$api_key  = get_option( $this->plugin_name . '-api-key' );

		if ( ! $api_key && is_user_logged_in() ) {
			echo 'API Key not set. <a href="' . get_admin_url() . 'admin.php?page=nfl-teams-settings">Set the API Key</a>.';
		}

		$api_url = $api_url . $api_key;

		$response = wp_remote_get( $api_url );

		if ( is_array( $response ) ) {

			$results = json_decode( $response['body'], true );

			// Simplify the array to contain only teams.
			$teams = $results['results']['data']['team'];

			// Get all possible conferences and divisions.
			$conferences = $this->unique_multidim_array($teams, 'conference');
			$divisions   = $this->unique_multidim_array($teams, 'division');

			echo '<div id="nfl-teams">';

			// Create tab navigation.
			echo '<ul>';
			foreach( $conferences as $conference) {
				echo '<li><a href="#' . esc_attr( sanitize_title( $conference['conference'] ) ) . '">' . $conference['conference'] . '</a></li>';
			}
			echo '</ul>';

			foreach( $conferences as $conference) {

				echo '<div class="tab-content" id="' . esc_attr( sanitize_title( $conference['conference'] ) ) . '">';
				echo '<div class="conference">';

				foreach( $divisions as $division) {

					// Search for all teams in the current conference.
					$teams_conference = $this->search_multidim_array( $teams, 'conference', $conference['conference'] );

					// Using the teams from the conference search above, find teams in the current division.
					$teams_division   = $this->search_multidim_array( $teams_conference, 'division', $division['division'] );

					if( ! empty( $teams_division ) ) {

						echo '<div class="division">';
						echo '<h3>' . $division['division'] . '</h3>';
						echo '<ul>';
						foreach( $teams_division as $team) {
							echo '<li>' . $team['name'] . '</li>';
						}
						echo '</ul>';
						echo '</div><!-- /.division -->';

					}
				}
				echo '</div><!-- /.conference -->';
				echo '</div><!-- /.tab-content -->';
			}
			echo '</div><!-- /#nfl-teams -->';
		}
	}

	/**
	 * Return unique elements of multidimensional array
	 * http://php.net/manual/en/function.array-unique.php#116302
	 */
	public function unique_multidim_array($array, $key) {
		$temp_array = array();
		$count      = 0;
		$key_array  = array();

		foreach($array as $val) {
			if (!in_array($val[$key], $key_array)) {
				$key_array[$count] = $val[$key];
				$temp_array[$count] = $val;
			}
			$count++;
		}
		return $temp_array;
	}

	/**
	 * multidimensional array search
	 */
	public function search_multidim_array($array, $key, $needle) {

		$result = array();

		$results = array_keys( array_combine( array_keys( $array ), array_column( $array, $key ) ), $needle );

		foreach($results as $v  ) {
			$result[] = $array[ $v ];
		}

		return $result;

	}
}
