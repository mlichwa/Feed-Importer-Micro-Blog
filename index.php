<?php

/*
  Plugin Name: Micro.blog Feed Importer
  Plugin URI: 
  Description: This plugin lets you set up an import posts from one or several rss-feeds and save them as posts on your site. It has been optimized to support Micro.blog format but does work with any other RSS feeds.
  Author: Michal Lichwa
  Version: 1.0.0
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
	define('RSS_MB_VERSION', '1.0.0');
}

if (!defined('RSS_MB_LOG_PATH')) {
	define('RSS_MB_LOG_PATH', trailingslashit(WP_CONTENT_DIR) . 'rssmb-log/');
}

if (!is_dir(RSS_MB_LOG_PATH)) {
	mkdir(RSS_MB_LOG_PATH);
}

// helper classes
include_once RSS_MB_PATH . 'app/classes/helpers/class-rss-mb-log.php';
include_once RSS_MB_PATH . 'app/classes/helpers/class-rss-mb-featured-image.php';
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

