<?php
/**
 * Plugin Name.
 *
 * @package   Arlo_For_Wordpress
 * @author    Arlo <info@arlo.co>
 * @license   GPL-2.0+
 * @link      http://arlo.co
 * @copyright 2015 Arlo
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to 'class-arlo-for-wordpress-admin.php'
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package Arlo_For_Wordpress
 * @author  Your Name <email@example.com>
 */
class Arlo_For_Wordpress {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '2.4';

	/**
	 * Minimum required PHP version
	 *
	 * @since   2.0.6
	 *
	 * @var     string
	 */
	const MIN_PHP_VERSION = '5.4.0';

	/**
	 * @TODO - Rename "arlo-for-wordpress" to the name your your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public $plugin_slug = 'arlo-for-wordpress';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Location for overloaded data.
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
    protected $data = array();
    
	/**
	 * $post_types: used to set default settings & create posts types for import
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
    public static $post_types = array(
		'upcoming' => array(
			'slug' => 'upcomingevents',
			'name' => 'Upcoming events',
			'singular_name' => 'Upcoming event list',
			'regionalized' => true
		),
		'event' => array(
			'slug' => 'event',
			'name' => 'Events',
			'singular_name' => 'Catalogue',
			'regionalized' => true
		),
		'venue' => array(
			'slug' => 'venue',
			'name' => 'Venues',
			'singular_name' => 'Venue list'
		),		
		'presenter' => array(
			'slug' => 'presenter',
			'name' => 'Presenters',
			'singular_name' => 'Presenter list'
		),		
		'eventsearch' => array(
			'slug' => 'eventsearch',
			'name' => 'Event search',
			'singular_name' => 'Event search',
			'regionalized' => true
		),
    );
    
	/**
	 * $pages: used to set the necessary pages
	 *
	 * @since    2.2.0
	 *
	 * @var      array
	 */
	 
    public static $pages = array(

			array(
				'name'				=> 'events',
				'title'				=> 'Events',
				'content' 			=> '[arlo_event_template_list]',
				'child_post_type'	=> 'event'
			),
			array(
				'name'				=> 'eventsearch',
				'title'				=> 'Event search',
				'content' 			=> '[arlo_event_template_search_list]',
				'child_post_type'	=> 'event'
			),			
			array(
				'name'				=> 'upcoming',
				'title'				=> 'Upcoming Events',
				'content' 			=> '[arlo_upcoming_list]'
			),
			array(
				'name'				=> 'presenters',
				'title'				=> 'Presenters',
				'content' 			=> '[arlo_presenter_list]',
				'child_post_type'	=> 'presenter'
			),
			array(
				'name'				=> 'venues',
				'title'				=> 'Venues',
				'content' 			=> '[arlo_venue_list]',
				'child_post_type'	=> 'venue'
			),
		);    
    
	/**
	 * $price_settings: used to set the price showing on the site
	 *
	 * @since    2.1.0
	 *
	 * @var      array
	 */
    public static $price_settings = array(
        'exclgst' => 'Exclude GST.',
        'inclgst' => 'Include GST.',
    ); 
    
	/**
	 * $dismissible_notices: valid dismissible notices
	 *
	 * @since    2.1.5
	 *
	 * @var      array
	 */
    public static $dismissible_notices = array(
    	'welcome' => 'arlo-welcome-admin-notice',
    	'developer' => 'arlo-developer-admin-notice',
    	'webinar' => 'arlo-webinar-admin-notice',
    	'newpages' => 'arlo-newpages-admin-notice',
    );     
    
	/**
	 * $delivery_labels: used to show the different delivery types
	 *
	 * @since    2.0.6
	 *
	 * @var      array
	 */
    public static $delivery_labels = array(
        0 => 'Workshop',
        1 => 'Online',
    );
    
	/**
	 * $templates: defines the available templates for the plugin
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
    public static $templates = array(
		'event' => array(
			'id' => 'event',
			'name' => 'Event',
			'sub' => array(
				'' => 'List',
				'grid' => 'Grid'
			)
		),
		'events' => array(
			'id' => 'events',
			'shortcode' => '[arlo_event_template_list]',
			'name' => 'Catalogue'
		),
		'eventsearch' => array(
			'id' => 'eventsearch',
			'shortcode' => '[arlo_event_template_search_list]',
			'name' => 'Event search list'
		),
		'upcoming' => array(
			'id' => 'upcoming',
			'shortcode' => '[arlo_upcoming_list]',
			'name' => 'Upcoming event list',
			'sub' => array(
				'' => 'List',
				'grid' => 'Grid'
			)
		),
		'presenter' => array(
			'id' => 'presenter',
			'name' => 'Presenter'
		),
		'presenters' => array(
			'id' => 'presenters',
			'shortcode' => '[arlo_presenter_list]',
			'name' => 'Presenter list'
		),
		'venue' => array(
			'id' => 'venue',
			'name' => 'Venue'
		),
		'venues' => array(
			'id' => 'venues',
			'shortcode' => '[arlo_venue_list]',
			'name' => 'Venue list'
		),
    );

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		// check for a proxy redirect request
		add_action( 'wp', array( $this, 'redirect_proxy' ) );

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Register custom post types
		add_action( 'init', 'arlo_register_custom_post_types');

		// Activate plugin when new blog is added
		//add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		//add_action( '@TODO', array( $this, 'action_method_name' ) );
		//add_filter( '@TODO', array( $this, 'filter_method_name' ) );
		
		// cron actions
		add_filter( 'cron_schedules', array( $this, 'add_cron_schedules' ) ); 
		add_action( 'arlo_scheduler', array( $this, 'cron_scheduler' ) );
		
		add_action( 'arlo_set_import', array( $this, 'cron_set_import' ) );
		
		//load custom css
		add_action( 'wp_head', array( $this, 'load_custom_css' ) );
		
		//add canonical urls for the filtered lists
		add_action( 'wp_head', array( $this, 'add_canonical_urls' ) );
		
		//add meta description
		add_action( 'wp_head', array( $this, 'add_meta_description' ) );
		
		// GP: Check if the scheduled task is entered. If it does not exist set it. (This ensures it is in as long as the plugin is activated.  
		if ( ! wp_next_scheduled('arlo_set_import')) {
			wp_schedule_event( time(), 'hourly', 'arlo_set_import' );
		}
		
		if ( ! wp_next_scheduled('arlo_scheduler')) {
			wp_schedule_event( time(), 'minutes_5', 'arlo_scheduler' );
		}

		// content and excerpt filters to hijack arlo registered post types
		add_filter('the_content', 'arlo_the_content');
	
	
		add_action( 'wp_ajax_dismissible_notice', array($this, 'dismissible_notice_callback'));
		
		add_action( 'wp_ajax_start_scheduler', array($this, 'start_scheduler_callback'));
		
		add_action( 'wp_ajax_arlo_get_task_info', array($this, 'arlo_get_task_info_callback'));
		
		add_action( 'wp_ajax_arlo_get_last_import_log', array($this, 'arlo_get_last_import_log_callback'));
		
		
		
		// the_post action - allows us to inject Arlo-specific data as required
		// consider this later
		//add_action( 'the_posts', array( $this, 'the_posts_action' ) );
		
		add_action( 'init', 'set_search_redirect');
		
		add_action( 'wp', 'set_region_redirect');
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		
		//check the PHP version
		if (version_compare(phpversion(), self::MIN_PHP_VERSION) === -1) {
    		wp_die(sprintf(__('The minimum required PHP version for the Arlo plugin is %s'), self::MIN_PHP_VERSION));
		}

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}
	
	/**
	 * Run update scripts according to the version of the plugin
	 *
	 * @since    2.2.1
	 *
	 * @return   null
	 */
	public static function update($new_version, $old_version) {
		arlo_add_datamodel();	
	
		if (version_compare($old_version, '2.2.1') < 0) {
			self::run_update('2.2.1');
		}	
		
		if (version_compare($old_version, '2.3') < 0) {
			self::run_update('2.3');
		}

		if (version_compare($old_version, '2.3.5') < 0) {
			self::run_update('2.3.5');
		}
		
		if (version_compare($old_version, '2.4') < 0) {
			self::run_update('2.4');
		}
		
	}	
	
	private static function run_update($version) {
		switch($version) {
			case '2.2.1': 
				//Add [arlo_no_event_text] shortcode to the templates
				$update_templates = ['eventsearch', 'events'];
				$saved_templates = arlo_get_option('templates');
				
				foreach ($update_templates as $id) {
					if (!empty($saved_templates[$id]['html'])) {
						$content = $saved_templates[$id]['html'];
						
						if (strpos($content, "arlo_no_event_text") === false) {
							$shortcode = "\n[arlo_no_event_text]\n";
							$append_after = "[arlo_category_footer]";						
						
							//try to find the [arlo_category_footer], and append before
							$pos = strpos($content, $append_after);
							if ($pos !== false) {
								$pos += strlen($append_after);
							} else {
								$pos = strlen($content);
							}
							
							$saved_templates[$id]['html'] = substr_replace($content, $shortcode, $pos, 0);
						}
					}
				}
				
				arlo_set_option('templates', $saved_templates);
			break;
			case '2.3': 
				$saved_templates = arlo_get_option('templates');

				//Add [arlo_template_region_selector] shortcode to the event template
				if (!empty($saved_templates['event']['html']) && strpos($saved_templates['event']['html'], "[arlo_template_region_selector]") === false) {
					$saved_templates['event']['html'] = "[arlo_template_region_selector]\n" . $saved_templates['event']['html'];
				}
				
				//Add [arlo_template_region_selector] shortcode to the catalogue template
				if (!empty($saved_templates['events']['html']) && strpos($saved_templates['event']['html'], "[arlo_template_region_selector]") === false) {
					$saved_templates['events']['html'] = "[arlo_template_region_selector]\n" . $saved_templates['events']['html'];
				}
								
				//Add [arlo_template_search_region_selector] shortcode to the event search template
				if (!empty($saved_templates['eventsearch']['html']) && strpos($saved_templates['event']['html'], "[arlo_template_search_region_selector]") === false) {
					$saved_templates['eventsearch']['html'] = "[arlo_template_search_region_selector]\n" . $saved_templates['eventsearch']['html'];
				}				

				//Add [arlo_upcoming_region_selector] shortcode to the upcoming events list template
				if (!empty($saved_templates['upcoming']['html']) && strpos($saved_templates['event']['html'], "[arlo_upcoming_region_selector]") === false) {
					$saved_templates['upcoming']['html'] = "[arlo_upcoming_region_selector]\n" . $saved_templates['upcoming']['html'];
				}
				
				arlo_set_option('templates', $saved_templates);
			break;
			
			case '2.3.5':
				wp_clear_scheduled_hook( 'arlo_import' );
			break;
			
			case '2.4': 
				
				//Add [event_template_register_interest] shortcode to the event template
				$update_templates = ['event'];
				$saved_templates = arlo_get_option('templates');
				
				foreach ($update_templates as $id) {
					if (!empty($saved_templates[$id]['html'])) {
						$content = $saved_templates[$id]['html'];
						
						if (strpos($content, "[arlo_event_template_register_interest]") === false) {
							$shortcode = "\n[arlo_event_template_register_interest]\n";
							$append_before = [
								"[arlo_suggest_datelocation",
								"[arlo_content_field_item",
								"<h3>Similar courses",
							];
							foreach ($append_before as $target) {
								//try to find the given shortcode, and append before
								$pos = strpos($content, $target);
								if ($pos !== false) {
									break;
								}
							}
							
							if ($pos === false) {
								$pos = strlen($content);
							}
							
							$saved_templates[$id]['html'] = substr_replace($content, $shortcode, $pos, 0);
						}
					}
				}
				
				arlo_set_option('templates', $saved_templates);
			break;			
		}	
	}
	

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
		
