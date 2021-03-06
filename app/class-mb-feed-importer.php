<?php

/**
 * Pretty much an entry point.
 */
class mbFeedImporter {

	/**
	 * A var to store the options in
	 * @var array
	 */
	public $options = array();

	/**
	 * A var to store the link to the plugin page
	 * @var array
	 */
	public $page_link = '';

	/**
	 * To initialise the admin and cron classes
	 * 
	 * @var object
	 */
	private $admin, $cron;

	/**
	 * Start
	 */
	function __construct() {

		// populate the options first
		$this->load_options();

		// do any upgrade if needed
		$this->upgrade();

		// setup this plugin options page link
		$this->page_link = admin_url('options-general.php?page=rss_mb');

		// hook translations
		add_action('plugins_loaded', array($this, 'localize'));

		add_filter('plugin_action_links_' . RSS_MB_BASENAME, array($this, 'settings_link'));
	}

	/**
	 * Load options from the db
	 */
	public function load_options() {
		
		$default_settings = array(
			'enable_logging' => true,
			'frequency' => 4,
			'post_template' => "{\$content}\nSource: {\$feed_title}",
			'post_status' => 'publish',
			'author_id' => 1,
			'allow_comments' => 'open',
			'block_indexing' => false,
			'nofollow_outbound' => true,
			'keywords' => array(),
			'import_images_locally' => false,
			'disable_thumbnail' => false,
			'cache_deleted' => true,
			'mb_feed_title' => "",
			'import_post_length' => 'All',
		);

		


		$options = get_option('rss_mb_feeds', array());

		// prepare default options when there is no record in the database
		if (!isset($options['feeds'])) {
			$options['feeds'] = array();
		}
		if (!isset($options['settings'])) {
			$options['settings'] = array();
		}
		if (!isset($options['latest_import'])) {
			$options['latest_import'] = '';
		}
		if (!isset($options['imports'])) {
			$options['imports'] = 0;
		}
		if (!isset($options['upgraded'])) {
			$options['upgraded'] = array();
		}

		$options['settings'] = wp_parse_args($options['settings'], $default_settings);

		$this->options = $options;
	}

	/**
	 * Upgrade plugin settings
	 */
	public function upgrade() {

		global $wpdb;
		$upgraded = FALSE;
		$bail = FALSE;

		// migrate to rss_mb_deleted_posts only items from rss_mb_imported_posts that are actually deleted, discard the others
		// do this in iterations so not to degrade the UX
		if ( ! isset($this->options['upgraded']['deleted_posts']) ) {
			// get meta data for "deleted" and "imported" posts
			$rss_mb_deleted_posts = get_option( 'rss_mb_deleted_posts', array() );
			$rss_mb_imported_posts = get_option( 'rss_mb_imported_posts', array() );
			$rss_mb_imported_posts_migrated = get_option( 'rss_mb_imported_posts_migrated', array() );
			// limit execution time (in seconds)
			$_limit = ( ( defined('DOING_CRON') && DOING_CRON ) ? 20 : ( ( defined('DOING_AJAX') && DOING_AJAX ) ? 10 : 3 ) );
			$_start = microtime(TRUE);
			// iterate through all imported posts' source URLs
			foreach ( $rss_mb_imported_posts as $k => $source_url ) {
				// strip any params from the URL
				$_source_url = explode('?',$source_url);
				$_source_url = $_source_url[0];
				// hash the URL for storage
				$source_md5 = md5($_source_url);
				// properly format the URL for comparison
				$source_url = esc_url($source_url);
				// skip if we already have "migrated" this item
				if ( in_array( $k, $rss_mb_imported_posts_migrated ) ) {
					continue;
				}
				// skip if we already have "deleted" metadata for this item
				if ( in_array( $source_md5, $rss_mb_deleted_posts ) ) {
					continue;
				}
				$rss_mb_imported_posts_migrated[] = $k;
				// check if there is a post with this source URL
				$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'rss_mb_source_url' and meta_value = %s", $source_url ) );
				// when there is no such post (it was deleted?)
				if ( ! $post_id ) {
					// add this source URL to "deleted" metadata
					$rss_mb_deleted_posts[] = $source_md5;
				} else {
					// otherwise update the post metadata to include hashed URL
					update_post_meta( $post_id, 'rss_mb_source_md5', $source_md5 );
				}
				// remove it from "imported" metadata
				$_curr = microtime(TRUE);
				if ( $_curr - $_start > $_limit ) {
					// bail out when the "max execution time" limit is exhausted
					$bail = TRUE;
					break;
				}
			}
			// shed any duplicates
			$rss_mb_deleted_posts = array_unique($rss_mb_deleted_posts);
			update_option('rss_mb_deleted_posts', $rss_mb_deleted_posts);
			// keep record of migrated items
			update_option('rss_mb_imported_posts_migrated', $rss_mb_imported_posts_migrated);
			// are there still source URLs in the "imported" metadata?
			if ( count($rss_mb_imported_posts_migrated) < count($rss_mb_imported_posts) ) {
			} else {
				// remove the "imported" metadata from database
				delete_option('rss_mb_imported_posts_migrated');
				delete_option('rss_mb_imported_posts');
				// mark this upgrade as completed
				$this->options['upgraded']['deleted_posts'] = TRUE;
				$upgraded = TRUE;
			}
		}
		// check after each upgrade routine
		if ( $bail ) {
			return;
		}

		// if there is something to record as an upgrade
		if ( $upgraded ) {
			update_option('rss_mb_feeds', $this->options);
		}
	}

	/**
	 * Load translations
	 */
	public function localize() {

		load_plugin_textdomain('rss_mb', false, RSS_MB_PATH . 'app/lang/');
	}

	/**
	 * Initialise
	 */
	public function init() {

		// initialise admin and cron
		$this->cron = new rssMBCron();
		$this->cron->init();

		$this->admin = new rssMBAdmin();
		$this->admin->init();

		$this->front = new rssMBFront();
		$this->front->init();
	}

	/**
	 * Adds a settings link
	 * 
	 * @param array $links Existing links
	 * @return type
	 */
	public function settings_link($links) {
		$settings_link = array(
			'<a href="' . $this->page_link . '">Settings</a>',
		);
		return array_merge($settings_link, $links);
	}

}
