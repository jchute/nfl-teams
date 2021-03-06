<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://jereross.com
 * @since      1.0.0
 *
 * @package    Nfl_Teams
 * @subpackage Nfl_Teams/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nfl_Teams
 * @subpackage Nfl_Teams/admin
 * @author     Jeremy Ross <jeremyrwross@gmail.com>
 */
class Nfl_Teams_Admin {

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
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nfl-teams-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nfl-teams-admin.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Creates the settings page menu item
	 */
	public function add_menu() {

		add_menu_page(
			__( 'NFL Teams', 'nfl-teams' ),
			__( 'NFL Teams', 'nfl-teams' ),
			'manage_options',
			'nfl-teams-settings',
			array( $this, 'page_settings' ),
			'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"><path fill="#82878c" d="M30 2c-.49-.49-4.1-.737-8.477-.042l.977.542c2.485 1.381 5.619 4.515 7 7l.542.976C30.737 6.1 30.49 2.49 30 2zM18 4l-1.354-.903C13.479 4.097 10.347 5.653 8 8c-2.346 2.346-3.903 5.479-4.902 8.646L4 18c2.209 3.313 6.687 7.791 10 10l1.354.902C18.521 27.903 21.653 26.347 24 24c2.347-2.347 3.903-5.479 4.902-8.646L28 14c-2.209-3.313-6.687-7.791-10-10zm3.707 9.707a.997.997 0 0 1-1.414 0L20 13.414l-.586.586.293.293a.999.999 0 1 1-1.414 1.414L18 15.414l-.586.586.293.293a.999.999 0 1 1-1.414 1.414L16 17.414l-.586.586.293.293a.999.999 0 1 1-1.414 1.414L14 19.414l-.586.586.293.293a.999.999 0 1 1-1.414 1.414l-2-2a.999.999 0 1 1 1.414-1.414l.293.293.586-.586-.293-.293a.999.999 0 1 1 1.414-1.414l.293.293.586-.586-.293-.293a.999.999 0 1 1 1.414-1.414l.293.293.586-.586-.293-.293a.999.999 0 1 1 1.414-1.414l.293.293.586-.586-.293-.293a.999.999 0 1 1 1.414-1.414l2 2a.999.999 0 0 1 0 1.414zM2.5 22.5l-.542-.977C1.263 25.9 1.51 29.51 2 30c.49.49 4.1.737 8.477.042l-.976-.542c-2.486-1.381-5.62-4.515-7.001-7z"/></svg>'),
			30
		);

		add_submenu_page(
			'nfl-teams-settings',
			'Help',
			'Help',
			'manage_options',
			'nfl-teams-help',
			array( $this, 'page_help' )
		);
	}

	/**
	 * Creates the help page
	 */
	public function page_help() {

		include( plugin_dir_path( __FILE__ ) . 'partials/nfl-teams-help.php' );

	}

	/**
	 * Creates the settings page
	 */
	public function page_settings() {

		include( plugin_dir_path( __FILE__ ) . 'partials/nfl-teams-settings.php' );

	}

	/**
	 * Registers plugin settings
	 */
	public function register_settings() {

		// register_setting( string $option_group, string $option_name, array $args = array() )

		$args = array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => NULL,
          );

		register_setting(
			$this->plugin_name . '-options',
			$this->plugin_name . '-api-key',
			$args
		);

	}

	/**
	 * Registers settings sections with WordPress
	 */
	public function register_sections() {

		// add_settings_section( $id, $title, $callback, $menu_slug );

		add_settings_section(
			$this->plugin_name . '-settings',
			__( 'API Settings', 'nfl-teams' ),
			array( $this, 'section_messages' ),
			$this->plugin_name
		);

	}

	/**
	 * Displays a message before each settings section
	 */
	public function section_messages() {

		// Blank.

	}

	/**
	 * Registers settings fields with WordPress
	 */
	public function register_fields() {

		// add_settings_field( string $id, string $title, callable $callback, string $page, string $section = 'default', array $args = array() )

		add_settings_field(
			$this->plugin_name . 'api-key',
			__( 'API Key', 'nfl-teams' ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '-settings'
		);

	}

	/**
	 * Displays form input
	 */
	public function field_text( ) {

		$key = $this->plugin_name . '-api-key';

		$atts['name']  = $key;
		$atts['value'] = get_option( $key );

		?>
		<input class="regular-text" name="<?php echo esc_attr( $atts['name'] ); ?>" type="text" required value="<?php echo esc_attr( $atts['value'] ); ?>">
	<?php

	}
}
