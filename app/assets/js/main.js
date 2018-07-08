(function($){

$('document').ready(function(){

	// Edit-buttons
	$('body').on('click', 'a.toggle-edit', function () {
		$('#edit_' + $(this).attr('data-target')).toggleClass('show');
		$('#display_' + $(this).attr('data-target')).toggleClass('show');
		return false;
	});

	// Delete-buttons
	$('body').on('click', 'a.delete-row', function () {
		$('#edit_' + $(this).attr('data-target')).remove();
		$('#display_' + $(this).attr('data-target')).remove();
		update_ids();
		return false;
	});

	if ( $("#rss_mb-feed-table").length ) {

		$("#rss_mb-feed-table").on("rss-mb-changed", "tr", function () {
			var $tr = $(this),
				id = $tr.attr("id").replace("display_","").replace("edit_",""),
				$tr_data = $("#display_"+id),
				$tr_edit = $("#edit_"+id),
				fields = $tr_data.data("fields").split(",");
			$.each(fields,function(i){
				var field = ".field-"+fields[i];
				$tr_data.find(field).text($tr_edit.find(field).val());
			});
			$tr_data.addClass("rss-mb-unsaved");
		});

		var do_save = false;
		$(window).bind('beforeunload', function() {
			if( ! do_save && $("#rss_mb-feed-table .rss-mb-unsaved").length ){
				return rss_mb.l18n.unsaved;
			}
		});
		$("#rss_mb-settings-form").on("submit",function(){
			do_save = true;
		});
		// Monitor dynamic inputs
		$("#rss_mb-feed-table").on('change', ':input', function(){ //triggers change in all input fields including text type
			$(this).parents("tr.edit-row").trigger("rss-mb-changed");
		});

	}

	$('a.add-row').on('click', function (e) {
		e.preventDefault();
		var id = uniqid();
		$("#rss_mb-feed-table > tbody .empty_table").parent("tr").remove();
		$tr_data = $("#rss_mb-feed-table > tfoot > tr.data-row").clone().attr("id","display_"+id).appendTo("#rss_mb-feed-table > tbody");
		$tr_edit = $("#rss_mb-feed-table > tfoot > tr.edit-row").clone().attr("id","edit_"+id).appendTo("#rss_mb-feed-table > tbody");
		$tr_data.find(".toggle-edit,.delete-row").attr("data-target",id);
		$tr_edit.find(".toggle-edit").attr("data-target",id);
		$tr_edit.find("[name='id']").val(id);
		$tr_edit.find("[for^=0-]").each(function(){
			$(this).attr("for",$(this).attr("for").replace("0-",id+"-"));
		});
		$tr_edit.find("[id^=0-]").each(function(){
			$(this).attr("id",$(this).attr("id").replace("0-",id+"-"));
		});
		$tr_edit.find("[name^=0-]").each(function(){
			$(this).attr("name",$(this).attr("name").replace("0-",id+"-"));
		});
		update_ids();
		$("#"+id+"-name").focus().select();
	});

	$('#save_and_import').on('click', function () {
		$('#save_to_db').val('true');
	});

	if ( Modernizr !== undefined && Modernizr.input.min && Modernizr.input.max )
	$("#rss_mb-settings-form [type='submit']").on("click",function(e){
		$("[name$='-max_posts']").each(function(){
			var max_posts = {
				val: parseInt($(this).val()),
				min: parseInt($(this).attr("min")),
				max: parseInt($(this).attr("max")),
				id: $(this).attr("id").replace("-max_posts","")
			}
			if ( max_posts.val < max_posts.min || max_posts.val > max_posts.max ) {
				$("#edit_"+max_posts.id).addClass("show");
				$("#display_"+max_posts.id).addClass("show");
			}
		});
	});

	$('a.load-log').on('click', function () {
		$('#main_ui').hide();
		$('.ajax_content').html('<img src="/wp-admin/images/wpspin_light.gif" alt="" class="loader" />');
		$.ajax({
			type: 'POST',
			url: rss_mb.ajaxurl,
			data: ({
				action: 'rss_mb_load_log'
			}),
			success: function (data) {
				$('.ajax_content').html(data);
			}
		});
		return false;
	});

	$('body').delegate('a.show-main-ui', 'click', function () {
		$('#main_ui').show();
		$('.ajax_content').html('');
		return false;
	});

	$('body').delegate('a.clear-log', 'click', function () {
		$.ajax({
			type: 'POST',
			url: rss_mb.ajaxurl,
			data: ({
				action: 'rss_mb_clear_log'
			}),
			success: function (data) {
				$('.log').html(data);
			}
		});
		return false;
	});

	$("#from_date").datepicker();
	$("#till_date").datepicker();

	if ( $("#rss_mb-stats-placeholder").length ) {
		rss_filter_stats = function(form) {
			var data = {
					action: "rss_mb_stats",
					rss_from_date: $("#from_date").val() || "",
					rss_till_date: $("#till_date").val() || ""
				},
				$loading = false;
			if (form && $("#submit-rss_filter_stats").length) {
				data.rss_filter_stats = $("#submit-rss_filter_stats").val();
			} else {
				$loading = $('<div class="rss_mb_overlay"><img class="rss_mb_loading" src="'+rss_mb.pluginurl+'app/assets/img/loading.gif" /><p>Stats are loading. Please wait...</p></div>').appendTo("#rss_mb-stats-placeholder");
			}
			$.ajax({
				type: "POST",
				url: rss_mb.ajaxurl,
				data: data,
				success: function (data) {
					if ($loading) { $loading.remove(); $loading = false; }
					$("#rss_mb-stats-placeholder").empty().append(data);
					drawChart();
					$("#from_date").datepicker();
					$("#till_date").datepicker();
					$("#submit-rss_filter_stats").on("click",function(e){
						e.preventDefault();
						$loading = $('<div class="rss_mb_overlay"><img class="rss_mb_loading" src="'+rss_mb.pluginurl+'app/assets/img/loading.gif" /><p>Stats are loading. Please wait...</p></div>').appendTo("#rss_mb-stats-placeholder");
						rss_filter_stats(true);
					});
				}
			});
		};
		rss_filter_stats();
	}

	if ( $("#rss_mb_progressbar").length && feeds !== undefined && feeds.count ) {
		var import_feed = function(id) {
			$.ajax({
				type: 'POST',
				url: rss_mb.ajaxurl,
				data: {
					action: 'rss_mb_import',
					feed: id
				},
				success: function (data) {
					var data = data.data || {};
					$("#rss_mb_progressbar").progressbar({
						value: feeds.processed()
					});
					$("#rss_mb_progressbar_label .processed").text(feeds.processed());
					if ( data.count !== undefined ) feeds.imported(data.count);
					if (feeds.left()) {
						$("#rss_mb_progressbar_label .count").text(feeds.imported());
						import_feed(feeds.get());
					} else {
						$("#rss_mb_progressbar_label").html("Import completed.");
						//Imported posts: " + feeds.imported()
					}
				}
			});
		}
		$("#rss_mb_progressbar").progressbar({
			value: 0,
			max: feeds.total()
		});
		$("#rss_mb_progressbar_label").html("Import in progres...");
		import_feed(feeds.get());
	}

});

})(jQuery);

function update_ids() {

	ids = jQuery("#rss_mb-feed-table > tbody input[name='id']").map(function () {
		return jQuery(this).val();
	}).get().join();

	jQuery('#ids').val(ids);

}

var feeds = {
	ids: feeds || [],
	count: feeds && feeds.length ? feeds.length : 0,
	imported_posts: 0,
	set: function(ids){
		this.ids = ids;
		this.count = ids.length;
	},
	get: function(){
		return this.ids.splice(0,1)[0];
	},
	left: function(){
		return this.ids.length;
	},
	processed: function(){
		return this.count - this.ids.length;
	},
	total: function(){
		return this.count;
	},
	imported: function(num){
		if ( num !== undefined && !isNaN(parseInt(num)) ) this.imported_posts += parseInt(num);
		return this.imported_posts;
	}
};
