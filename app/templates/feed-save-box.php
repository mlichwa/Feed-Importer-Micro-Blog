<div class="postbox">
	<div class="inside">
		<div class="misc-pub-section">
			<h3 class="version">Version: <?php echo RSS_MB_VERSION; ?></h3>
			<ul>
				<li>
					<i class="icon-calendar"></i> <?php _e("Latest import:", 'rss_mb'); ?> <strong><?php echo $this->options['latest_import'] ? $this->options['latest_import'] : 'never' ; ?></strong>
				</li>
				<li><i class="icon-eye-open"></i> <a href="#" class="load-log"><?php _e("View the log", 'rss_mb'); ?></a></li>
			</ul>
		</div>
		<div id="major-publishing-actions">
			<input class="button button-primary button-large right" type="submit" name="info_update" value="<?php _e('Save', 'rss_mb'); ?>" />
			<input class="button button-large" type="submit" name="info_update" value="<?php _e('Save and import', "rss_mb"); ?>" id="save_and_import" />
		</div>
		
		<div class="rate-box">
			<h4><?php printf(__('%d posts imported and counting!', "rss_mb"), $this->options['imports']); ?></h4>
			<i class="icon-star"></i>
			<i class="icon-star"></i>
			<i class="icon-star"></i>
			<i class="icon-star"></i>
			<i class="icon-star"></i>
			<p class="description"><a href="http://wordpress.org/plugins/rss-post-importer/" target="_blank">Please support this plugin by rating it!</a></p>
		</div>
	</div>
	
</div>

