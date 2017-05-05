<div class="wrap">
	<h2><?php _e("Micro.blog Importer Log", 'rss_mb'); ?></h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="postbox-container-2" class="postbox-container">
				<a href="#" class="button button-large button-primary show-main-ui"><?php _e("Ok, all done", "rss_mb"); ?></a> 
				<a href="#" class="button button-large button-warning clear-log"><?php _e("Clear log", "rss_mb"); ?></a> 
				<div class="log">
					<code><?php echo(wpautop($log, true)); ?></code>
				</div>
			</div>
		</div>
	</div>
</div>