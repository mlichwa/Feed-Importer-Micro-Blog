<?php

/*
  Plugin Name: Feed Importer for Micro.blog
  Plugin URI: 
  Description: This plugin imports posts created with Micro.blog. You need a valid account with Micro.blog to use this plugin.  
  Author: Michal Lichwa
  Version: 0.9.0
  Author URI: https://michallichwa.com/
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
  Text Domain: rss_mb
  Domain Path: /lang/
 */

// define some constants
if (!defined('RSS_MB_PATH')) {
	define('RSS_MB_PATH', trailingslashit(plugin_dir_path(__FILE__)));
}

if (!defined('RSS_MB_URL')) {
	define('RSS_MB_URL', trailingslashit(plugin_dir_url(__FILE__)));
}

if (!defined('RSS_MB_BASENAME')) {
	define('RSS_MB_BASENAME', plugin_basename(__FILE__));
}

if (!defined('RSS_MB_VERSION')) {
	define('RSS_MB_VERSION', '0.9.0');
}

if (!defined('RSS_MB_LOG_PATH')) {
	define('RSS_MB_LOG_PATH', trailingslashit(WP_CONTENT_DIR) . 'rssmb-log/');
}

if (!is_dir(RSS_MB_LOG_PATH)) {
	mkdir(RSS_MB_LOG_PATH);
}

// helper classes
include_once RSS_MB_PATH . 'app/classes/helpers/class-rss-mb-log.php'; // Log cron tasks
include_once RSS_MB_PATH . 'app/classes/helpers/class-rss-mb-featured-image.php'; // Add featured image to a blog post
include_once RSS_MB_PATH . 'app/classes/helpers/class-rss-mb-parser.php'; 
include_once RSS_MB_PATH . 'app/classes/helpers/rss-mb-functions.php';
include_once RSS_MB_PATH . 'app/classes/helpers/class-OPMLParser.php'; // OPML Parser

// admin classes
include_once RSS_MB_PATH . 'app/classes/admin/class-rss-mb-admin-processor.php';
include_once RSS_MB_PATH . 'app/classes/admin/class-rss-mb-admin.php';
include_once RSS_MB_PATH . 'app/classes/admin/class-rss-mb-export-to-csv.php';
include_once RSS_MB_PATH . 'app/classes/admin/class-rss-mb-stats.php';
include_once RSS_MB_PATH . 'app/classes/admin/class-rss-mb-opml.php';

// Front classes
include_once RSS_MB_PATH . 'app/classes/front/class-rss-mb-front.php';

// main importers
include_once RSS_MB_PATH . 'app/classes/import/class-rss-mb-engine.php';
include_once RSS_MB_PATH . 'app/classes/import/class-rss-mb-cron.php';

// the main loader class
include_once RSS_MB_PATH . 'app/class-rss-post-importer.php';

// initialise plugin as a global var
global $rss_post_importer;

$rss_post_importer = new rssPostImporter();

$rss_post_importer->init();

