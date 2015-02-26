var ucc_bpc_ajax_request = null;

jQuery(document).ready( function($) {
	var inline;
	var replaced;
	var date;
	var submit;
	var cancel;
	var title;

	$('#edit-options').hide();
	$('#edit-content').hide();
	date = $('#ucc_bpc_date').val();
	submit = $('#ucc_bpc_submit').val();
	cancel = $('a.cancel-edit-link').html();	
	title = $('#edit-form h3').html();

	$('#ucc_bpc_title').focus( function(){
		$('#edit-content').show();
		$('#edit-options').show();
	});

	/* Checklist reset confirm */
	$('#reset-checklist').on('click', function(event) {
		if ( confirm( 'Are you sure? This will erase the entire checklist and cannot be undone.' ) ) {
			var user_id = $(this).parent().data('userid');
			var data = {
				'action': 'bpc-reset',
				'user_id': user_id
			};

			$.post(ajaxurl, data, function(response) {
				alert(response);
			});
		} else {
			return false;
		}
	});

	/* Complete task checkbox. */
	$('.ucc_bpc_checkit_cb').on('click', function(event) {
		$('#ucc_bpc_id').val($(event.target).val());
		$('#ucc_bpc_is_checkit').val(1);
		var c = this.checked ? '1' : '0';
		$('#ucc_bpc_checkit').val(c);

		bpc_set_hidden_values();

		$('#edit-form').submit();
	});

	/* Edit task button. */
	$('a.edit-link').on('click', function(event) {
		event.preventDefault();

		inline = $(event.target).parent().siblings('.hidden');
		$('#ucc_bpc_id').val(inline.children('.ucc_bpc_id').html());
		$('#ucc_bpc_title').val(inline.children('.ucc_bpc_title').html());
		$('#ucc_bpc_content').val(inline.children('.ucc_bpc_content').html());
		$('#ucc_bpc_category').val(inline.children('.ucc_bpc_category').html());
		$('#ucc_bpc_status').val(inline.children('.ucc_bpc_status').html());
		$('#ucc_bpc_date').val(inline.children('.ucc_bpc_date').html());

		bpc_set_hidden_values();

		// Deal with form differences between new/edit.
		$('#ucc_bpc_submit').val(ucc_bpc.save_task);
		$('a.cancel-edit-link').html(ucc_bpc.reset_fields);
		$('#edit-form h3').html(ucc_bpc.edit_task);

		replaced = $(event.target).parent().parent().parent().replaceWith($('#edit-form'));
		$('#edit-content').show();
		$("#edit-options").show();

		$('a.edit-link').hide();
	});

	/* Cancel edit task button. */
	$('a.cancel-edit-link').on('click', function(event) {
		event.preventDefault();

		$('#ucc_bpc_id').val(0);
 		$('#ucc_bpc_title').val('');
		$('#ucc_bpc_content').val('');
		$('#ucc_bpc_category').val('');
		$('#ucc_bpc_status').val('');
		$('#ucc_bpc_date').val(date);

		$('#ucc_bpc_submit').val(submit);
		$(event.target).html(cancel);
		$('#edit-form h3').html(title);

		replaced = $('#edit-form').after(replaced);
		$('#checklist-edit-form').append(replaced);
		$('#edit-content').hide();
		$('#edit-options').hide();

		$('a.edit-link').show();
	});

	/* Confirm on delete link. */
	$('a.doublecheck').on('click', function(event) {
		if ( confirm( 'Are you sure?' ) ) {
			return true;
		} else {
			return false;
		}
	});

	function update_checklist_filters(){
		var status = $('select#status-filter-by option:selected').val();
		var category = $('select#category-filter-by option:selected').val();
		var sort = $('select#sort-order-by option:selected').val();
		var itemcount = $('select#itemcount option:selected').val();
		ucc_bpc_request(status, category, sort, itemcount);

		return false;
	}
	
	function bpc_set_hidden_values(){
		$('#category').val($('select#category-filter-by option:selected').val());
		$('#status').val($('select#status-filter-by option:selected').val());
		$('#sort').val($('select#sort-order-by option:selected').val());
		$('#itemcount').val($('select#itemcount option:selected').val());
		$('#upage').val($('#pag-top .page-numbers.current').html());
	}
	
	/* Sort by date. */
	$('select#sort-order-by').change( function() {
		update_checklist_filters();
	});
	
	/* Change items per page. */
	$('select#itemcount').change( function() {
		update_checklist_filters();
	});

	/* Filter by task category. */
	$('select#category-filter-by').change(function() {
		update_checklist_filters();
	});

	/* Filter by task status. */
	$('select#status-filter-by').change(function() {
		update_checklist_filters();
	});

	/* Show progress bar for bulk importer */
	$('#ucc_bpc_bulk_submit').on('click', function(event) {
		event.preventDefault();
		//$('#load-progress').show();
		//bpc_beginChecklistImport();
		$('#ucc_bpc_bulk_autosubmit').val(1);
		$('#bulk-form').submit();
	});

	/* Auto-submit bulk form. */
	var autosubmit = $('#ucc_bpc_bulk_autosubmit').val();
	if (autosubmit > 0) {
		setTimeout(function() {
			$('#bulk-form').submit();
		}, 2000);
	}
});

function bpc_beginChecklistImport(){
	var user_id = $(this).parent().data('userid');
	var data = {
		'action': 'bpc_bulk_import',
		'user_id': user_id
	};

	$.post(ajaxurl, data, function(response) {
		$('#load-progress .progress-bar').html(response);
	});
}

function ucc_bpc_request(status, category, sort, itemcount) {
	/* Save parameters to a session cookie. */
	$.cookie( 'ucc-bpc-category', category, {path: '/'} );
	$.cookie( 'ucc-bpc-status', status, {path: '/'} );
	$.cookie( 'ucc-bpc-sort', sort, {path: '/'} );
	$.cookie( 'ucc-bpc-itemcount', itemcount, {path: '/'} );

	/* Set the correct select values. */
	$('select#category-filter-by option[value="' + category + '"]').prop( 'selected', true );
	$('select#status-filter-by option[value="' + status + '"]').prop( 'selected', true );
	$('select#sort-order-by option[value="' + sort + '"]').prop( 'selected', true );
	$('select#itemcount option[value="' + itemcount + '"]').prop( 'selected', true );

	/* Reload the task list based on the selections. */
	if ( ucc_bpc_ajax_request )
		ucc_bpc_ajax_request.abort();
	
	var data = {
		action: 'ucc-bpc-filter',
		'category': category,
		'status': status,
		'sort': sort,
		'itemcount' : itemcount,
		'search': $('#search').val()
	}

	ucc_bpc_ajax_request = $.post( ucc_bpc.ajaxurl, data, function(response) {
		$('ul#checklist-list').fadeOut( 100, function() {
			$('div#checklist-dir-list').html(response);
			$('div#checklist-dir-list').fadeIn(100);
		});
		$('div.item-list-tabs li.selected').removeClass('loading');

		$('#category').val(bpc('select#category-filter-by option:selected').val());
		$('#status').val(bpc('select#status-filter-by option:selected').val());
		$('#sort').val(bpc('select#sort-order-by option:selected').val());
		$('#itemcount').val(bpc('select#itemcount option:selected').val());
		$('#upage').val(bpc('#pag-top .page-numbers.current').html());
	});
}
