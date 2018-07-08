

<table class="widefat rss_mb-table" id="rss_mb-settings-table">
	<thead>
		<tr>
			<th colspan="5"><?php _e('Feed Importer for Micro.blog Settings', 'rss_mb'); ?></th>
		</tr>
	</thead>
	<tbody class="setting-rows">
		<tr class="edit-row show">
			<td colspan="4">
				<table class="widefat edit-table">
					<tr>
						<td>
							<label for="frequency"><?php _e('Frequency', "rss_mb"); ?></label>
							<p class="description"><?php _e('How often will the import run.', "rss_mb"); ?></p>
							<br>
							<p class="large">
								<?php printf(__("If your imports are not running regularly according to your settings you might need to set up a scheduled task in CRON, there are external sites that offer the service, such as:", "rss_mb"), get_site_url()); ?>
								<ul>
									<li><a href="http://www.mywebcron.com" target="_blank">www.mywebcron.com</a></li>
									<li><a href="http://www.onlinecronjobs.com" target="_blank">www.onlinecronjobs.com</a></li>
								</ul>
							</p>

						</td>
						<td>
							<select name="frequency" id="frequency">
								<?php $x = wp_get_schedules(); ?>
								<?php foreach (array_keys($x) as $interval) : ?>
									<option value="<?php echo $interval; ?>" <?php
									if ($this->options['settings']['frequency'] == $interval) : echo('selected="selected"');
									endif;
									?>><?php echo $x[$interval]['display']; ?></option>
										<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="post_length"><?php _e('Import Entries according to post length', "rss_mb"); ?></label>
							<p class="description"><?php _e('Specify posts to be imported based on their length. <br><strong>All-</strong> long and short entries<br><strong>Short Entries-</strong> without titles and less than 280 characters, <br><strong>Long Entries-</strong> with titles and more than 280 characters', "rss_mb"); ?></p>
						</td>
						<td>
						<select name="import_post_length" id="import_post_length">
							<?php $post_lengths = array("All", "Short Entries", "Long Entries"); ?>
							<?php foreach ($post_lengths as $length) : ?>
								<option value="<?php echo $length; ?>" <?php
									if ($this->options['settings']['import_post_length'] == $length) : echo('selected="selected"');
									endif;
									?>><?php echo $length; ?>
								</option>
							<?php endforeach; ?>
						</select>


						</td>
					</tr>
					<tr>
						<td>
							<label for="mb_feed_title"><?php _e('Specify title for short entries.', "rss_mb"); ?></label>
							<p class="description">
							<?php _e('Short Micro.blog entries don\'t have titles. By default Micro.blog Feed Importer uses formatted publish date as a post title. You can change that by setting your own title in the box on the right or leave it empty to use formatted date.', "rss_mb"); ?> 
							</p>
						</td>
						<td>
							<?php 
								$timestamp = date("l jS \of F Y");
								$mb_feed_title = isset($this->options['settings']["mb_feed_title"]) ? $this->options['settings']["mb_feed_title"] : ""; ?>
							<input placeholder="<?php echo $timestamp; ?>" type="text" name="mb_feed_title" id="mb_feed_title" value="<?php echo $mb_feed_title; ?>" />
						</td>
					</tr>
					<tr>
						<td>
							<label for="post_template"><?php _e('Filter Entries by Keywords or Regex', 'rss_mb'); ?></label>
							<p class="description"><?php _e('Filter Micro.blog entries.', "rss_mb"); ?></p>
							<p class="description">
								<?php _e('Use keywords and regular expressions to filter Micro.blog entries. Keywords and regex should be separated by comas.', "rss_mb"); ?>
							</p>
						</td>
						<td>

							<textarea name="keyword_filter" id="post_template" cols="30" rows="5">
								<?php
								echo implode(', ', $this->options['settings']['keywords']);
								?>
							</textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label for="post_template"><?php _e('Template', 'rss_mb'); ?></label>
							<p class="description"><?php _e('This is how the post will be formatted.', "rss_mb"); ?></p>
							<p class="description">
								<?php _e('Available tags:', "rss_mb"); ?>
							<dl>
								<dt><code>&lcub;$content&rcub;</code></dt>
								<dt><code>&lcub;$title&rcub;</code></dt>
								<dt><code>&lcub;$feed_title&rcub;</code></dt>
								
							</dl>
							</p>
						</td>
						<td>
							<textarea name="post_template" id="post_template" cols="30" rows="5"><?php
								$value = (
										$this->options['settings']['post_template'] != '' ? $this->options['settings']['post_template'] : '{$content}' . "\nSource: " . '{$feed_title}'
										);

								$value = str_replace(array('\r', '\n'), array(chr(13), chr(10)), $value);

								echo esc_textarea(stripslashes($value));
								?></textarea>
						</td>
					</tr>
					<tr>
						<td><label for="post_status"><?php _e('Post status', "rss_mb"); ?></label></td>
						<td>

							<select name="post_status" id="post_status">
								<?php
								$statuses = get_post_stati('', 'objects');

								foreach ($statuses as $status) {
									?>
									<option value="<?php echo($status->name); ?>" <?php
									if ($this->options['settings']['post_status'] == $status->name) : echo('selected="selected"');
									endif;
									?>><?php echo($status->label); ?></option>
											<?php
										}
										?>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php _e('Author', 'rss_mb'); ?></td>
						<td>
							<?php
							$args = array(
								'id' => 'author_id',
								'name' => 'author_id',
								'selected' => $this->options['settings']['author_id']
							);
							wp_dropdown_users($args);
							?> 
						</td>
					</tr>
					<tr>
						<td>
							<?php _e('Download and save images locally?', "rss_mb"); ?>
							<p class="description"><?php _e('Images in the feeds will be downloaded and saved in the WordPress media. Once enabled, Micro.Blog Feed Importer will format images into Galleries. ', "rss_mb"); ?></p>
						</td>
						<td>
							<ul class="radiolist">
								<li>
									<label><input type="radio" id="import_images_locally_true" name="import_images_locally" value="true" <?php echo($this->options['settings']['import_images_locally'] == 'true' ? 'checked="checked"' : ''); ?> /> <?php _e('Yes', 'rss_mb'); ?></label>
								</li>
								<li>
									<label><input type="radio" id="import_images_locally_false" name="import_images_locally" value="false" <?php echo($this->options['settings']['import_images_locally'] == 'false' || $this->options['settings']['enable_logging'] == '' ? 'checked="checked"' : ''); ?> /> <?php _e('No', 'rss_mb'); ?></label>
								</li>
							</ul>
						</td> 
					</tr>
					<tr>
						<td>
							<?php _e('Disable featured image?', "rss_mb"); ?>
							<p class="description"><?php _e('Don\'t set a featured image for the imported posts.', "rss_mb"); ?></p>
						</td>
						<td>
							<ul class="radiolist">
								<li>
									<label><input type="radio" id="disable_thumbnail_true" name="disable_thumbnail" value="true" <?php echo($this->options['settings']['disable_thumbnail'] == 'true' ? 'checked="checked"' : ''); ?> /> <?php _e('Yes', 'rss_mb'); ?></label>
								</li>
								<li>
									<label><input type="radio" id="disable_thumbnail_false" name="disable_thumbnail" value="false" <?php echo($this->options['settings']['disable_thumbnail'] == 'false' || $this->options['settings']['disable_thumbnail'] == '' ? 'checked="checked"' : ''); ?> /> <?php _e('No', 'rss_mb'); ?></label>
								</li>
							</ul>
						</td> 
					</tr>
					<tr>
						<td>
							<?php _e('Clean cache if posts were deleted', "rss_mb"); ?>
							<p class="description"><?php _e('In order to re-import Micro.blog entries: <br>
															1. Delete selected Posts that were imported with Micro.blog Feed Importer.<br>
															2. Press \'Clean Micro.blog Wordpress Cache\' button.<br>
															3. Press \'Save and Import\' button to import entries.',  "rss_mb"); ?></p>
						</td>
						<td>
							<?php $rss_mb_deleted_posts = count( get_option( 'rss_mb_deleted_posts', array() ) ); ?>
							<p><?php printf( _n('Cached: <strong>%d</strong> deleted post', 'Cached: <strong>%d</strong> deleted posts', $rss_mb_deleted_posts, 'rss_mb'), $rss_mb_deleted_posts ); ?></p>
							<input type="submit" value="Clean Micro.blog Wordpress Cache" name="purge_deleted_cache" class="button button-primary button-large"/>     
						</td> 
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>