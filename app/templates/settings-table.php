<table class="widefat rss_mb-table" id="rss_mb-settings-table">
	<thead>
		<tr>
			<th colspan="5"><?php _e('Micro.blog Global Feed Settings', 'rss_mb'); ?></th>
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
							<label for="post_template"><?php _e('Template', 'rss_mb'); ?></label>
							<p class="description"><?php _e('This is how the post will be formatted.', "rss_mb"); ?></p>
							<p class="description">
								<?php _e('Available tags:', "rss_mb"); ?>
							<dl>
								<dt><code>&lcub;$content&rcub;</code></dt>
								<dt><code>&lcub;$permalink&rcub;</code></dt>
								<dt><code>&lcub;$title&rcub;</code></dt>
								<dt><code>&lcub;$feed_title&rcub;</code></dt>
								<dt><code>&lcub;$excerpt:n&rcub;</code></dt>
								<dt><code>&lcub;$inline_image&rcub;</code> <small>insert the featured image inline into the post content</small></dt>
							</dl>
							</p>
						</td>
						<td>
							<textarea name="post_template" id="post_template" cols="30" rows="10"><?php
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
						<td><?php _e('Allow comments', "rss_mb"); ?></td>
						<td>
							<ul class="radiolist">
								<li>
									<label><input type="radio" id="allow_comments_open" name="allow_comments" value="open" <?php echo($this->options['settings']['allow_comments'] == 'open' ? 'checked="checked"' : ''); ?> /> <?php _e('Yes', 'rss_mb'); ?></label>
								</li>
								<li>
									<label><input type="radio" id="allow_comments_false" name="allow_comments" value="false" <?php echo($this->options['settings']['allow_comments'] == 'false' ? 'checked="checked"' : ''); ?> /> <?php _e('No', 'rss_mb'); ?></label>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td>
							<?php _e('Block search indexing?', "rss_mb"); ?>
							<p class="description"><?php _e('Prevent your content from appearing in search results.', "rss_mb"); ?></p>
						</td>
						<td>
							<ul class="radiolist">
								<li>
									<label><input type="radio" id="block_indexing_true" name="block_indexing" value="true" <?php echo($this->options['settings']['block_indexing'] == 'true' ? 'checked="checked"' : ''); ?> /> <?php _e('Yes', 'rss_mb'); ?></label>
								</li>
								<li>
									<label><input type="radio" id="block_indexing_false" name="block_indexing" value="false" <?php echo($this->options['settings']['block_indexing'] == 'false' || $this->options['settings']['block_indexing'] == '' ? 'checked="checked"' : ''); ?> /> <?php _e('No', 'rss_mb'); ?></label>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td>
							<?php _e('Nofollow option for all outbound links?', "rss_mb"); ?>
							<p class="description"><?php _e('Add rel="nofollow" to all outbounded links.', "rss_mb"); ?></p>
						</td>
						<td>
							<ul class="radiolist">
								<li>
									<label><input type="radio" id="nofollow_outbound_true" name="nofollow_outbound" value="true" <?php echo($this->options['settings']['nofollow_outbound'] == 'true' ? 'checked="checked"' : ''); ?> /> <?php _e('Yes', 'rss_mb'); ?></label>
								</li>
								<li>
									<label><input type="radio" id="nofollow_outbound_false" name="nofollow_outbound" value="false" <?php echo($this->options['settings']['nofollow_outbound'] == 'false' || $this->options['settings']['nofollow_outbound'] == '' ? 'checked="checked"' : ''); ?> /> <?php _e('No', 'rss_mb'); ?></label>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td>
							<?php _e('Enable logging?', "rss_mb"); ?>
							<p class="description"><?php _e('The logfile can be found <a href="#" class="load-log">here</a>.', "rss_mb"); ?></p>
						</td>
						<td>
							<ul class="radiolist">
								<li>
									<label><input type="radio" id="enable_logging_true" name="enable_logging" value="true" <?php echo($this->options['settings']['enable_logging'] == 'true' ? 'checked="checked"' : ''); ?> /> <?php _e('Yes', 'rss_mb'); ?></label>
								</li>
								<li>
									<label><input type="radio" id="enable_logging_false" name="enable_logging" value="false" <?php echo($this->options['settings']['enable_logging'] == 'false' || $this->options['settings']['enable_logging'] == '' ? 'checked="checked"' : ''); ?> /> <?php _e('No', 'rss_mb'); ?></label>
								</li>
							</ul>
						</td> 
					</tr>
					<tr>
						<td>
							<?php _e('Download and save images locally?', "rss_mb"); ?>
							<p class="description"><?php _e('Images in the feeds will be downloaded and saved in the WordPress media.', "rss_mb"); ?></p>
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
							<?php _e('Disable the featured image?', "rss_mb"); ?>
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
							<?php _e('Export and backup your Feeds and setting as CSV File', "rss_mb"); ?>
							<p class="description"><?php _e('This option will help you download a csv file with all your feeds setting , you can upload it back later.', "rss_mb"); ?></p>
						</td>
						<td>
						<?php
						$disabled = '';
						?>
							<input type="submit" value="Export your Feeds and Setting as CSV File" name="csv_download" class="button button-primary button-large"<?php echo $disabled; ?> />     
						</td> 
					</tr>
					<tr>
						<td>
							<?php _e('Import your CSV file with your feeds\' settings', "rss_mb"); ?>
							<p class="description"><?php _e('Create and Import a CSV file with your Feeds\' Setting with the following Structure and heading:<br/>
<br/>
url , name, max_posts, author_id, category_id, tags, keywords, strip_html<br/>
<br/>
url = your feed url<br/>
name = the name you gives to your feed<br/>
max_posts = the number of posts to simultaneously import<br/>
author_id = your author ID, this is a number<br/>
category_id = the Category IDs - number(s) separated with comma (,)<br/>
tags = the Tag IDs - number(s) separated with comma (,)<br/>
keywords = the filter keywords - string(s) separated with comma (,)<br/>
strip_html = strip html tags - "true" or "false"', "rss_mb"); ?></p>
						</td>
						<td>
						<?php
						$disabled = '';
						?>
							<input type="file" name="import_csv"<?php echo $disabled; ?> />
						</td> 
					</tr>
					<tr>
						<td>
							<?php _e('Export and backup your Feeds as OPML File', "rss_mb"); ?>
							<p class="description"><?php _e('This option will help you download an OPML file with all your feeds so you can upload it back later.', "rss_mb"); ?></p>
						</td>
						<td>
						<?php
						$disabled = '';
						?>
							<input type="submit" value="Export your Feeds as OPML File" name="export_opml" class="button button-primary button-large"<?php echo $disabled; ?> />     
						</td> 
					</tr>
					<tr>
						<td>
							<?php _e('Import your OPML file with your feeds', "rss_mb"); ?>
							<p class="description"><?php _e('Create and Import an OPML file with your Feeds', "rss_mb"); ?></p>
						</td>
						<td>
						<?php
						$disabled = '';
						?>
							<input type="file" name="import_opml"<?php echo $disabled; ?> />
						</td> 
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>