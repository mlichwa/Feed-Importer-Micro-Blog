<?php
$show = '';

if (!isset($f)) {
	$f = array(
		'id' => 0,
		'name' => 'New feed',
		'url' => '',
		'max_posts' => 20,
		'author_id' => 1,
		'category_id' => 1,
		'tags_id' => array(),
		'strip_html' => 'false'
	);
	$show = ' show';
}

if (is_array($f['tags_id'])) {
	if (!empty($f['tags_id'])) {
		foreach ($f['tags_id'] as $tag) {
			$tagname = get_tag($tag);
			$tagarray[] = $tagname->name;
		}
		$tag = join(',', $tagarray);
	} else {
		$tag = array();
	}
} else {
	if (empty($f['tags_id'])) {
		$f['tags_id'] = array();
		$tag = '';
	} else {
		$f['tags_id'] = array($f['tags_id']);
		$tagname = get_tag(intval($f['tags_id']));
		$tag = $tagname->name;
	}
}

if (is_array($f['category_id'])) {
	foreach ($f['category_id'] as $cat) {
		$catarray[] = get_cat_name($cat);
	}
	$category = join(',', $catarray);
} else {
	if (empty($f['category_id'])) {
		$f['category_id'] = array(1);
		$category = get_the_category_by_ID(1);
	} else {
		$f['category_id'] = array($f['category_id']);
		$category = get_the_category_by_ID(intval($f['category_id']));
	}
}
?>

<tr id="display_<?php echo ($f['id']); ?>" class="data-row<?php echo $show; ?>" data-fields="name,url,max_posts">
	<td class="rss_mb-feed_name">
		<strong><a href="#" class="toggle-edit" data-target="<?php echo ($f['id']); ?>"><span class="field-name"><?php echo esc_html(stripslashes($f['name'])); ?></span></a></strong>
		<div class="row-options">
			<a href="#" class="toggle-edit" data-target="<?php echo ($f['id']); ?>"><?php _e('Edit', 'rss_mb'); ?></a> | 
			<a href="#" class="delete-row" data-target="<?php echo ($f['id']); ?>"><?php _e('Delete', 'rss_mb'); ?></a>
		</div>
	</td>
	<td class="rss_mb-feed_url"><span class="field-url"><?php echo esc_url(stripslashes($f['url'])); ?></span></td>
	<td class="rss_mb_feed_max_posts"><span class="field-max_posts"><?php echo $f['max_posts']; ?></span></td>
   <!-- <td width="20%"><?php //echo $category;  ?></td>-->
</tr>
<tr id="edit_<?php echo ($f['id']); ?>" class="edit-row<?php echo $show; ?>">
	<td colspan="4">
		<table class="widefat edit-table">
			<tr>
				<td>
					<label for="<?php echo ($f['id']); ?>-name"><?php _e("Feed name", 'rss_mb'); ?></label>
					<p class="description">Name of this feed.</p></td>
				<td>
					<input type="text" class="field-name" name="<?php echo ($f['id']); ?>-name" id="<?php echo ($f['id']); ?>-name" value="<?php echo esc_attr(stripslashes($f['name'])); ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo ($f['id']); ?>-url"><?php _e("Micro.blog Feed URL", 'rss_mb'); ?></label>
					<p class="description">Specify your Micro.blog feed url. Your url will look similar to this: http://username.micro.blog/feed.xml and you can find it under Account->Feeds in your Micro.blog dashboard.</p>
				</td>
				<td><input type="text" class="field-url" name="<?php echo ($f['id']); ?>-url" id="<?php echo ($f['id']); ?>-url" value="<?php echo esc_attr(stripslashes($f['url'])); ?>" /></td>
			</tr>
			<tr>
				<td><label for="<?php echo ($f['id']); ?>-max_posts"><?php _e("Max posts / import", 'rss_mb'); ?></label></td>
				<td><input type="number" class="field-max_posts" name="<?php echo ($f['id']); ?>-max_posts" id="<?php echo ($f['id']); ?>-max_posts" value="<?php echo ($f['max_posts']); ?>" min="1" max="100" /></td>
			</tr>
			<tr>
				<td><label for="<?php echo ($f['id']); ?>-author_id"><?php _e("Feed Author", 'rss_mb'); ?></label></td>
				<td>
					<?php
					$args = array(
						'id' => $f['id'] . '-author_id',
						'name' => $f['id'] . '-author_id',
						'selected' => $f['author_id'],
						'class' => 'rss-mb-specific-feed-author'
					);
					wp_dropdown_users($args);
					?>
				</td>
			</tr>
			<tr>
				<td><label for=""><?php _e("Category", 'rss_mb'); ?></label></td>
				<td>
					<?php
					$rss_post_mb_admin = new rssMBAdmin();
					$disabled = '';
					
						?>
						<div class="category_container">
							<ul>
						<?php
						$allcats = $rss_post_mb_admin->wp_category_checklist_rss_mb(0, false, $f['category_id']);
						$allcats = str_replace('name="post_category[]"', 'name="' . $f['id'] . '-category_id[]"', $allcats);
						echo $allcats;
						?>
							</ul>
						</div>
						<?php
					
					?>
				</td>
			</tr>
			<tr>
				<td><label for=""><?php _e("Tags", 'rss_mb'); ?></label></td>
				<td>
					<div class="tags_container">
						<?php
						echo $rss_post_mb_admin->rss_mb_tags_checkboxes($f['id'], $f['tags_id']);
						?></div>
				</td>
			</tr>
			<tr>
				<td>
					<label for="<?php echo ($f['id']); ?>-keywords"><?php _e('Keywords Filter', 'rss_mb'); ?></label>
					<p class="description"><?php _e('Enter keywords and/or regex, separated by commas', "rss_mb"); ?></p>
					<p class="description">
						<?php _e('Only posts matching these keywords/regex will be imported', "rss_mb"); ?>
					</p>
				</td>
				<td>
					<textarea name="<?php echo ($f['id']); ?>-keywords" id="<?php echo ($f['id']); ?>-keywords" cols="30" rows="<?php echo $disabled ? '3' : '10'; ?>"<?php echo $disabled; ?>><?php
						echo isset($f['keywords']) && !empty($f['keywords']) && is_array($f['keywords']) ? implode(', ', $f['keywords']) : '';
						?></textarea>
				</td>
			</tr>
			<tr>
				<td><label for=""><?php _e("Strip html tags", 'rss_mb'); ?></label></td>
				<td>
					<ul class="radiolist">
						<li>
							<label><input type="radio" id="<?php echo($f['id']); ?>-strip_html" name="<?php echo($f['id']); ?>-strip_html" value="true" <?php echo($f['strip_html'] == 'true' ? 'checked="checked"' : ''); ?> /> <?php _e('Yes', 'rss_mb'); ?></label>
						</li>
						<li>
							<label><input type="radio" id="<?php echo($f['id']); ?>-strip_html" name="<?php echo($f['id']); ?>-strip_html" value="false" <?php echo($f['strip_html'] == 'false' ? 'checked="checked"' : ''); ?> /> <?php _e('No', 'rss_mb'); ?></label>
						</li>
					</ul>
				</td>
			</tr>
			<tr>
				<td><input type="hidden" name="id" value="<?php echo($f['id']); ?>" /></td>
				<td><a id="close-edit-table-<?php echo($f['id']); ?>" class="button button-large toggle-edit" data-target="<?php echo ($f['id']); ?>"><?php _e('Close', 'rss_mb'); ?></a></td>
			</tr>
		</table>

	</td>
</tr>