		//add data model
		arlo_add_datamodel();

		// flush permalinks upon plugin deactivation
		flush_rewrite_rules();

		// must happen before adding pages
		self::set_default_options();
		
		// run import every 15 minutes
		$temp = wp_schedule_event( time(), 'minutes_15', 'arlo_scheduler' ) . ': Log initiated.';
		self::get_instance()->add_import_log($temp, date('Y-m-d H:i:s T'));

		// now add pages
		self::add_pages();
		
		update_option('arlo_plugin_version', self::VERSION);
	}

	/**
	 * Set the default values for arlo wp_options table option
	 *
	 * @since    1.0.0
	 *
	 */
	private static function set_default_options() {
		$settings = get_option('arlo_settings');
		
		if (is_array($settings) && count($settings)) {
			//add new templates			
			foreach(self::$templates as $id => $template) {
				if (empty($settings['templates'][$id]['html'])) {
					$settings['templates'][$id] = array(
						'html' => arlo_get_blueprint($id)
					);				
				}
			}
			
			update_option('arlo_settings', $settings);
			
		} else {
			$default_settings = array(
				'platform_name' => '',
				'post_types' => self::$post_types,
				'templates' => array()
			);
			
			foreach(self::$templates as $id => $template) {
				$default_settings['templates'][$id] = array(
					'html' => arlo_get_blueprint($id)
				);
			}		
			
			add_option('arlo_settings', $default_settings);			
		}
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here

		// flush permalinks upon plugin deactivation
		flush_rewrite_rules();
		
		wp_clear_scheduled_hook( 'arlo_scheduler' );
		wp_clear_scheduled_hook( 'arlo_set_import' );
		wp_clear_scheduled_hook( 'arlo_import' );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		
		load_plugin_textdomain( $domain, false, plugin_basename( dirname( __FILE__ ) ) . '/../languages' );
		
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css?20160814', __FILE__ ), array(), self::VERSION );
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles-darktooltip', plugins_url( 'assets/css/libs/darktooltip.min.css', __FILE__ ), array(), self::VERSION );
		
		$customcss_load_type = get_option('arlo_customcss');
		if ($customcss_load_type == 'file' && file_exists(plugin_dir_path( __FILE__ ) . 'assets/css/custom.css')) {
			$customcss_timestamp = get_option('arlo_customcss_timestamp');
			wp_enqueue_style( $this->plugin_slug .'-custom-styles', plugins_url( 'assets/css/custom.css', __FILE__ ), array(), $customcss_timestamp );		
		}	
	}
	
	/**
	 * Add canonical urls for the filtered lists (upcoming, category).
	 * SEO compatibility
	 *
	 * @since    2.2.0
	 */
	public function add_canonical_urls() {
		$settings = get_option('arlo_settings');
		$page_id = get_query_var('page_id', '');
		$obj = get_queried_object();
		
		$page_id = (empty($obj->ID) ? $page_id : $obj->ID);	
		
		$filter_enabled_page_ids = [];
		
		$filter_enabled_arlo_pages = ['upcoming', 'event'];
				
		foreach($filter_enabled_arlo_pages as $page) {
			if (!empty($settings['post_types'][$page]['posts_page'])) {
				$filter_enabled_page_ids[] = intval($settings['post_types'][$page]['posts_page']);
			}			
		}
				
		if (in_array($page_id, $filter_enabled_page_ids)) {
			$url = get_home_url() . '/' .$obj->post_name;

			//has to be the same order as in public.js to construct the same order
			if (!empty($_GET['arlo-category'])) {
				$url .= '/cat-' . urlencode($_GET['arlo-category']);
			}
			
			if (!empty($_GET['arlo-month'])) {
				$url .= '/month-' . urlencode($_GET['arlo-month']);
			}
			
			if (!empty($_GET['arlo-location'])) {
				$url .= '/location-' . urlencode($_GET['arlo-location']);
			}

			if (isset($_GET['arlo-delivery']) && is_numeric($_GET['arlo-delivery'])) {
				$url .= '/delivery-' . urlencode($_GET['arlo-delivery']);
			}

			if (!empty($_GET['arlo-eventtag'])) {
				if (is_numeric($_GET['arlo-eventtag'])) {
					$tag = self::get_tag_by_id($_GET['arlo-eventtag']);
					if (!empty($tag['tag'])) {
						$_GET['arlo-eventtag'] = $tag['tag'];
					}
				}
				$url .= '/eventtag-' . urlencode($_GET['arlo-eventtag']);
			}
			
			if (!empty($_GET['arlo-templatetag'])) {
				if (is_numeric($_GET['arlo-templatetag'])) {
					$tag = self::get_tag_by_id($_GET['arlo-templatetag']);
					if (!empty($tag['tag'])) {
						$_GET['arlo-templatetag'] = $tag['tag'];
					}					
				}
			
				$url .= '/templatetag-' . urlencode($_GET['arlo-templatetag']);
			}
			
			echo '<link rel="canonical" href="' . $url . '/" />';
		}
	}	
	
	/**
	 * Add meta descriptions for the template
	 * SEO compatibility
	 *
	 * @since    2.2.0
	 */
	public function add_meta_description() {
		$settings = get_option('arlo_settings');
		$page_id = get_query_var('page_id', '');
		$obj = get_queried_object();
		
		$page_id = (empty($obj->ID) ? $page_id : $obj->ID);
		
		if (!empty($obj->post_type) && $obj->post_type == 'arlo_event' && !empty($obj->post_content)) {
			$ellipsis = '';
			$desc = strip_tags($obj->post_content);
			if (strlen($desc) >= 150) {
				$end_pos = strpos($desc, " ", 140);
				$ellipsis = '...';
			} else {
				$end_pos = strlen($desc);
			}
			$desc = substr($desc, 0, $end_pos) . $ellipsis;
			
			echo '<meta description="' . htmlspecialchars($desc, ENT_COMPAT, 'UTF-8') . '">';
		}
	}	
	
	
	
	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    2.2.0
	 */
	public function load_custom_css() {
		$customcss_load_type = get_option('arlo_customcss');
				
		if ($customcss_load_type !== 'file' || !file_exists(plugin_dir_path( __FILE__ ) . 'assets/css/custom.css')) {
			$settings = get_option('arlo_settings');
			
			if (!empty($settings['customcss'])) {
				echo "\n<style>\n" . $settings['customcss'] . "\n</style>\n";
			}
		}
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js?20160814', __FILE__ ), array( 'jquery' ), self::VERSION );
		wp_enqueue_script( $this->plugin_slug . '-plugin-script-darktooltip', plugins_url( 'assets/js/libs/jquery.darktooltip.min.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		wp_enqueue_script( $this->plugin_slug . '-plugin-script-cookie', plugins_url( 'assets/js/libs/jquery.cookie.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		wp_localize_script( $this->plugin_slug . '-plugin-script', 'objectL10n', array(
			'showmoredates' => __( 'Show me more dates', $this->plugin_slug ),
		) );
		wp_localize_script( $this->plugin_slug . '-plugin-script', 'WPUrls', array(
			'home_url' => get_home_url(),
		) );
		
	}
	
	/**  Local Setter  */
	public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
    
    /**  Local Getter  */
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }

	/* API & import functionality */
    
	public function set_import_id($import_id) {
		update_option('arlo_import_id', $import_id);
                               
		$this->import_id = $import_id;
	}    
        
	public function get_import_id() {	
                global $wpdb;
                
                //need to access the db directly, get_option('arlo_import_id'); can return a cached (old) value
                $table_name = "{$wpdb->prefix}options";
                
                $sql = "SELECT option_value
			FROM $table_name 
                        WHERE option_name = 'arlo_import_id'";
                
                $import_id = $wpdb->get_var($sql);
            
		$this->import_id = $import_id;
                
		return $this->import_id;
	}            
	
	public function get_import_log($limit=1) {
		global $wpdb;
		
		$table_name = "{$wpdb->prefix}arlo_import_log";
		
		$items = $wpdb->get_results(
			"SELECT log.* 
			FROM $table_name log 
			ORDER BY log.id DESC
			LIMIT $limit"
		);
		
		return $items;
	}
	
	public function get_last_successful_import_log() {
		global $wpdb;
		
		$table_name = "{$wpdb->prefix}arlo_import_log";
		
		$item = $wpdb->get_row(
			"SELECT log.* 
			FROM $table_name log 
			WHERE log.successful = 1 
			ORDER BY log.created DESC
			LIMIT 1"
		);
		
		if($item) return $item;
		
		return false;
	}
	
	public function add_import_log($message, $timestamp, $successful = true, $utimestamp = null) {
		global $wpdb;
		
		$table_name = "{$wpdb->prefix}arlo_import_log";
		
		$wpdb->query(
			$wpdb->prepare( 
				"INSERT INTO $table_name 
				(message, created, successful) 
				VALUES ( %s, %s, %d ) 
				", 
			    $message,
				$timestamp,
				$successful
			)
		);
		
		// MV: Automatically purge the log after two weeks day. 
		$wpdb->query("DELETE FROM $table_name WHERE CREATED < NOW() - INTERVAL 14 DAY ORDER BY ID ASC LIMIT 10");

		if($successful) {
			$this->set_last_import($utimestamp);
		}
	}
	
	// should only be used when successful
	public function set_last_import($timestamp) {
		update_option('arlo_last_import', $timestamp);
		$this->last_imported = $timestamp;
	}
	
	public function get_last_import() {
		if(!is_null($this->last_imported)) {
			return $this->last_imported;
		}
		
		$this->last_imported = get_option('arlo_last_import');
		
		return $this->last_imported;
	}
	
	public function get_scheduler() {
		if($scheduler = $this->__get('scheduler')) {
			return $scheduler;
		}
		
		$scheduler = new \Arlo\Scheduler($this);
		
		$this->__set('scheduler', $scheduler);
		
		return $scheduler;
	}
	
	public function get_api_client() {
		if(get_option('arlo_test_api')) {
			define('ARLO_TEST_API', true);
		}
	
		$platform_name = arlo_get_option('platform_name');
		
		if(!$platform_name) return false;
	
		if($client = $this->__get('api_client')) {
			return $client;
		}
	
		$transport = new \ArloAPI\Transports\Wordpress();
		$transport->setRequestTimeout(30);
		
		$client = new \ArloAPI\Client($platform_name, $transport, self::VERSION);
		
		$this->__set('api_client', $client);
		
		return $client;
	}
	
	public function cron_set_import() {
		$scheduler = $this->get_scheduler();
		
		$scheduler->set_task("import");
	}
	
	public function call_wp_cron() {
		$now = DateTime::createFromFormat('U.u', microtime(true));
        $timestamp = $now->format("Y-m-d H:i:s");	
        
		$url = get_site_url() . '/wp-cron.php?doing_wp_cron=1';        
	
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		curl_exec($ch);  
		
		if($errno = curl_errno($ch)) {
			$error_message = curl_strerror($errno);
			$this->add_import_log($url . ' ' . $error_message, $timestamp, false);
		} else {
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
			$this->add_import_log($url . $httpcode, $timestamp, false);
		}
		 
		curl_close($ch);
	}
	
	public function cron_scheduler() {
		try{
			//necessary to avoid multiple scripts running on a forced import.
			sleep(2);
			$this->clean_up_tasks();
			$this->run_task_scheduler();
		}catch(\Exception $e){
			var_dump($e);
		}
	}
	
	public function clean_up_tasks() {
		$scheduler = $this->get_scheduler();
		
		$running_tasks = $scheduler->get_running_tasks();
				
		foreach ($running_tasks as $task) {
			$ts = strtotime($task->task_modified);
			$now = time();			
			if ($now - $ts > 8*60) {
				$scheduler->update_task($task->task_id, 3, "Import doesn't respond within 8 minutes, stopped by the scheduler");
			}
		}
	}

	public function run_task_scheduler() {
		$scheduler = $this->get_scheduler();
		
		$scheduler->run_task();		
	}
	
	public function load_demo() {
		$settings = get_option('arlo_settings');
		$notice_id = self::$dismissible_notices['newpages'];
		$user = wp_get_current_user();
		update_user_meta($user->ID, $notice_id, 1);
		
		if (empty($settings['platform_name'])) {
			$settings['platform_name'] = 'websitetestdata';
		}
		
		$error = [];
		
		foreach (self::$post_types as $id => $page) {
			//try to find and publish the page
			$args = array(
  				'name' => $id,
  				'post_type' => 'page',
  				'post_status' => array('publish','draft'),
  				'numberposts' => 1
			);
			
			$posts = get_posts($args);
			
			if (!(is_array($posts) && count($posts) == 1)) {
				$args = array(
	  				'name' => $page['name'],
	  				'post_type' => 'page',
	  				'post_status' => array('publish','draft'),
	  				'numberposts' => 1
				);
				
				$posts = get_posts($args);					
			}
							
			if (is_array($posts) && count($posts) == 1) {
				if ($posts[0]->post_status == 'draft') {
					wp_publish_post($posts[0]->ID);
				}
				
				$settings['post_types'][$id]['posts_page'] = $posts[0]->ID;
			} else {
				$error[] = $page['name'];
			} 
		}
		
		update_option('arlo_settings', $settings);
		
		$_SESSION['arlo-demo'] = $error;
		
		$scheduler = $this->get_scheduler();
		$scheduler->set_task("import", -1);
	}
        
    public function GUIDv4 ($trim = true) {
        // Windows
        if (function_exists('com_create_guid') === true) {
            if ($trim === true)
                return trim(com_create_guid(), '{}');
            else
                return com_create_guid();
        }

        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }
    }
    
    public function get_random_int() {
        $guid = explode("-", $this->GUIDv4());
        
        return hexdec($guid[0]);
    }
    
    public function clear_import_lock() {
        global $wpdb;
        $table_name = "{$wpdb->prefix}arlo_import_lock";
      
        $query = $wpdb->query(
            'DELETE FROM  
                ' . $table_name
        );
    }     
    
    public function get_import_lock_entries_number() {
        global $wpdb;
        $table_name = "{$wpdb->prefix}arlo_import_lock";
        
        $sql = '
            SELECT 
                lock_acquired
            FROM
                ' . $table_name . '
            WHERE
                lock_expired > NOW()
            ';
	               
        $wpdb->get_results($sql);
        
        return $wpdb->num_rows;
    }
    
    public function cleanup_import_lock() {
        global $wpdb;
        $table_name = "{$wpdb->prefix}arlo_import_lock";
      
        $wpdb->query(
            'DELETE FROM  
                ' . $table_name . '
            WHERE 
                lock_expired < NOW()
            '
        );
    }
    
    public function add_import_lock($import_id) {
        global $wpdb;
        
        $table_lock = "{$wpdb->prefix}arlo_import_lock";
        $table_log = "{$wpdb->prefix}arlo_import_log";
        
        $query = $wpdb->query(
                'INSERT INTO ' . $table_lock . ' (import_id, lock_acquired, lock_expired)
                SELECT ' . $import_id . ', NOW(), ADDTIME(NOW(), "00:05:00.00") FROM ' . $table_log . ' WHERE (SELECT count(1) FROM ' . $table_lock . ') = 0 LIMIT 1');
                    
        return $query !== false && $query == 1;
    }
    
    public function acquire_import_lock($import_id) {
    	$lock_entries_num = $this->get_import_lock_entries_number();
        if ($lock_entries_num == 0) {
            $this->cleanup_import_lock();
            if ($this->add_import_lock($import_id)) {
                return true;
            }
        } else if ($lock_entries_num == 1) {
        	return $this->check_import_lock($import_id);
        }
        
        return false;
    }
    
    public function check_import_lock($import_id) {
        global $wpdb;
    	$table_name = "{$wpdb->prefix}arlo_import_lock";
        
        $sql = '
            SELECT 
                lock_acquired
            FROM
                ' . $table_name . '
            WHERE
                import_id = ' . $import_id . '
            AND    
                lock_expired > NOW()';
               
        $wpdb->get_results($sql);
        
        if ($wpdb->num_rows == 1) {
            return true;
        }
    
        return false;
    }
        
        	
	public function import($force = false, $task_id = 0) {
		global $wpdb;
		$task_id = intval($task_id);
		$scheduler = $this->get_scheduler();
		
		$now = DateTime::createFromFormat('U.u', microtime(true));
        $timestamp = $now->format("Y-m-d H:i:s");
        $utimestamp = $now->format("Y-m-d H:i:s.u T ");
		
		if ($task_id > 0) {
			$task = $scheduler->get_task_data($task_id);
			if (count($task)) {
				$task = $task[0];
			}
						
			if (empty($task->task_data_text)) {
				// check for last sucessful import. Continue if imported mor than an hour ago or forced. Otherwise, return.
				$last = $this->get_last_import();
		        $import_id = $this->get_random_int();
		                        
		        $this->add_import_log($import_id . ' Synchronization Started: ' . $utimestamp, $timestamp, false);
		        
		        $scheduler->update_task_data($task_id, ['import_id' => $import_id]);
								
				// MV: Untangled the if statements. 
				// If not forced
				if(!$force) {
					// LOG THIS AS AN AUTOMATIC IMPORT
					$this->add_import_log($import_id . ' Synchronization identified as automatic synchronization.', $timestamp, false);
					if(!empty($last)) {
						// LOG THAT A PREVIOUS SUCCESSFUL IMPORT HAS BEEN FOUND
						$this->add_import_log($import_id . ' Previous succesful synchronization found.', $timestamp, false);
						if(strtotime('-1 hour') > strtotime($last)) {
							// LOG THE FACT THAT PREVIOUS SUCCESSFUL IMPORT IS MORE THAN AN HOUR AGO
							$this->add_import_log($import_id . ' Synchronization more than an hour old. Synchronization required.', $timestamp, false);
						}
						else {
							// LOG THE FACT THAT PREVIOUS SUCCESSFUL IMPORT IS MORE THAN AN HOUR AGO
							$this->add_import_log($import_id . ' Synchronization less than an hour old. Synchronization stopped.', $timestamp, false);
							// LOG DATA USED TO DECIDE IMPORT NOT REQUIRED.
							$this->add_import_log($last . '-'  . strtotime($last) . '-' . strtotime('-1 hour') . '-'  . !$force, $timestamp, false);
							return false;
						}
					}
				} 				
			} else {

				$task->task_data_text = json_decode($task->task_data_text);
				if (empty($task->task_data_text->import_id)) {
					return false;
				} else {
					$import_id = $task->task_data_text->import_id;
				}				
			}
		}

		// excessive, but some servers are slow...
		ini_set('max_execution_time', 3000);
		set_time_limit(3000);
		
		// first check valid api_client
		if(!$this->get_api_client()) return false;
                
        //if an import is already running, exit
        if (!$this->acquire_import_lock($import_id)) {
            $this->add_import_log($import_id . ' Synchronization LOCK found, please wait 5 minutes and try again', $timestamp, false, $utimestamp);
            return false;
        }
                
		try {			
			
			$current_subtask = ((!empty($task->task_data_text) && isset($task->task_data_text->finished_subtask)) ? intval($task->task_data_text->finished_subtask) + 1 : 0 );
			$import_tasks = [
				'import_timezones',
				'import_presenters',
				'import_event_templates',
				'import_events',
				'import_onlineactivities',
				'import_venues',
				'import_categories',
				'import_finish',
			];
			
			$import_tasks_desc = [
				'import_timezones' => "Importing time zones",
				'import_presenters' => "Importing presenters",
				'import_event_templates' => "Importing event templates",
				'import_events' => "Importing events",
				'import_onlineactivities' => "Importing online activities",
				'import_venues' => "Importing venues",
				'import_categories' => "Importing categories",
				'import_finish' => "Finalize the import",
			];
			
			$task = $import_tasks[$current_subtask];
			
			if (!empty($task)) {
			
				$scheduler->update_task($task_id, 1, "Import is running: task " . ($current_subtask + 1) . "/" . count($import_tasks) . ": " . $import_tasks_desc[$task]);
				call_user_func(array('Arlo_For_Wordpress', $task), $import_id);
				$scheduler-> update_task_data($task_id, ['finished_subtask' => $current_subtask]);
								
				if ($current_subtask + 1 == count($import_tasks)) {
					//finish task
					$scheduler->update_task($task_id, 4, "Import finished");
				} else {
					//kick off next
					$scheduler->update_task($task_id, 1);
					
					wp_schedule_single_event(time(), 'arlo_scheduler', ['fake_data' => mt_rand(1000,9999)]);
					sleep(2);
					$this->call_wp_cron();
				}
			} else {
				$scheduler->update_task($task_id, 4, "Import finished");
			}
		} catch(\Exception $e) {
			$wpdb->query('ROLLBACK');	
			
			$this->add_import_log($import_id . ' Synchronization failed: ' . $e->getMessage(), $timestamp, false);
			
			$this->clear_import_lock();
			
			return false;
		}
					
		// flush the rewrite rules
		flush_rewrite_rules(true);	
      	wp_cache_flush();
        
        $this->clear_import_lock();                
		
		return true;
	}
	
	private function import_event_templates($timestamp) {
		global $wpdb;
		
		$regions = get_option('arlo_regions');
		
		if (!(is_array($regions))) {
			$regions = [];
		}
			
		$client = $this->get_api_client();
		
		$regionalized_items = $client->EventTemplateSearch()->getAllEventTemplates(
			array(
				'TemplateID',
				'Code',
				'Name',
				'Description',
				'AdvertisedPresenters',
				'BestAdvertisedOffers',
				'ViewUri',
				'RegisterInterestUri',
				'Categories',
				'Tags',
			), 
			array_keys($regions)
		);
		
		$table_name = "{$wpdb->prefix}arlo_eventtemplates";
				
		if(!empty($regionalized_items)) {
				
			// lets put it all in a transaction
			$wpdb->query('START TRANSACTION');
				
			foreach($regionalized_items as $region => $items) {
			
				foreach ($items as $item) {
					$slug = sanitize_title($item->TemplateID . ' ' . $item->Name);
					$query = $wpdb->query(
						$wpdb->prepare( 
							"INSERT INTO $table_name 
							(et_arlo_id, et_code, et_name, et_descriptionsummary, et_post_name, active, et_registerinteresturi, et_viewuri, et_region) 
							VALUES ( %d, %s, %s, %s, %s, %s, %s, %s, %s) 
							", 
						    $item->TemplateID,
							@$item->Code,
							$item->Name,
							@$item->Description->Summary,
							$slug,
							$timestamp,
							!empty($item->RegisterInterestUri) ? $item->RegisterInterestUri : '',
							!empty($item->ViewUri) ? $item->ViewUri : '',
							(!empty($region) ? $region : '')
						)
					);
	                                
	                if ($query === false) {
	                    throw new Exception('Database insert failed: ' . $table_name);
	                }
					
					$this->save_tags($item->Tags, $template_event_id, 'template', $timestamp);
										
					$content = '';
					if (!empty($item->Description->Summary)) {
						$content = $item->Description->Summary;
					}
					
					// create associated custom post, if it dosen't exist
					$post_config_array = array(
						'post_title'    => $item->Name,
						'post_content'  => $content,
						'post_status'   => 'publish',
						'post_author'   => 1,
						'post_type'		=> 'arlo_event',
						'post_name'		=> $slug
					);					
					
					$post = arlo_get_post_by_name($slug, 'arlo_event');
					
					if(!$post) {					
						wp_insert_post($post_config_array, true);						
					} else {
						wp_update_post($post_config_array);				
	  				}
	  									
					// need to insert associated data here
					// advertised offers
					if(isset($item->BestAdvertisedOffers) && !empty($item->BestAdvertisedOffers)) {
						$this->save_advertised_offer($item->BestAdvertisedOffers, $timestamp, $region, $template_event_id);
					}	
					
					// content fields
					if(isset($item->Description->ContentFields) && !empty($item->Description->ContentFields)) {
						foreach($item->Description->ContentFields as $index => $content) {
							$query = $wpdb->query( $wpdb->prepare( 
								"INSERT INTO {$wpdb->prefix}arlo_contentfields 
								(et_id, cf_fieldname, cf_text, cf_order, e_contenttype, active) 
								VALUES ( %d, %s, %s, %s, %s, %s ) 
								", 
							    $template_event_id,
								@$content->FieldName,
								@$content->Content->Text,
								$index,
								@$content->Content->ContentType,
								$timestamp
							) );
	                        if ($query === false) {
	                            throw new Exception('Database insert failed: ' . $table_name);
	                        }
						}
					}
				
					// prsenters
					if(isset($item->AdvertisedPresenters) && !empty($item->AdvertisedPresenters)) {
						foreach($item->AdvertisedPresenters as $index => $presenter) {
							$query = $wpdb->query( $wpdb->prepare( 
								"REPLACE INTO {$wpdb->prefix}arlo_eventtemplates_presenters 
								(et_arlo_id, p_arlo_id, p_order, active) 
								VALUES ( %d, %d, %d, %s ) 
								", 
							    $item->TemplateID,
							    $presenter->PresenterID,
							    $index,
							    $timestamp
							) );
	                                                        
	                        if ($query === false) {
	                            throw new Exception('Database insert failed: ' . $table_name);
	                        }
						}
					}
					
					// categories
					if(isset($item->Categories) && !empty($item->Categories)) {
						foreach($item->Categories as $index => $category) {
							$query = $wpdb->query( $wpdb->prepare( 
								"REPLACE INTO {$wpdb->prefix}arlo_eventtemplates_categories 
								(et_arlo_id, c_arlo_id, active) 
								VALUES ( %d, %d, %s ) 
								", 
							    $item->TemplateID,
							    $category->CategoryID,
							    $timestamp
							) );
	                                                        
	                        if ($query === false) {
	                            throw new Exception('Database insert failed: ' . $table_name);
	                        }
						}
					}
				}
			}
			
			$wpdb->query('COMMIT');
		}
		
		return $items;
	}
	
	private function save_tags($tags, $id, $type = '', $timestamp) {
		global $wpdb;
		
		switch ($type) {
			case "template":
				$field = "et_arlo_id";
				$table_name = $wpdb->prefix . "arlo_eventtemplates_tags";			
			break;		
			case "event":
				$field = "e_arlo_id";
				$table_name = $wpdb->prefix . "arlo_events_tags";			
			break;
			case "oa":
				$field = "oa_id";
				$table_name = $wpdb->prefix . "arlo_onlineactivities_tags";
			break;			
			default: 
				throw new Exception('Tag type failed: ' . $type);
			break;		
		}
		
		if (isset($tags) && is_array($tags)) {
			$exisiting_tags = [];
			$sql = "
			SELECT 
				id, 
				tag
			FROM
				{$wpdb->prefix}arlo_tags 
			WHERE 
				tag IN ('" . implode("', '", $tags) . "')
			AND
				active = '{$timestamp}'
			";
			$rows = $wpdb->get_results($sql, ARRAY_A);
			foreach ($rows as $row) {
				$exisiting_tags[$row['tag']] = $row['id'];
			}
			unset($rows);
			
			foreach ($tags as $tag) {
				if (empty($exisiting_tags[$tag])) {
					$query = $wpdb->query( $wpdb->prepare( 
						"INSERT INTO {$wpdb->prefix}arlo_tags
						(tag, active) 
						VALUES ( %s, %s ) 
						", 
						$tag,
						$timestamp
					) );
												
					if ($query === false) {
						throw new Exception('Database insert failed: ' . $wpdb->prefix . 'arlo_tags ' . $type );
					} else {
						$exisiting_tags[$tag] = $wpdb->insert_id;
					}
				}
										
				if (!empty($exisiting_tags[$tag])) {
					$query = $wpdb->query( $wpdb->prepare( 
						"REPLACE INTO {$table_name}
						(" . $field . ", tag_id, active) 
						VALUES ( %d, %d, %s ) 
						", 
						$id,
						$exisiting_tags[$tag],
						$timestamp
					) );
					
					if ($query === false) {
						throw new Exception('Database insert failed: ' . $table_name );
					}
				} else {
					throw new Exception('Couldn\'t find tag: ' . $tag );
				}
			}
		}		
	
	}
	
	private function save_advertised_offer($advertised_offer, $timestamp, $region = '', $template_id = null, $event_id = null, $oa_id = null) {
		global $wpdb;
		
		if(isset($advertised_offer) && !empty($advertised_offer)) {
			$template_id = (intval($template_id) > 0 ? $template_id : null);
			$event_id = (intval($event_id) > 0 ? $event_id : null);
			$oa_id = (intval($oa_id) > 0 ? $oa_id : null);
		
			$offers = array_reverse($advertised_offer);
			foreach($offers as $key => $offer) {
				$query = $wpdb->query( $wpdb->prepare( 
					"INSERT INTO {$wpdb->prefix}arlo_offers 
					(o_arlo_id, et_id, e_id, oa_id, o_label, o_isdiscountoffer, o_currencycode, o_offeramounttaxexclusive, o_offeramounttaxinclusive, o_formattedamounttaxexclusive, o_formattedamounttaxinclusive, o_taxrateshortcode, o_taxratename, o_taxratepercentage, o_message, o_order, o_replaces, o_region, active) 
					VALUES ( %d, %d, %d, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s, %s, %s ) 
					", 
					$offer->OfferID+1,
				    $template_id,
					$event_id,
					$oa_id,
					@$offer->Label,
					@$offer->IsDiscountOffer,
					@$offer->OfferAmount->CurrencyCode,
					@$offer->OfferAmount->AmountTaxExclusive,
					@$offer->OfferAmount->AmountTaxInclusive,
					@$offer->OfferAmount->FormattedAmountTaxExclusive,
					@$offer->OfferAmount->FormattedAmountTaxInclusive,
					@$offer->OfferAmount->TaxRate->ShortName,
					@$offer->OfferAmount->TaxRate->Name,
					@$offer->OfferAmount->TaxRate->RatePercent,
					@$offer->Message,
					$key+1,
					(isset($offer->ReplacesOfferID)) ? $offer->ReplacesOfferID+1 : null,
					(!empty($region) ? $region : 'NULL'),
					$timestamp
				) );
				
				if ($query === false) {
					throw new Exception('Database insert failed: ' . $wpdb->prefix . 'arlo_offers');
				}
			}
		}	
	
	
	}
	
	private function save_event_data($item = array(), $parent_id = 0, $timestamp, $region = '') {
		global $wpdb;
		
		$table_name = "{$wpdb->prefix}arlo_events";
		
		$query = $wpdb->query(
			$wpdb->prepare( 
				"INSERT INTO $table_name 
				(e_arlo_id, et_arlo_id, e_parent_arlo_id, e_code, e_name, e_startdatetime, e_finishdatetime, e_datetimeoffset, e_timezone, e_timezone_id, v_id, e_locationname, e_locationroomname, e_locationvisible , e_isfull, e_placesremaining, e_summary, e_sessiondescription, e_notice, e_viewuri, e_registermessage, e_registeruri, e_providerorganisation, e_providerwebsite, e_isonline, e_region, active) 
				VALUES ( %d, %d, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s ) 
				", 
			    $item->EventID,
				$item->EventTemplateID,
				$parent_id,
				@$item->Code,
				$item->Name,
				substr(@$item->StartDateTime,0,26),
				substr(@$item->EndDateTime,0,26),
				substr(@$item->StartDateTime,27,6),
				@$item->TimeZone,
				@$item->TimeZoneID,
				@$item->Location->VenueID,
				@$item->Location->Name,
				@$item->Location->VenueRoomName,
				(!empty($item->Location->ViewUri) ? 1 : 0 ),
				@$item->IsFull,
				@$item->PlacesRemaining,
				@$item->Summary,
				@$item->SessionsDescription,
				@$item->Notice,
				@$item->ViewUri,
				@$item->RegistrationInfo->RegisterMessage,
				@$item->RegistrationInfo->RegisterUri,
				@$item->Provider->Name,
				@$item->Provider->WebsiteUri,
				@$item->Location->IsOnline,
				(!empty($region) ? $region : 'NULL'),
				$timestamp
			)
		);
                        
		if ($query === false) {					
			throw new Exception('Database insert failed: ' . $table_name);
		}	
		
		$event_id = $wpdb->insert_id;
		
		if(isset($item->AdvertisedOffers) && !empty($item->AdvertisedOffers)) {
			$this->save_advertised_offer($item->AdvertisedOffers, $timestamp, $region, null, $event_id);
		}
		
		// prsenters
		if(isset($item->Presenters) && !empty($item->Presenters)) {
			foreach($item->Presenters as $index => $presenter) {
				$query = $wpdb->query( $wpdb->prepare( 
					"REPLACE INTO {$wpdb->prefix}arlo_events_presenters 
					(e_arlo_id, p_arlo_id, p_order, active) 
					VALUES ( %d, %d, %d, %s ) 
					", 
				    $item->EventID,
				    $presenter->PresenterID,
				    $index,
				    $timestamp
				) );
				
				if ($query === false) {
					throw new Exception('Database insert failed: ' . $wpdb->prefix . 'arlo_events_presenters');
				}
			}
		}
		
		//Save session information
		if ($parent_id == 0 && isset($item->Sessions) && is_array($item->Sessions) && !empty($item->Sessions[0]->EventID) && $item->Sessions[0]->EventID != $item->EventID ) {
			foreach ($item->Sessions as $session) {
				$this->save_event_data($session, $item->EventID, $timestamp, $region);
			}
		}	
		
		return $event_id;
	}
	
	private function import_events($timestamp) {
		global $wpdb;
		
		$regions = get_option('arlo_regions');
		
		if (!(is_array($regions))) {
			$regions = [];
		}		
	
		$client = $this->get_api_client();
		
		$regionalized_items = $client->EventSearch()->getAllEvents(
			array(
				'EventID',
				'EventTemplateID',
				'Name',
				'Code',
				'Summary',
				'Description',
				'StartDateTime',
				'EndDateTime',
				'TimeZoneID',
				'TimeZone',
				'Location',
				'IsFull',
				'PlacesRemaining',
				'AdvertisedOffers',
				'SessionsDescription',
				'Presenters',
				'Notice',
				'ViewUri',
				'RegistrationInfo',
				'Provider',
				'TemplateCode',
				'Tags',
				'Sessions'
			), 
			array_keys($regions)
		);
						
		if(!empty($regionalized_items)) {
			
			$wpdb->query('START TRANSACTION');
				
			foreach($regionalized_items as $region => $items) {
			
				foreach($items as $item) {
					if (!empty($item->EventID) && is_numeric($item->EventID) && $item->EventID > 0) {
						$event_id = $this->save_event_data($item, 0, $timestamp, $region);
						
						$this->save_tags($item->Tags, $item->EventID, 'event', $timestamp);
					}
				}				
			}
			
			$wpdb->query('COMMIT');
		}
		
		return $items;
	}	
	
	private function import_onlineactivities($timestamp) {
		global $wpdb;
		
		$regions = get_option('arlo_regions');
		
		if (!(is_array($regions))) {
			$regions = [];
		}		
	
		$client = $this->get_api_client();
		
		$regionalized_items = $client->OnlineActivitySearch()->getAllOnlineActivities(
			array(
				'OnlineActivityID',
				'TemplateID',
				'Name',
				'Code',
				'DeliveryDescription',
				'ViewUri',
				'ReferenceTerms',
				'Credits',
				'RegistrationInfo',
				'AdvertisedOffers',
				'Tags'
			), 
			array_keys($regions)
		);
										
		if(!empty($regionalized_items)) {
			
			$wpdb->query('START TRANSACTION');
				
			foreach($regionalized_items as $region => $items) {
			
				foreach($items as $item) {
					//TODO: This field has to be an integer, but the API returns a wrong value!!!
					if (!empty($item->OnlineActivityID) /* && is_numeric($item->OnlineActivityID) && $item->OnlineActivityID > 0*/) {
						
						$table_name = "{$wpdb->prefix}arlo_onlineactivities";
						
						$query = $wpdb->query(
							$wpdb->prepare( 
								"INSERT INTO $table_name 
								(oa_arlo_id, oat_arlo_id, oa_code, oa_name, oa_delivery_description, oa_viewuri, oa_reference_terms, oa_credits, oa_registermessage, oa_registeruri, oa_region, active) 
								VALUES ( %s, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) 
								", 
							    $item->OnlineActivityID,
								$item->TemplateID,
								@$item->Code,
								$item->Name,
								@$item->DeliveryDescription,
								$item->ViewUri,
								json_encode($item->ReferenceTerms),
								json_encode($item->Credits),
								@$item->RegistrationInfo->RegisterMessage,
								@$item->RegistrationInfo->RegisterUri,
								(!empty($region) ? $region : 'NULL'),
								$timestamp
							)
						);
				                        
						if ($query === false) {					
							throw new Exception('Database insert failed: ' . $table_name);
						}	
						
						$oa_id = $wpdb->insert_id;	
						
						$this->save_tags($item->Tags, $oa_id, 'oa', $timestamp);						
						
						if(isset($item->AdvertisedOffers) && !empty($item->AdvertisedOffers)) {
							$this->save_advertised_offer($item->AdvertisedOffers, $timestamp, $region, null, null, $oa_id);
						}
					}
				}				
			}
			
			$wpdb->query('COMMIT');
		}
		
		return $items;
	}
	
	
	private function import_timezones($timestamp) {
		global $wpdb;
	
		$client = $this->get_api_client();
		
		$items = $client->Timezones()->getAllTimezones();
				
		$table_name = "{$wpdb->prefix}arlo_timezones";
		
		if(!empty($items)) {
		
			$wpdb->query('START TRANSACTION');
		
			foreach($items as $item) {
				$query = $wpdb->replace(
					$table_name,
					array(
						'id' => $item->TimeZoneID,
						'name' => $item->Name,
						'active' => $timestamp
					),
					array(
						'%d', '%s', '%s'
					)
				);
                                
				if ($query === false) {
					throw new Exception('Database insert failed: ' . $table_name);
				} else {
					if (is_array($item->TzNames)) {
						foreach ($item->TzNames as $TzName) {
							$query = $wpdb->replace(
								$table_name . '_olson',
								array(
									'timezone_id' => $item->TimeZoneID,
									'olson_name' => $TzName,
									'active' => $timestamp
								),
								array(
									'%d', '%s', '%s'
								)
							);

						} 
					}
				}			
			}
			
			$wpdb->query('COMMIT');	
		}
		
		return $items;
	}	
	
	private function import_presenters($timestamp) {
		global $wpdb;
	
		$client = $this->get_api_client();
		
		$items = $client->Presenters()->getAllPresenters(
			array(
				'PresenterID',
				'FirstName',
				'LastName',
				'ViewUri',
				'Profile',
				'SocialNetworkInfo',
			)
		);
		
		$table_name = "{$wpdb->prefix}arlo_presenters";
				
		if(!empty($items)) {
		
			$wpdb->query('START TRANSACTION');
		
			foreach($items as $item) {
				$slug = sanitize_title($item->PresenterID . ' ' . $item->FirstName . ' ' . $item->LastName);
				$query = $wpdb->query( $wpdb->prepare( 
					"INSERT INTO $table_name 
					(p_arlo_id, p_firstname, p_lastname, p_viewuri, p_profile, p_qualifications, p_interests, p_twitterid, p_facebookid, p_linkedinid, p_post_name, active) 
					VALUES ( %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s ) 
					", 
				    $item->PresenterID,
					$item->FirstName,
					$item->LastName,
					@$item->ViewUri,
					@$item->Profile->ProfessionalProfile->Text,
					@$item->Profile->Qualifications->Text,
					@$item->Profile->Interests->Text,
					@$item->SocialNetworkInfo->TwitterID,
					@$item->SocialNetworkInfo->FacebookID,
					@$item->SocialNetworkInfo->LinkedInID,
					$slug,
					$timestamp
				) );
                                
                if ($query === false) {
                    throw new Exception('Database insert failed: ' . $table_name);
                }
				
				$name = $item->FirstName . ' ' . $item->LastName;
				
				// create associated custom post, if it dosen't exist
				if(!arlo_get_post_by_name($slug, 'arlo_presenter')) {
					wp_insert_post(array(
						'post_title'    => $name,
						'post_content'  => '',
						'post_status'   => 'publish',
						'post_author'   => 1,
						'post_type'		=> 'arlo_presenter',
						'post_name'		=> $slug
					));
				}
			}
			
			$wpdb->query('COMMIT');
		}
		
		return $items;
	}
	
	private function import_venues($timestamp) {
		global $wpdb;
	
		$client = $this->get_api_client();
		
		$items = $client->Venues()->getAllVenues(
			array(
				'VenueID',
				'Name',
				'GeoData',
				'PhysicalAddress',
				'FacilityInfo',
				'ViewUri'
			)
		);
		
		$table_name = "{$wpdb->prefix}arlo_venues";
		
		if(!empty($items)) {
		
			$wpdb->query('START TRANSACTION');
		
			foreach($items as $item) {
				$slug = sanitize_title($item->VenueID . ' ' . $item->Name);
				$query = $wpdb->query( $wpdb->prepare( 
					"INSERT INTO $table_name 
					(v_arlo_id, v_name, v_geodatapointlatitude, v_geodatapointlongitude, v_physicaladdressline1, v_physicaladdressline2, v_physicaladdressline3, v_physicaladdressline4, v_physicaladdresssuburb, v_physicaladdresscity, v_physicaladdressstate, v_physicaladdresspostcode, v_physicaladdresscountry, v_viewuri, v_facilityinfodirections, v_facilityinfoparking, v_post_name, active) 
					VALUES ( %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )
					", 
				    $item->VenueID,
					$item->Name,
					@$item->GeoData->PointLatitude,
					@$item->GeoData->PointLongitude,
					@$item->PhysicalAddress->StreetLine1,
					@$item->PhysicalAddress->StreetLine2,
					@$item->PhysicalAddress->StreetLine3,
					@$item->PhysicalAddress->StreetLine4,
					@$item->PhysicalAddress->Suburb,
					@$item->PhysicalAddress->City,
					@$item->PhysicalAddress->State,
					@$item->PhysicalAddress->PostCode,
					@$item->PhysicalAddress->Country,
					@$item->ViewUri,
					@$item->FacilityInfo->Directions->Text,
					@$item->FacilityInfo->Parking->Text,
					$slug,
					$timestamp
				) );
                                
                if ($query === false) {
                    throw new Exception('Database insert failed: ' . $table_name);
                }
                                
				// create associated custom post, if it dosen't exist
				// should be arlo_venues
				if(!arlo_get_post_by_name($slug, 'arlo_venue')) {
					wp_insert_post(array(
						'post_title'    => $item->Name,
						'post_content'  => '',
						'post_status'   => 'publish',
						'post_author'   => 1,
						'post_type'		=> 'arlo_venue',
						'post_name'		=> $slug
					));
				}
			}
			
			$wpdb->query('COMMIT');
		}
		
		return $items;
	}
	
	private function import_categories($timestamp) {
		global $wpdb;
	
		$client = $this->get_api_client();
		
		$items = $client->Categories()->getAllCategories(
			array(
				'CategoryID',
				'ParentCategoryID',
				'Name',
				'SequenceIndex',
				'Description',
				'Footer',
			)
		);
		
		$table_name = "{$wpdb->prefix}arlo_categories";
		
		$wpdb->query('START TRANSACTION');
		
		if(!empty($items)) {
			foreach($items as $item) {
				$slug = sanitize_title($item->CategoryID . ' ' . $item->Name);
				$query = $wpdb->query( $wpdb->prepare( 
					"REPLACE INTO $table_name 
					(c_arlo_id, c_name, c_slug, c_header, c_footer, c_order, c_parent_id, active) 
					VALUES ( %d, %s, %s, %s, %s, %d, %d, %s ) 
					", 
				    $item->CategoryID,
					$item->Name,
					$slug,
					@$item->Description->Text,
					@$item->Footer->Text,
					@$item->SequenceIndex,
					@$item->ParentCategoryID,
					$timestamp
				) );
                                
                if ($query === false) {
                    throw new Exception('Database insert failed: ' . $table_name);
                }               
			}
		}
		
		$this->import_eventtemplatescategoriesitems($timestamp);
		
		//count the templates in the categories
		$sql = "
		SELECT
			COUNT(1) AS num,  
			c_arlo_id
		FROM
			{$wpdb->prefix}arlo_eventtemplates_categories
		WHERE
			active = '{$timestamp}'
		GROUP BY
			c_arlo_id
		";

		$items = $wpdb->get_results($sql, ARRAY_A);
		if (!is_null($items)) {
			foreach ($items as $counts) {
				$sql = "
				UPDATE
					{$wpdb->prefix}arlo_categories
				SET
					c_template_num = %d
				WHERE
					c_arlo_id = %d
				";
				$query = $wpdb->query( $wpdb->prepare($sql, $counts['num'], $counts['c_arlo_id']) );
				
		        if ($query === false) {
		        	throw new Exception('Database insert failed: ' . $table_name);
		        }
			}		
		}
		
		$this->set_categories_count(0, $timestamp);
		
		$cats = \Arlo\Categories::getTree(0, null);
		$this->set_category_depth_level($cats, $timestamp);
		
		$sql = "SELECT MAX(c_depth_level) FROM {$wpdb->prefix}arlo_categories WHERE active = '{$timestamp}'";
		$max_depth = $wpdb->get_var($sql);
		
		$this->set_category_depth_order($cats, $max_depth, 0, $timestamp);
		
		$wpdb->query('COMMIT');
		
		return $items;
	}
	
	private function set_categories_count($cat_id, $timestamp) {
		global $wpdb;
		$cat_id = intval($cat_id);
		
		$where = "
		c_arlo_id = {$cat_id}
		";
		
		if ($cat_id == 0) {
			$where = "
			(
			SELECT 
				COUNT(1) 
			FROM 
				{$wpdb->prefix}arlo_categories
			WHERE 
				wpc.c_arlo_id = wp_arlo_categories.c_parent_id
			AND
				active = '{$timestamp}'
			) = 0			
			";
		}
		
		$sql = "
		SELECT 
			c_arlo_id,
			c_template_num,
			c_parent_id
		FROM 
			{$wpdb->prefix}arlo_categories AS wpc
		WHERE 
			{$where}
		AND
			active = '{$timestamp}'
		";

		$cats = $wpdb->get_results($sql, ARRAY_A);
		if (!is_null($cats)) {
			foreach ($cats as $cat) {
				if ($cat['c_parent_id'] > 0) {
					$sql = "
					UPDATE 
						{$wpdb->prefix}arlo_categories
					SET 
						c_template_num = c_template_num + %d
					WHERE
						c_arlo_id = %d
					AND
						active = '%s'
					";
					$query = $wpdb->query( $wpdb->prepare($sql, $cat['c_template_num'], $cat['c_parent_id'], $timestamp) );
					$this->set_categories_count($cat['c_parent_id'], $timestamp);
				}
			}		
		}
	}
	
	private function set_category_depth_level($cats, $timestamp) {
		global $wpdb;
		
		foreach ($cats as $cat) {
			$sql = "
			UPDATE 
				{$wpdb->prefix}arlo_categories
			SET 
				c_depth_level = %d
			WHERE
				c_arlo_id = %d
			AND
				active = '%s'
			";
			$query = $wpdb->query( $wpdb->prepare($sql, $cat->depth_level, $cat->c_arlo_id, $timestamp) );
			if (is_array($cat->children)) {
				$this->set_category_depth_level($cat->children, $timestamp);
			}
		}
	}
	
	private function set_category_depth_order($cats, $max_depth, $parent_order = 0, $timestamp) {
		global $wpdb;
		$num = 100;
		
		foreach ($cats as $index => $cat) {		
			$order = $parent_order + pow($num, $max_depth - $cat->depth_level) * ($index + 1);

			$sql = "
			UPDATE
				{$wpdb->prefix}arlo_categories
			SET
				c_order = %d
			WHERE
				c_arlo_id = %d
			AND
				active = '%s'	
			";
			
			$query = $wpdb->query( $wpdb->prepare($sql, $order + $cat->c_order, $cat->c_arlo_id, $timestamp) );
			if ($query === false) {
				throw new Exception('Database update failed in set_category_depth_order()');
			} else if (is_array($cat->children)) {
				$this->set_category_depth_order($cat->children, $max_depth, $order, $timestamp);
			}
		}
	}	
		
	private function import_eventtemplatescategoriesitems($timestamp) {
		global $wpdb;
	
		$client = $this->get_api_client();
		
		$sql = "
		SELECT 
			c_arlo_id
		FROM
			{$wpdb->prefix}arlo_categories
		WHERE 
			active = '{$timestamp}'
		";
		$category_ids = $wpdb->get_results($sql, ARRAY_A);
		$category_ids = array_map(function($item) {
			return $item['c_arlo_id'];
		}, $category_ids);
		
		$items = $client->EventTemplateCategoryItems()->getAllTemplateCategoriesItems(
			array(
				'CategoryID',
				'EventTemplateID',
				'SequenceIndex',
			),
			$category_ids
		);
		
		$table_name = "{$wpdb->prefix}arlo_eventtemplates_categories";
		
		if(!empty($items)) {
		
			$wpdb->query('START TRANSACTION');
		
			foreach($items as $item) {
				$sql = "
				UPDATE
					{$table_name}
				SET
					et_order = %d
				WHERE
					et_arlo_id = %d
				AND
					c_arlo_id = %d
				";
								
				$query = $wpdb->query( $wpdb->prepare($sql, !empty($item->SequenceIndex) ? $item->SequenceIndex : 0, $item->EventTemplateID, $item->CategoryID) );
				
		        if ($query === false) {
		        	throw new Exception('Database insert failed: ' . $table_name);
		        }
			}
			
			$wpdb->query('COMMIT');
		}
	}
		
	private function import_cleanup($import_id) {
		global $wpdb;
		
		// need to delete posts and join rows no longer needed
   		$now = DateTime::createFromFormat('U.u', microtime(true));
        $timestamp = $now->format("Y-m-d H:i:s");
        $utimestamp = $now->format("Y-m-d H:i:s.u T ");	
        
        $wpdb->query('START TRANSACTION');	
		
		$tables = array(
			'eventtemplates',
			'contentfields',
			'offers',
			'events',
			'events_tags',
			'tags',
			'presenters',
			'venues',
			'categories',
			'onlineactivities',
			'onlineactivities_tags',
            'events_presenters',
            'eventtemplates_categories',
            'eventtemplates_presenters',
            'eventtemplates_tags',
            'timezones',
            'timezones_olson'
		);
                		
		foreach($tables as $table) {
			$table = $wpdb->prefix . 'arlo_' . $table;
			$wpdb->query($wpdb->prepare("DELETE FROM $table WHERE active <> %s", $import_id));
		}   

		$this->add_import_log($import_id . ' Database cleanup: ' . $utimestamp, $timestamp, false);
        
        // delete unneeded custom posts
        $this->delete_custom_posts('eventtemplates','et_post_name','event');

        $this->delete_custom_posts('presenters','p_post_name','presenter');

        $this->delete_custom_posts('venues','v_post_name','venue');        
        
   		$now = DateTime::createFromFormat('U.u', microtime(true));
        $timestamp = $now->format("Y-m-d H:i:s");
        $utimestamp = $now->format("Y-m-d H:i:s.u T ");        
        
		$this->add_import_log($import_id . ' Posts cleanup: ' . $utimestamp, $timestamp, false);        
		
		$wpdb->query('COMMIT');
	}

	private function delete_custom_posts($table, $column, $post_type) {
		global $wpdb;

		$table = $wpdb->prefix . 'arlo_' . $table;
		$items = $wpdb->get_results("SELECT $column FROM $table", ARRAY_A);

		$post_names = array();
		foreach($items as $item) {
			$post_names[] = $item[$column];
		}

		$args = array(
			'post_type' => 'arlo_' . $post_type,
			'posts_per_page' => -1
		);

		$posts = get_posts($args);

		if(!empty($posts)) {
			foreach($posts as $post) {
				if(!in_array($post->post_name, $post_names)) {
					wp_delete_post( $post->ID, true );
				}
			}
		}

	}
	
	private function import_finish($import_id) {
		global $wpdb;
		
        $now = DateTime::createFromFormat('U.u', microtime(true));
        $timestamp = $now->format("Y-m-d H:i:s");
        $utimestamp = $now->format("Y-m-d H:i:s.u T ");
        			
        if ($this->get_import_lock_entries_number() == 1 && $this->check_import_lock($import_id)) {
            //clean up the old entries
			$this->import_cleanup($import_id);        
        
            // update logs
            $this->add_import_log($import_id . ' Synchronization successful', $timestamp, true, $utimestamp);            
			
	        //set import id
	        $this->set_import_id($import_id);
        } else {
            $this->add_import_log($import_id . ' Synchronization died because of a database LOCK, please wait 5 minutes and try again.', $timestamp, true, $utimestamp);
        }
	}	
	
	public function add_cron_schedules($schedules) {
		$schedules = [
			'minutes_15' => [
				'interval' => 900,
				'display' => __('Once every 15 minutes')
				],
			'hourly' => [
				'interval' => 3600,
				'display' => __('Once every hour')
				]
			];
		return $schedules;
	}
	
	public static function redirect_proxy() {
		$settings = get_option('arlo_settings');
		
		if(!isset($_GET['object_post_type']) || !isset($_GET['arlo_id'])) return;
		
		switch($_GET['object_post_type']) {
			case 'event':
				//check if it's a private event				
				if (!empty($_GET['e']) || !empty($_GET['t']) && !empty($settings['platform_name'])) {
					$url = 'http://' . $settings['platform_name'] . '.arlo.co/events/' . intval($_GET['arlo_id']) . '-fake-redirect-url?';
					$url .= (!empty($_GET['e']) ? 'e=' . $_GET['e'] : '');
					$url .= (!empty($_GET['t']) ? 't=' . $_GET['t'] : '');
					
					$location = $url;
				} else {
					$event = \Arlo\EventTemplates::get(array('id' => $_GET['arlo_id']), array(), 1);
					
					if(!$event) return;
					
					$post = arlo_get_post_by_name($event->et_post_name, 'arlo_event');
					
					if(!$post) return;
					
					$location = get_permalink($post->ID);					
				}
			break;
			
			case 'venue':
				$venue = \Arlo\Venues::get(array('id' => $_GET['arlo_id']), array(), 1);
				
				if(!$venue) return;
				
				$post = arlo_get_post_by_name($venue->v_post_name, 'arlo_venue');
				
				if(!$post) return;
				
				$location = get_permalink($post->ID);
			break;
			
			case 'presenter':
				$presenter = \Arlo\Presenters::get(array('id' => $_GET['arlo_id']), array(), 1);
				
				if(!$presenter) return;
				
				$post = arlo_get_post_by_name($presenter->p_post_name, 'arlo_presenter');
				
				if(!$post) return;
				
				$location = get_permalink($post->ID);
			break;
			
			default:
				return;
			break;
		}
		
		wp_redirect( $location, 301 );
		exit;
	}
		
	public static function load_demo_notice($error = []) {
		global $wpdb;
		$settings = get_option('arlo_settings');
		$timestamp = get_option('arlo_last_import');
		
		$events = arlo_get_post_by_name('events', 'page');
		$upcoming = arlo_get_post_by_name('upcoming', 'page');
		$presenters = arlo_get_post_by_name('presenters', 'page');
		$venues = arlo_get_post_by_name('venues', 'page');
		
		$notice_id = self::$dismissible_notices['newpages'];
		$user = wp_get_current_user();
		$meta = get_user_meta($user->ID, $notice_id, true);
				
		if (count($error)) {
			echo '
				<div class="' . (count($error) ? "error" : "") . ' notice is-dismissible" id="' . $notice_id . '">
		        	<p>' . sprintf(__('Couldn\'t set the following post types: %s', self::get_instance()->plugin_slug), implode(', ', $error)) . '</p>
		        	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
			 	</div>';
		} else {
			if ($meta !== '0') {
				$import = self::get_instance()->get_import_log();
				
				if (!empty($settings['platform_name']) && $events !== false && $upcoming !== false && $presenters !== false && $venues !== false) {		
					//Get the first event template wich has event
					$sql = "
					SELECT 
						ID
					FROM
						{$wpdb->prefix}arlo_events AS e
					LEFT JOIN 		
						{$wpdb->prefix}arlo_eventtemplates AS et		
					ON
						e.et_arlo_id = et.et_arlo_id
					AND
						e.active = '{$timestamp}'
					LEFT JOIN
						{$wpdb->prefix}posts
					ON
						et_post_name = post_name		
					AND
						post_status = 'publish'
					WHERE 
						et.active = '{$timestamp}'
					LIMIT 
						1
					";

					$event = $wpdb->get_results($sql, ARRAY_A);
					$event_link = '';
					if (count($event)) {
						$event_link = sprintf('<a href="%s" target="_blank">%s</a>,',
						get_post_permalink($event[0]['ID']),
						__('Event', self::get_instance()->plugin_slug));
					}					
					
					//Get the first presenter
					$sql = "
					SELECT 
						ID
					FROM
						{$wpdb->prefix}arlo_presenters AS p
					LEFT JOIN
						{$wpdb->prefix}posts
					ON
						p_post_name = post_name		
					AND
						post_status = 'publish'
					WHERE 
						p.active = '{$timestamp}'
					LIMIT 
						1
					";
					$presenter = $wpdb->get_results($sql, ARRAY_A);		
					$presenter_link = '';
					if (count($event)) {
						$presenter_link = sprintf('<a href="%s" target="_blank">%s</a>,',
						get_post_permalink($presenter[0]['ID']),
						__('Presenter profile', self::get_instance()->plugin_slug));
					}					
					
					//Get the first venue
					$sql = "
					SELECT 
						ID
					FROM
						{$wpdb->prefix}arlo_venues AS v
					LEFT JOIN
						{$wpdb->prefix}posts
					ON
						v_post_name = post_name		
					AND
						post_status = 'publish'
					WHERE 
						v.active = '{$timestamp}'
					LIMIT 
						1
					";
					$venue = $wpdb->get_results($sql, ARRAY_A);							
					$venue_link = '';
					if (count($event)) {
						$venue_link = sprintf('<a href="%s" target="_blank">%s</a>,',
						get_post_permalink($venue[0]['ID']),
						__('Venue information', self::get_instance()->plugin_slug));
					}					
					
					
					$message = '<h3>' . __('Start editing your new pages', self::get_instance()->plugin_slug) . '</h3><p>'.
											
					sprintf(__('View %s <a href="%s" target="_blank">%s</a>, <a href="%s" target="_blank">%s</a>, %s <a href="%s" target="_blank">%s</a> %s or <a href="%s" target="_blank">%s</a> pages', self::get_instance()->plugin_slug), 
						$event_link,
						$events->guid, 
						__('Catalogue', self::get_instance()->plugin_slug), 
						$upcoming->guid,  
						$upcoming->post_title,
						$presenter_link,
						$presenters->guid, 
						__('Presenters list', self::get_instance()->plugin_slug), 						
						$venue_link,
						$venues->guid,  
						__('Venues list', self::get_instance()->plugin_slug)
					) . '</p><p>' . __('Edit the page <a href="#pages" class="arlo-pages-setup">templates</a> for each of these websites pages below.') . '</p>';
					
					echo '
					<div class="' . (count($error) ? "error" : "") . ' notice is-dismissible" id="' . $notice_id . '">
			        	' . $message . '
			        	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
				 	</div>
				 	';
					
					unset($_SESSION['arlo-demo']);		
				}				
			}		
		}
	}	
	
	public static function welcome_notice() {
		$settings = get_option('arlo_settings');
		$notice_id = self::$dismissible_notices['welcome'];
		$user = wp_get_current_user();
		$meta = get_user_meta($user->ID, $notice_id, true);
		
		if ($meta !== '0') {
			echo '
			<div class="notice is-dismissible" id="' . $notice_id . '">
				<h3>' . __('Welcome to Arlo for WordPress', self::get_instance()->plugin_slug) . '</h3>
				<table class="arlo-welcome">
					<tr>
						<td class="logo" valign="top">
							<a href="http://www.arlo.co" target="_blank"><img src="' . plugins_url( '/assets/img/icon-128x128.png', __FILE__) . '" style="width: 65px"></a>
						</td>
						<td>
							<p>' . __( 'Create beautiful and interactive training and event websites using the Arlo for WordPress plugin. Access an extensive library of WordPress Shortcodes, Templates, and Widgets, all designed specifically for web developers to make integration easy.', self::get_instance()->plugin_slug) . '</p>
							<p>' . __('<a href="https://developer.arlo.co/doc/wordpress/index" target="_blank">Learn how to use</a> Arlo for WordPress or visit <a href="http://www.arlo.co" target="_blank">www.arlo.co</a> to find out more about Arlo.', self::get_instance()->plugin_slug) . '</p>
							<p>' . (empty($settings['platform_name']) ? '<a href="?page=arlo-for-wordpress&load-demo" class="button button-primary">' . __('Try with demo data', self::get_instance()->plugin_slug) . '</a> &nbsp; &nbsp; ' : '') .'<a href="http://www.arlo.co/register" target="_blank"  class="button button-primary">' . __('Get started with free trial', self::get_instance()->plugin_slug) . '</a></p>
						</td>
					</tr>
				</table>
				<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
		    </div>
			';		
		}
		
		self::load_demo_notice(!empty($_SESSION['arlo-demo']) ? $_SESSION['arlo-demo'] : []);
		self::webinar_notice();
		self::developer_notice();
		
		unset($_SESSION['arlo-import']);
	}	
	
	public static function developer_notice() {
		$notice_id = self::$dismissible_notices['developer'];
		$user = wp_get_current_user();
		$meta = get_user_meta($user->ID, $notice_id, true);

		if ($meta !== '0') {
			echo '
			<div class="notice is-dismissible" id="' . $notice_id . '">
				<p class="developer">
					
					<img src="' . plugins_url( '/assets/img/tips-yellow.png', __FILE__) . '" style="width: 32px">
					' . __('Are you a web developer building a site for a client?', self::get_instance()->plugin_slug) . '
					' . sprintf(__('<a target="_blank" href="%s">Contact us to become an Arlo partner</a>', self::get_instance()->plugin_slug), 'https://www.arlo.co/contact') . '
				</p>
				<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
		    </div>
			';	
		}
	}
	
	public static function webinar_notice() {
		$notice_id = self::$dismissible_notices['webinar'];
		$user = wp_get_current_user();
		$meta = get_user_meta($user->ID, $notice_id, true);

		if ($meta !== '0') {	
			echo '
			<div class="notice is-dismissible" id="' . $notice_id . '" style="display: none">
				<p class="webinar">
					<a target="_blank" href="https://www.arlo.co/video/wordpress-overview" target="_blank"><img src="' . plugins_url( '/assets/img/video-yellow.png', __FILE__) . '" style="width: 32px">' . __('Watch overview video', self::get_instance()->plugin_slug) .'</a>
					<img src="' . plugins_url( '/assets/img/training-yellow.png', __FILE__) . '" style="width: 32px">
					' . __('Join <a target="_blank" href="" class="webinar_url">Arlo for WordPress Getting started</a> webinar on <span id="webinar_date"></span>', self::get_instance()->plugin_slug) . '
					' . __('<a target="_blank" href="" class="webinar_url">Register now!</a> or <a target="_blank" href="" id="webinar_template_url">view more times</a>', self::get_instance()->plugin_slug) . '
				</p>
				<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
		    </div>
			';	
		}
	}	
	
	public static function permalink_notice() {
		echo '
		<div class="error notice">
			<p><strong>' . __("Permalink setting change required.", self::get_instance()->plugin_slug) . '</strong> ' . sprintf(__('Arlo for WordPress requires <a target="_blank" href="%s">Permalinks</a> to be set to "Post name".', self::get_instance()->plugin_slug), admin_url('options-permalink.php')) . '</p>
	    </div>
		';		
	}		
	
	public static function posttype_notice() {
		echo '
		<div class="error notice">
			<p><strong>' . __("Page setup required.", self::get_instance()->plugin_slug) . '</strong> ' . __('Arlo for WordPress requires you to setup the pages which will host event information.', self::get_instance()->plugin_slug ) .' '. sprintf(__('<a href="%s" class="arlo-pages-setup">Setup pages</a>', self::get_instance()->plugin_slug), admin_url('admin.php?page=arlo-for-wordpress#pages/events')) . '</p>
			<p>' . sprintf(__('<a target="_blank" href="%s">View documentation</a> for more information.', self::get_instance()->plugin_slug), 'http://developer.arlo.co/doc/wordpress/index#pages-and-post-types') . '</p>
	    </div>
		';
	}
	
	public static function connected_platform_notice() {
		$settings = get_option('arlo_settings');
		echo '
			<div class="notice arlo-connected-message"> 
				<p>
					Arlo is connected to <strong>' . $settings['platform_name'] . '</strong> <span class="arlo-block">Last synchronized: <span class="arlo-last-sync-date">' . self::get_instance()->get_last_import() . '</span></span> 
					<a class="arlo-block arlo-sync-button" href="?page=arlo-for-wordpress&arlo-import">Synchronize now</a>
				</p>
			</div>
		';
		
		if (strtolower($settings['platform_name']) === "websitetestdata") {
			echo '
				<div class="notice updated"> 
					<p>
						<strong>Connected to demo data</strong>  Your site is currently using demo event, presenter, and venue data. Start an Arlo trial to load your own events!
					</p>
					<p>
						<a class="button button-primary" href="https://www.arlo.co/register">Get started with free Arlo trial</a>&nbsp;&nbsp;&nbsp;&nbsp;
						<a class="button button-primary arlo-block" href="#general" id="arlo-connet-platform">Connect existing Arlo platform</a>
					</p>
				</div>
			';
			
		}
	}		
	
	
	public static function start_scheduler_callback() {		
		do_action("arlo_scheduler");
		
		wp_die();
	}
	
	public static function arlo_get_last_import_log_callback() {
		global $wpdb;
		$plugin = Arlo_For_Wordpress::get_instance();
		
		$sql = "
		SELECT
			message,
			created,
			successful
		FROM
			{$wpdb->prefix}arlo_import_log
		ORDER BY
			id DESC
		LIMIT 
			1
		";
		
		$log = $wpdb->get_results($sql, ARRAY_A);
		
		if (count($log)) {
			if (strpos($log[0]['message'], "Error code 404") !== false ) {
				$log[0]['message'] = __('The provided platform name does not exist.', self::get_instance()->plugin_slug);
			}
				
			$log[0]['last_import'] = $plugin->get_last_import();
			
			echo wp_json_encode($log[0]);
		}
		
		wp_die();
	}
	
	
	public static function arlo_get_task_info_callback() {
		$task_id = intval($_POST['taskID']);
		
		if ($task_id > 0) {
			$plugin = Arlo_For_Wordpress::get_instance();
			$scheduler = $plugin->get_scheduler();
			
			$task = $scheduler->get_tasks(null, null, $task_id);
			
			echo wp_json_encode($task);
		}
		
		wp_die();
	}
	
	public static function dismissible_notice_callback() {		
		$user = wp_get_current_user();
		
		if (in_array($_POST['id'], self::$dismissible_notices)) {
			update_user_meta($user->ID, $_POST['id'], 0);
		}
		
		echo $_POST['id'];
		wp_die();
	}	
	
	public static function get_tag_by_id($tag_id) {
		global $wpdb;		
		
		$tag = $wpdb->get_row(
		"SELECT 
			id, 
			tag
		FROM 
			" . $wpdb->prefix . "arlo_tags
		WHERE 
			id = " . intval($tag_id), ARRAY_A);
			

		return $tag;
	}	
		

	/**
	 * add_pages function.
	 * 
	 * @access public
	 * @return void
	 */
	private static function add_pages() {
		
		$settings = get_option('arlo_settings');
	
		foreach(self::$pages as $page) {
			$current_page = get_page_by_title($page['title']);
		
			if(is_null($current_page)) {
				$post_id = wp_insert_post(array(
					'post_type'		=> 'page',
					'post_status'	=> 'draft',
					'post_name' 	=> $page['name'],
					'post_title'	=> $page['title'],
					'post_content' 	=> $page['content']
				));
				
				/*
				if(isset($page['child_post_type'])) {
					foreach(self::$post_types as $id => $type) {
						if($page['child_post_type'] == $id) {
							$settings['post_types'][$id]['posts_page'] = $post_id;
						}
					}
				}
				*/
			}
		}
	
		// update settings
		update_option('arlo_settings', $settings);
	}
}

