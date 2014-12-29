var bpc = jQuery;
var ucc_bpc_ajax_request = null;

bpc(document).ready( function() {
	var inline;
	var replaced;
	var date;
	var submit;
	var cancel;
	var title;

	bpc('#edit-options').hide();
	bpc('#edit-content').hide();
	date = bpc('#ucc_bpc_date').val();
	submit = bpc('#ucc_bpc_submit').val();
	cancel = bpc('a.cancel-edit-link').html();	
	title = bpc('#edit-form h3').html();

	bpc('#ucc_bpc_title').focus( function(){
		bpc('#edit-content').show();
		bpc('#edit-options').show();
	});

	/* Complete task checkbox. */
	bpc('.ucc_bpc_checkit_cb').live('click', function(event) {
		bpc('#ucc_bpc_id').val(bpc(event.target).val());
		bpc('#ucc_bpc_is_checkit').val(1);
		var c = this.checked ? '1' : '0';
		bpc('#ucc_bpc_checkit').val(c);

		bpc_set_hidden_values();

		bpc('#edit-form').submit();
	});

	/* Edit task button. */
	bpc('a.edit-link').live('click', function(event) {
		event.preventDefault();

		inline = bpc(event.target).parent().siblings('.hidden');
		bpc('#ucc_bpc_id').val(inline.children('.ucc_bpc_id').html());
		bpc('#ucc_bpc_title').val(inline.children('.ucc_bpc_title').html());
		bpc('#ucc_bpc_content').val(inline.children('.ucc_bpc_content').html());
		bpc('#ucc_bpc_category').val(inline.children('.ucc_bpc_category').html());
		bpc('#ucc_bpc_status').val(inline.children('.ucc_bpc_status').html());
		bpc('#ucc_bpc_date').val(inline.children('.ucc_bpc_date').html());

		bpc_set_hidden_values();

		// Deal with form differences between new/edit.
		bpc('#ucc_bpc_submit').val(ucc_bpc.save_task);
		bpc('a.cancel-edit-link').html(ucc_bpc.reset_fields);
		bpc('#edit-form h3').html(ucc_bpc.edit_task);

		replaced = bpc(event.target).parent().parent().parent().replaceWith(bpc('#edit-form'));
		bpc('#edit-content').show();
		bpc("#edit-options").show();

		bpc('a.edit-link').hide();
	});

	/* Cancel edit task button. */
	bpc('a.cancel-edit-link').live('click', function(event) {
		event.preventDefault();

		bpc('#ucc_bpc_id').val(0);
 		bpc('#ucc_bpc_title').val('');
		bpc('#ucc_bpc_content').val('');
		bpc('#ucc_bpc_category').val('');
		bpc('#ucc_bpc_status').val('');
		bpc('#ucc_bpc_date').val(date);

		bpc('#ucc_bpc_submit').val(submit);
		bpc(event.target).html(cancel);
		bpc('#edit-form h3').html(title);

		replaced = bpc('#edit-form').after(replaced);
		bpc('#checklist-edit-form').append(replaced);
		bpc('#edit-content').hide();
		bpc('#edit-options').hide();

		bpc('a.edit-link').show();
	});

	/* Confirm on delete link. */
	bpc('a.doublecheck').live('click', function(event) {
		if ( confirm( 'Are you sure?' ) ) {
			return true;
		} else {
			return false;
		}
	});

	function update_checklist_filters(){
		var status = bpc('select#status-filter-by option:selected').val();
		var category = bpc('select#category-filter-by option:selected').val();
		var sort = bpc('select#sort-order-by option:selected').val();
		var itemcount = bpc('select#itemcount option:selected').val();
		ucc_bpc_request(status, category, sort, itemcount);

		return false;
	}
	
	function bpc_set_hidden_values(){
		bpc('#category').val(bpc('select#category-filter-by option:selected').val());
		bpc('#status').val(bpc('select#status-filter-by option:selected').val());
		bpc('#sort').val(bpc('select#sort-order-by option:selected').val());
		bpc('#itemcount').val(bpc('select#itemcount option:selected').val());
		bpc('#upage').val(bpc('#pag-top .page-numbers.current').html());
	}
	
	/* Sort by date. */
	bpc('select#sort-order-by').change( function() {
		update_checklist_filters();
	});
	
	/* Change items per page. */
	bpc('select#itemcount').change( function() {
		update_checklist_filters();
	});

	/* Filter by task category. */
	bpc('select#category-filter-by').change(function() {
		update_checklist_filters();
	});

	/* Filter by task status. */
	bpc('select#status-filter-by').change(function() {
		update_checklist_filters();
	});

	/* Try for auto-submit. */
	bpc('#ucc_bpc_bulk_submit').live('click', function(event) {
		event.preventDefault();
		bpc('#ucc_bpc_bulk_autosubmit').val(1);
		bpc('#bulk-form').submit();
	});

	/* Auto-submit bulk form. */
	var autosubmit = bpc('#ucc_bpc_bulk_autosubmit').val();
	if (autosubmit > 0) {
		setTimeout(function() {
			bpc('#bulk-form').submit();
		}, 2000);
	}
});

function ucc_bpc_request(status, category, sort, itemcount) {
	/* Save parameters to a session cookie. */
	bpc.cookie( 'ucc-bpc-category', category, {path: '/'} );
	bpc.cookie( 'ucc-bpc-status', status, {path: '/'} );
	bpc.cookie( 'ucc-bpc-sort', sort, {path: '/'} );
	bpc.cookie( 'ucc-bpc-itemcount', itemcount, {path: '/'} );

	/* Set the correct select values. */
	bpc('select#category-filter-by option[value="' + category + '"]').prop( 'selected', true );
	bpc('select#status-filter-by option[value="' + status + '"]').prop( 'selected', true );
	bpc('select#sort-order-by option[value="' + sort + '"]').prop( 'selected', true );
	bpc('select#itemcount option[value="' + itemcount + '"]').prop( 'selected', true );

	/* Reload the task list based on the selections. */
	if ( ucc_bpc_ajax_request )
		ucc_bpc_ajax_request.abort();
	
	var data = {
		action: 'ucc-bpc-filter',
		'category': category,
		'status': status,
		'sort': sort,
		'itemcount' : itemcount,
		'search': bpc('#search').val()
	}

	ucc_bpc_ajax_request = bpc.post( ucc_bpc.ajaxurl, data, function(response) {
		bpc('ul#checklist-list').fadeOut( 100, function() {
			bpc('div#checklist-dir-list').html(response);
			bpc('div#checklist-dir-list').fadeIn(100);
		});
		bpc('div.item-list-tabs li.selected').removeClass('loading');

		bpc('#category').val(bpc('select#category-filter-by option:selected').val());
		bpc('#status').val(bpc('select#status-filter-by option:selected').val());
		bpc('#sort').val(bpc('select#sort-order-by option:selected').val());
		bpc('#itemcount').val(bpc('select#itemcount option:selected').val());
		bpc('#upage').val(bpc('#pag-top .page-numbers.current').html());
	});
}
