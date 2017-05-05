<?php

/**
 * Handles cron jobs
 *
 * @author mobilova UG (haftungsbeschränkt) <rsspostimporter@feedsapi.com>
 */
class rssMBCron {

	/**
	 * Initialise
	 */
	public function init() {

		// hook up scheduled events
		add_action('wp', array(&$this, 'schedule'));

		add_action('rss_mb_cron', array(&$this, 'do_hourly'));
	}

	/**
	 * Check and confirm scheduling
	 */
	function schedule() {

		if (!wp_next_scheduled('rss_mb_cron')) {

			wp_schedule_event(time(), 'hourly', 'rss_mb_cron');
		}
	}

	/**
	 * Import the feeds on schedule
	 */
	function do_hourly() {

		$engine = new rssMBEngine();
		$engine->import_feed();
	}

}
