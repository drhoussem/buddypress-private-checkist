<?php

function ucc_bpc_have_tasks( $args = '' ) {
	global $bp, $checklist_template;

	$current_user = $bp->loggedin_user->id; 
	if ( 0 == $current_user )
		return false;

	$action_variables = $bp->action_variables;
	$post_in = null;
	$paged = ( (int) get_query_var( 'upage' ) > 0 ? (int) get_query_var( 'upage' ) : 1 );
	$posts_per_page = apply_filters( 'ucc_bpc_posts_per_page', 10 );
	if ( ! empty( $action_variables ) && ! empty( $bp->current_action ) ) {
		if ( in_array( $bp->current_action, array( 'edit', 'view' ) ) ) {
			$post_in = $action_variables;
		}
	}
	
	if ( empty( $checklist_template ) ) {
		$defaults = array(
			'author'    => $current_user, 
			'post_type' => 'ucc_bpc_task',
			'meta_key'  => '_ucc_bpc_task_date',
			'orderby'   => 'meta_value',
			'order'     => 'ASC',
			'posts_per_page'  => $posts_per_page,
			'paged'	    => $paged,
			'post__in'  => $post_in,
			'tax_query' => array( array(
				'taxonomy' => 'ucc_bpc_status',
				'field' => 'slug',
				'terms' => array( 'completed' ),
				'operator' => 'NOT IN'
			) )
		);

		$r = wp_parse_args( $args, $defaults );

		$checklist_template = new UCC_BuddyPress_Private_Checklist( $r );
	}

	return $checklist_template->have_posts();
}

function ucc_bpc_the_task() {
	global $checklist_template;
	return $checklist_template->the_post();
}

function ucc_bpc_is_component() {
	$is_component = bp_is_current_component( 'checklist' );
	return apply_filters( 'ucc_bpc_is_component', $is_component );
}

function ucc_bpc_slug() {
	echo ucc_bpc_get_slug();
}

function ucc_bpc_get_slug() {
	global $bp;
	$slug = isset( $bp->checklist->root_slug ) ? $bp->checklist->root_slug : '';
	return apply_filters( 'ucc_bpc_get_slug', $slug );
}

function ucc_bpc_url() {
	echo ucc_bpc_get_url();
}

function ucc_bpc_get_url() {
	return apply_filters( 'ucc_bpc_get_url', bp_get_root_domain() . '/' . ucc_bpc_get_slug() );
}

function ucc_bpc_id() {
	echo ucc_bpc_get_id();
}

function ucc_bpc_get_id() {
	global $checklist_template;
	return $checklist_template->post->ID;
}

function ucc_bpc_title() {
	echo ucc_bpc_get_title();
}

function ucc_bpc_get_title() {
	global $checklist_template;
	return $checklist_template->post->post_title;
}

function ucc_bpc_content() {
	echo ucc_bpc_get_content();
}

function ucc_bpc_get_content() {
	global $checklist_template;
	return apply_filters( 'ucc_bpc_content', apply_filters( 'the_content', $checklist_template->post->post_content ) );
}

function ucc_bpc_content_raw() {
	echo ucc_bpc_get_content_raw();
}

function ucc_bpc_get_content_raw() {
	global $checklist_template;
	return $checklist_template->post->post_content;
}

function ucc_bpc_edit_form_action() {
	echo ucc_bpc_get_edit_form_action();
}

function ucc_bpc_get_edit_form_action() {
	return apply_filters( 'ucc_bpc_edit_form_action', user_trailingslashit( trailingslashit( ucc_bpc_get_url() ) . 'edit' ) ); 
}

function ucc_bpc_edit_form_title() {
	echo ucc_bpc_get_edit_form_title();
}

function ucc_bpc_get_edit_form_title() {
	global $bp;

	if ( bp_is_current_action( 'edit' ) )
		return __( 'Save Task', 'edit form', 'buddypress-private-checklist' );
	else
		return __( 'Create Task', 'edit form', 'buddypress-private-checklist' );
}

function ucc_bpc_bulk_form_action() {
	echo ucc_bpc_get_bulk_form_action();
}

function ucc_bpc_get_bulk_form_action() {
	return apply_filters( 'ucc_bpc_bulk_form_action', user_trailingslashit( trailingslashit( ucc_bpc_get_url() ) . 'bulk' ) ); 
}

function ucc_bpc_bulk_date_field_name() {
	echo ucc_bpc_get_bulk_date_field_name();
}

function ucc_bpc_get_bulk_date_field_name() {
	return apply_filters( 'ucc_bpc_bulk_date_field_name', 'ucc_bpc_bulk_date' );
}

function ucc_bpc_bulk_date_field_value() {
	echo ucc_bpc_get_bulk_date_field_value();
}

function ucc_bpc_get_bulk_date_field_value() {
	$value = '';
	if ( isset( $_REQUEST[ucc_bpc_get_bulk_date_field_name()] ) ) {
		$value = strtotime( $_REQUEST[ucc_bpc_get_bulk_date_field_name()] );

		if ( $value ) {
			$value = date( 'm/d/Y', $value ); 
		}
	}
	return apply_filters( 'ucc_bpc_bulk_date_field_value', $value );
}

function ucc_bpc_bulk_autosubmit_field_name() {
	echo ucc_bpc_get_bulk_autosubmit_field_name();
}

function ucc_bpc_get_bulk_autosubmit_field_name() {
	return apply_filters( 'ucc_bpc_bulk_autosubmit_field_name', 'ucc_bpc_bulk_autosubmit' );
}

function ucc_bpc_bulk_autosubmit_field_value() {
	echo ucc_bpc_get_bulk_autosubmit_field_value();
}

function ucc_bpc_get_bulk_autosubmit_field_value() {
	$date = ucc_bpc_get_bulk_date_field_value();
	$value = ( ! empty( $date ) && isset( $_REQUEST['ucc_bpc_bulk_autosubmit'] ) && ( $_REQUEST['ucc_bpc_bulk_autosubmit'] == 1 ) ) ? 1 : 0;
	return apply_filters( 'ucc_bpc_bulk_autosubmit_field_value', $value );
}

function ucc_bpc_bulk_offset_field_name() {
	echo ucc_bpc_get_bulk_offset_field_name();
}

function ucc_bpc_get_bulk_offset_field_name() {
	return apply_filters( 'ucc_bpc_bulk_offset_field_name', 'ucc_bpc_bulk_offset' );
}

function ucc_bpc_bulk_offset_field_value() {
	echo ucc_bpc_get_bulk_offset_field_value();
}

function ucc_bpc_get_bulk_offset_field_value() {
	$date = ucc_bpc_get_bulk_date_field_value();
	$value = ( ! empty( $date ) && isset( $_REQUEST['ucc_bpc_bulk_offset'] ) ) ? (int) $_REQUEST['ucc_bpc_bulk_offset'] + 1 : 0;
	return apply_filters( 'ucc_bpc_bulk_autosubmit_field_value', $value );
}

function ucc_bpc_email_address_field_name() {
	echo ucc_bpc_get_email_address_field_name();
}

function ucc_bpc_get_email_address_field_name() {
	return apply_filters( 'ucc_bpc_email_address_field_name', 'ucc_bpc_email_address' );
}

function ucc_bpc_email_address_field_value() {
	echo ucc_bpc_get_email_address_field_value();
}

function ucc_bpc_get_email_address_field_value() {
	$email_address = false;
	if ( isset( $_REQUEST ) && isset( $_REQUEST['ucc_bpc_email_address'] ) && ! empty( $_REQUEST['ucc_bpc_email_address'] ) ) {
		if ( is_email( $_REQUEST['ucc_bpc_email_address'] ) ) {
			$email_address = $_REQUEST['ucc_bpc_email_address'];
		}
	}
	return apply_filters( 'ucc_bpc_email_address_field_value', $email_address );
}

function ucc_bpc_user_can_view( $task = false ) {
	global $checklist_template, $bp;

	if ( ! $task )
		$task = $checklist_template->post;
	
	$can_view = false;

	if ( $bp->loggedin_user->is_super_admin )
		$can_view = true;

	if ( $task->post_author == $bp->loggedin_user->id )
		$can_view = true;
	
	if ( $bp->is_item_admin && $bp->is_single_item )
		$can_view = true;

	return apply_filters( 'ucc_bpc_user_can_view', $can_view, $task );
}

function ucc_bpc_user_can_delete( $task = false ) {
	global $checklist_template, $bp;

	if ( ! $task )
		$task = $checklist_template->post;

	$can_delete = false;

	if ( $bp->loggedin_user->is_super_admin )
		$can_delete = true;

	if ( $task->post_author == $bp->loggedin_user->id )
		$can_delete = true;

	if ( $bp->is_item_admin && $bp->is_single_item )
		$can_delete = true;

	return apply_filters( 'ucc_bpc_user_can_delete', $can_delete, $task );
}

function ucc_bpc_delete_link() {
	echo ucc_bpc_get_delete_link();
}

function ucc_bpc_get_delete_link() {
	global $checklist_template;

	$url   = user_trailingslashit( trailingslashit( ucc_bpc_get_url() ) . 'delete/' . $checklist_template->post->ID );
	$class = 'delete-link';

	$args = ucc_bpc_get_referer_vars();

	$link = '<a href="' . add_query_arg( $args, wp_nonce_url( $url, '_ucc_bpc_action_delete' ) ) . '" class="bp-secondary-action ' . $class . ' doublecheck" rel="nofollow">' . __( 'Delete this task', 'buddypress' ) . '</a>';
	return apply_filters( 'ucc_bpc_delete_link', $link );
}

function ucc_bpc_user_can_edit( $task = false ) {
	global $checklist_template, $bp;

	if ( ! $task )
		$task = $checklist_template->post;

	$can_edit = false;

	if ( $bp->loggedin_user->is_super_admin )
		$can_edit = true;

	if ( $task->post_author == $bp->loggedin_user->id )
		$can_edit = true;

	if ( $bp->is_item_admin && $bp->is_single_item )
		$can_edit = true;

	return apply_filters( 'ucc_bpc_user_can_edit', $can_edit, $task );
}

function ucc_bpc_edit_link() {
	echo ucc_bpc_get_edit_link();
}

function ucc_bpc_get_edit_link() {
	global $checklist_template;

	$url = user_trailingslashit( trailingslashit( ucc_bpc_get_url() ) . 'edit/' . $checklist_template->post->ID );
	$class = 'edit-link';

	$link = '<a href="' . wp_nonce_url( $url, '_ucc_bpc_edit_form_nonce' ) . '" class="button item-button bp-secondary-action ' . $class . '" rel="nofollow">' . __( 'Edit', 'buddypress-private-checklist' ) . '</a>';

	return apply_filters( 'ucc_bpc_edit_link', $link );
}

function ucc_bpc_bulk_link() {
	echo ucc_bpc_get_bulk_link();
}

function ucc_bpc_get_bulk_link() {
	global $current_user;

	// Only display if there are items to bulk add.
	$options = get_option( 'ucc_bpc_options' );
	if ( ! empty( $options ) && isset( $options['bulk_add_tasks'] ) )
		$csv = $options['bulk_add_tasks'];
	if ( empty( $csv ) )
		return;

	// Only display if we haven't timed out.
	$user_has_timeout = get_user_meta( $current_user->ID, '_ucc_bpc_action_bulk_timeout', true );
	$timeout = apply_filters( 'ucc_bpc_action_bulk_timeout', 60 * 5 );
	$now = time();
	if ( $user_has_timeout && (int) $user_has_timeout + $timeout < $now )
		return;


	// Only display if we can bulk add.
	$user_has_added = get_user_meta( $current_user->ID, '_ucc_bpc_action_bulk', true );
	if ( $user_has_added )
		return;

	$url = user_trailingslashit( trailingslashit( ucc_bpc_get_url() ) . 'bulk' );
	$class = 'bulk-link';

	$link = '<a href="' . $url . '" class="button item-button bp-secondary-action ' . $class . '" rel="nofollow">' . __( 'Add Default Tasks', 'buddypress-private-checklist' ) . '</a>';

	return apply_filters( 'ucc_bpc_bulk_link', $link );
}

function ucc_bpc_export_link() {
	echo ucc_bpc_get_export_link();
}

function ucc_bpc_get_export_link() {
	global $current_user;

	$url = user_trailingslashit( trailingslashit( ucc_bpc_get_url() ) . 'export' );
	$class = 'export-link';
	$args = ucc_bpc_get_referer_vars();

	$link = '<a href="' . add_query_arg( $args, wp_nonce_url( $url, '_ucc_bpc_action_export' ) ) . '" class="button item-buttom bp-secondary-action ' . $class . '" rel="nofollow">' . __( 'Export My Tasks (Plaintext CSV)', 'buddypress-private-checklist' ) . '</a>';

	return apply_filters( 'ucc_bpc_export_link', $link );
}

function ucc_bpc_email_link() {
        echo ucc_bpc_get_email_link();
}

function ucc_bpc_get_email_link() {
        global $current_user;

        $url = user_trailingslashit( trailingslashit( ucc_bpc_get_url() ) . 'email' );
        $class = 'email-link';
        $args = ucc_bpc_get_referer_vars();

        $link = '<a href="' . add_query_arg( $args, wp_nonce_url( $url, '_ucc_bpc_action_email' ) ) . '" class="button item-buttom bp-secondary-action ' . $class . '" rel="nofollow">' . __( 'Email My Tasks to Me', 'buddypress-private-checklist' ) . '</a>';

        return apply_filters( 'ucc_bpc_email_link', $link );
}

function ucc_bpc_print_link() {
        echo ucc_bpc_get_print_link();
}

function ucc_bpc_get_print_link() {
        global $current_user;

        $url = user_trailingslashit( trailingslashit( ucc_bpc_get_url() ) . 'print' );
        $class = 'print-link';
        $args = ucc_bpc_get_referer_vars();

        $link = '<a href="' . add_query_arg( $args, wp_nonce_url( $url, '_ucc_bpc_action_print' ) ) . '" class="button item-buttom bp-secondary-action ' . $class . '" rel="nofollow">' . __( 'Print My Tasks', 'buddypress-private-checklist' ) . '</a>';

        return apply_filters( 'ucc_bpc_print_link', $link );
}

function ucc_bpc_pagination() {
	echo ucc_bpc_get_pagination();
}

function ucc_bpc_get_pagination() {
	global $checklist_template;
	return apply_filters( 'ucc_bpc_pagination', $checklist_template->pagination_links );
}

function ucc_bpc_category_id( $post_id = 0 ) {
	echo ucc_bpc_get_category_id( $post_id );
}

function ucc_bpc_get_category_id( $post_id = 0 ) {
	global $checklist_template;

	if ( empty( $post_id ) )
		$post_id = $checklist_template->post->ID;

	if ( empty( $post_id ) )
		return;

	$terms = get_the_terms( $post_id, 'ucc_bpc_category' );
	if ( is_array( $terms ) ) {
		foreach ( $terms as $t )
			$term = $t->term_id;
		return $term;
	} else {
		return 0;
	}
}

function ucc_bpc_category( $post_id = 0 ) {
	echo ucc_bpc_get_category( $post_id );
}

function ucc_bpc_get_category( $post_id = 0 ) {
	global $checklist_template;

	if ( empty( $post_id ) )
		$post_id = $checklist_template->post->ID;

	if ( empty( $post_id ) )
		return;

	$terms = get_the_terms( $post_id, 'ucc_bpc_category' );
	if ( is_array( $terms ) ) {
		foreach ( $terms as $t )
			$term = $t->name;
		return $term;
	} else {
		return __( 'No category', 'buddypress-private-checklist' );
	}
}	

function ucc_bpc_category_slug( $post_id = 0 ) {
	echo ucc_bpc_get_category_slug( $post_id );
}

function ucc_bpc_get_category_slug( $post_id = 0 ) {
	global $checklist_template;

	if ( empty( $post_id ) )
		$post_id = $checklist_template->post->ID;

	if ( empty( $post_id ) )
		return;

	$terms = get_the_terms( $post_id, 'ucc_bpc_category' );
	if ( is_array( $terms ) ) {
		foreach ( $terms as $t )
			$term = $t->slug;
		return $term;
	} else {
		return __( 'no-category', 'buddypress-private-checklist' );
	}
}

function ucc_bpc_category_dropdown() {
	echo ucc_bpc_get_category_dropdown();
}

function ucc_bpc_get_category_dropdown() {
	$terms = get_terms( 'ucc_bpc_category', array( 
		'fields' => 'ids', 
		'hide_empty' => false 
	) );

	if ( ! empty( $terms ) ) {
		if ( isset( $_REQUEST['category'] ) && ( (int) $_REQUEST['category'] > 0 ) )
			$selected = absint( $_REQUEST['category'] );
		else
			$selected = 0;
		$args = array(
			'id' => 'category-filter-by',
			'name' => 'category-filter-by',
			'show_option_all' => __( 'All categories', 'buddypress-private-checklist' ),
			'echo' => false,
			'taxonomy' => 'ucc_bpc_category',
			'hide_empty' => false,
			'selected' => $selected
		);
		return wp_dropdown_categories( $args );
	} else {
		return false;
	}
}

function ucc_bpc_status_id( $post_id = 0 ) {
	echo ucc_bpc_get_status_id( $post_id );
}

function ucc_bpc_get_status_id( $post_id = 0 ) {
	global $checklist_template;

	if ( empty( $post_id ) )
		$post_id = $checklist_template->post->ID;

	if ( empty( $post_id ) )
		return;

	$terms = get_the_terms( $post_id, 'ucc_bpc_status' );
	if ( is_array( $terms ) ) {
		foreach ( $terms as $t )
			$term = $t->term_id;
		return $term;
	} else {
		return 0;
	}
}

function ucc_bpc_status_slug( $post_id = 0 ) {
	echo ucc_bpc_get_status_slug( $post_id = 0 );
}

function ucc_bpc_get_status_slug( $post_id = 0 ) {
	global $checklist_template;

	if ( empty( $post_id ) )
		$post_id = $checklist_template->post->ID;

	if ( empty( $post_id ) )
		return;

	$terms = get_the_terms( $post_id, 'ucc_bpc_status' );
	if ( is_array( $terms ) ) {
		foreach ( $terms as $t )
			$term = $t->slug;
		return $term;
	} else {
		return __( 'no-status', 'buddypress-private-checklist' );
	}
}

function ucc_bpc_status( $post_id = 0 ) {
	echo ucc_bpc_get_status( $post_id );
}

function ucc_bpc_get_status( $post_id = 0 ) {
	global $checklist_template;

	if ( empty( $post_id ) )
		$post_id = $checklist_template->post->ID;

	if ( empty( $post_id ) )
		return;

	$terms = get_the_terms( $post_id, 'ucc_bpc_status' );
	if ( is_array( $terms ) ) {
		foreach ( $terms as $t )
			$term = $t->name;
		return $term;
	} else {
		return __( 'No status', 'buddypress-private-checklist' );
	}
}

function ucc_bpc_status_dropdown() {
	echo ucc_bpc_get_status_dropdown();
}

function ucc_bpc_get_status_dropdown() {
	$terms = get_terms( 'ucc_bpc_status', array(
		'fields' => 'ids',
		'hide_empty' => false
	) );

	if ( ! empty( $terms ) ) {
		if ( isset( $_REQUEST['status'] ) && ( (int) $_REQUEST['status'] > 0 ) )
			$selected = absint( $_REQUEST['status'] );
		elseif ( isset( $_REQUEST['status'] ) && in_array( $_REQUEST['status'], array( 'overdue', 'all' ) ) )
			$selected = 'none';
		else
			$selected = 0;
		$args = array(
			'id' => 'status-filter-by',
			'name' => 'status-filter-by',
			'echo' => false,
			'taxonomy' => 'ucc_bpc_status',
			'hide_empty' => false,
			'selected' => $selected
		);

		$dropdown = wp_dropdown_categories( $args );

		// Add 'overdue' and 'all' as options.
		$select = "<select name='status-filter-by' id='status-filter-by' class='postform' >";
		$selected_overdue = "<option value='overdue' selected='selected'>" . __( 'Overdue tasks', 'buddypress-private-checklist' ) . "</option>\n";
		$overdue = "<option value='overdue'>" . __( 'Overdue tasks', 'buddypress-private-checklist' ) . "</option>\n";
		$selected_all = "<option value='all' selected='selected'>" . __( 'All tasks', 'buddypress-private-checklist' ) . "</option>\n";
		$all = "<option value='all'>" . __( 'All tasks', 'buddypress-private-checklist' ) . "</option>\n";

		if ( isset( $_REQUEST['status'] ) && ( 'overdue' == $_REQUEST['status'] ) )
			$overdue = $selected_overdue;
		if ( isset( $_REQUEST['status'] ) && ( 'all' == $_REQUEST['status'] ) )
			$all = $selected_all;
		$dropdown = str_replace( $select, $select . $overdue . $all, $dropdown );

		// Add 'incomplete' as an option.
		$selected_incomplete = "<option value='0' selected='selected'>" . __( 'All unfinished tasks', 'buddypress-private-checklist' ) . "</option>\n";
		$incomplete = "<option value='0'>" . __( 'All unfinished tasks', 'buddypress-private-checklist' ) . "</option>\n";

		if ( ! isset( $_REQUEST['status'] ) || isset( $_REQUEST['status'] ) && ( '0' == $_REQUEST['status'] ) )
			$incomplete = $selected_incomplete;
		$dropdown = str_replace( $select, $select . $incomplete, $dropdown );

		return $dropdown;
	} else {
		return false;
	}
}

function ucc_bpc_sort_dropdown() {
	echo ucc_bpc_get_sort_dropdown();
}

function ucc_bpc_get_sort_dropdown() {
	if ( isset( $_REQUEST['sort'] ) && in_array( $_REQUEST['sort'], array( 'date_due_asc', 'date_due_desc' ) ) )
		$selected = $_REQUEST['sort'];
	else
		$selected = '';
	?>
	<select id="sort-order-by">
		<option value="date_due_asc" <?php selected( $selected, 'date_due_asc' ); ?>>Date due (earliest first)</option>
		<option value="date_due_desc" <?php selected( $selected, 'date_due_desc' ); ?>>Date due (latest first)</option>
	</select>
	<?php
}

function ucc_bpc_itemcount_dropdown() {
	echo ucc_bpc_get_itemcount_dropdown();
}

function ucc_bpc_get_itemcount_dropdown() {
	$itemcount_options = array( '10','15','20','30','50' );
	if ( isset( $_REQUEST['itemcount'] ) && in_array( $_REQUEST['itemcount'], $itemcount_options ) )
		$selected = $_REQUEST['itemcount'];
	else
		$selected = '';
	?>
	<select id="itemcount">
		<?php foreach ($itemcount_options as $option) {
				echo '<option value="'.$option.'" '.selected( $selected, $option ).'>'.$option.'</option>';
			} ?>
	</select>
	<?php
}

function ucc_bpc_checked_slug() {
	echo ucc_bpc_get_checked_slug();
}

function ucc_bpc_get_checked_slug() {
	return apply_filters( 'ucc_bpc_checked_slug', 'completed' );
}

function ucc_bpc_date( $post_id = 0 ) {
	echo ucc_bpc_get_date( $post_id );
}

function ucc_bpc_get_date( $post_id = 0 ) {
	global $checklist_template;

	if ( empty( $post_id ) )
		$post_id = $checklist_template->post->ID;

	if ( empty( $post_id ) )
		return;

	$date = get_post_meta( $post_id, '_ucc_bpc_task_date', true );
	if ( $date )
		$date = date( 'm/d/Y', $date );
	else 
		$date = date( 'm/d/Y' );

	return $date;
}

function ucc_bpc_is_overdue( $post_id = 0 ) {
	global $checklist_template;

	if ( empty( $post_id ) )
		$post_id = $checklist_template->post->ID;

	if ( empty( $post_id ) )
		return;

	$date = ucc_bpc_time( $post_id );
	$now = time();

	if ( $date < $now )
		return true;
	else
		return false;
}

function ucc_bpc_state( $post_id = 0 ) {
	echo ucc_bpc_get_state( $post_id );
}

function ucc_bpc_get_state( $post_id = 0 ) {
        global $checklist_template;

        if ( empty( $post_id ) )
                $post_id = $checklist_template->post->ID;

        if ( empty( $post_id ) )
                return;

	if ( ucc_bpc_is_overdue( $post_id ) )
		return 'overdue';
	else
		return 'on-time';
}

function ucc_bpc_time( $post_id = 0 ) {
	global $checklist_template;

	if ( empty( $post_id ) )
		$post_id = $checklist_template->post->ID;

	if ( empty( $post_id ) )
		return;

	$time = (int) get_post_meta( $post_id, '_ucc_bpc_task_date', true );

	return $time;
}

function ucc_bpc_ajax_query() {
	global $bp;
	$args = array();

	if ( empty( $_REQUEST ) )
		return;

	$args = array();

	if ( isset( $_REQUEST['status'] ) && ( $_REQUEST['status'] == 'overdue' ) ) { 
		$now = time();
		$args['meta_query'][] = array(
			'key' => '_ucc_bpc_task_date',
			'value' => $now,
			'compare' => '<',
			'type' => 'NUMERIC'
		);

		$args['tax_query'][] = array(
			'taxonomy' => 'ucc_bpc_status',
			'field' => 'slug',
			'terms' => array( 'completed' ),
			'operator' => 'NOT IN'
		);
	} elseif ( isset( $_REQUEST['status'] ) && ( $_REQUEST['status'] == 'all' ) ) {
		$args['tax_query'] = array();
	} elseif ( isset( $_REQUEST['status'] ) && ( (int) $_REQUEST['status'] > 0 ) ) { 
		$args['tax_query'][] = array(
			'taxonomy' => 'ucc_bpc_status',
			'field'    => 'id',
			'terms'    => array( $_REQUEST['status'] )
		);
	} else {
		$args['tax_query'][] = array(
			'taxonomy' => 'ucc_bpc_status',
			'field' => 'slug',
			'terms' => array( 'completed' ),
			'operator' => 'NOT IN'
		);
	}

	if ( isset( $_REQUEST['category'] ) && ( (int) $_REQUEST['category'] > 0 ) ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'ucc_bpc_category',
			'field' => 'id',
			'terms' => array( $_REQUEST['category'] )
		);
	}

	if ( isset( $_REQUEST['sort'] ) && in_array( $_REQUEST['sort'], array( 'date_due_asc', 'date_due_desc' ) ) ) {
		if ( 'date_due_asc' == $_REQUEST['sort'] ) {
			$args['order'] = 'ASC';
		} else {
			$args['order'] = 'DESC';
		}
	}

	if ( array_key_exists( 'tax_query', $args ) ) {
		$a = $args['tax_query'];
		$a = array_merge( array( 'relation' => 'AND' ), $a );
		$args['tax_query'] = $a;
	}

	if ( isset( $_REQUEST['search'] ) && ! empty( $_REQUEST['search'] ) ) {
		if ( trim( $_REQUEST['search'] ) != trim( ucc_bpc_get_search_text_default_field_value() ) ) { 
			$args['s'] = $_REQUEST['search'];
		}
	}

	if ( isset( $_REQUEST['upage'] ) )
		$args['paged'] = absint( $_REQUEST['upage'] );

	return $args;
}

function ucc_bpc_get_referer_vars() {
	$args = array();

	if ( isset( $_REQUEST['sort'] ) && in_array( $_REQUEST['sort'], array( 'date_due_asc', 'date_due_desc' ) ) )
		$args['sort'] = $_REQUEST['sort'];

	if ( isset( $_REQUEST['category'] ) && ! empty( $_REQUEST['category'] ) )
		$args['category'] = absint( $_REQUEST['category'] );

	if ( isset( $_REQUEST['status'] ) && ! empty( $_REQUEST['status'] ) ) {
		if ( $_REQUEST['status'] == 'overdue' )
			$args['status'] = 'overdue';
		elseif ( $_REQUEST['status'] = 'all' )
			$args['status'] = 'all';
		else
			$args['status'] = absint( $_REQUEST['status'] );
	}

	if ( isset( $_REQUEST['search'] ) && ! empty( $_REQUEST['search'] ) ) {
		if ( trim( $_REQUEST['search'] ) != trim( ucc_bpc_get_search_text_default_field_value() ) ) {
			$args['search'] = $_REQUEST['search'];
		}
	}
	if ( isset( $_REQUEST['itemcount'] ) && is_numeric( $_REQUEST['itemcount'] ) )
		$args['itemcount'] = $_REQUEST['itemcount'];
		
	if ( isset( $_REQUEST['upage'] ) && ! empty( $_REQUEST['upage'] ) )
		$args['upage'] = absint( $_REQUEST['upage'] );

	return $args;
}

function ucc_bpc_get_referer_url( $args = array() ) {
	$url = user_trailingslashit( ucc_bpc_get_url() );
	$url = add_query_arg( $args, $url );
	return $url;
}

function ucc_bpc_id_field_name() {
	echo ucc_bpc_get_id_field_name();
}

function ucc_bpc_get_id_field_name() {
	return apply_filters( 'ucc_bpc_id_field_name', 'ucc_bpc_id' );
}

function ucc_bpc_id_field_value() {
	echo ucc_bpc_get_id_field_value();
}

function ucc_bpc_get_id_field_value() {
	global $bp;

	if ( bp_is_current_action( 'edit' ) )
		$action_variables = (array) $bp->action_variables;

	$id = 0;
	if ( ! empty( $action_variables ) ) {
		$post = get_post( absint( $action_variables[0] ) );
		if ( $post )
			$id = $post->ID;
	}

	return apply_filters( 'ucc_bpc_id_field_value', $id ); 
}

function ucc_bpc_title_field_name() {
	echo ucc_bpc_get_title_field_name();
}

function ucc_bpc_get_title_field_name() {
	return apply_filters( 'ucc_bpc_title_field_name', 'ucc_bpc_title' );
}

function ucc_bpc_title_field_value() {
	echo ucc_bpc_get_title_field_value();
}

function ucc_bpc_get_title_field_value() {
	global $bp;

	if ( bp_is_current_action( 'edit' ) )
		$action_variables = (array) $bp->action_variables;

	$title = '';
	if ( ! empty( $action_variables ) ) {
		$post = get_post( absint( $action_variables[0] ) );
		if ( $post )
			$title = $post->post_title;
	} 

	return apply_filters( 'ucc_bpc_title_field_value', $title );
}

function ucc_bpc_content_field_name() {
	echo ucc_bpc_get_content_field_name();
}

function ucc_bpc_get_content_field_name() {
	return apply_filters( 'ucc_bpc_content_field_name', 'ucc_bpc_content' );
}

function ucc_bpc_content_field_value() {
	echo ucc_bpc_get_content_field_value();
}

function ucc_bpc_get_content_field_value() {
	global $bp;

	if ( bp_is_current_action( 'edit' ) )
		$action_variables = (array) $bp->action_variables;

	$content = '';
	if ( ! empty( $action_variables ) ) {
		$post = get_post( absint( $action_variables[0] ) );
		if ( $post )
			$content = $post->post_content;
	}

	return apply_filters( 'ucc_bpc_content_field_value', $content );
}

function ucc_bpc_category_field_name() {
	echo ucc_bpc_get_category_field_name();
}

function ucc_bpc_get_category_field_name() {
	return apply_filters( 'ucc_bpc_category_field_name', 'ucc_bpc_category' );
}

function ucc_bpc_category_field_value() {
	echo ucc_bpc_get_category_field_value();
}

function ucc_bpc_get_category_field_value() {
	global $bp;

	if ( bp_is_current_action( 'edit' ) )
		$action_variables = (array) $bp->action_variables;

	$category = 0;
	if ( ! empty( $action_variables ) ) {
		$category = ucc_bpc_get_category_id( $action_variables[0] );
	}

	return apply_filters( 'ucc_bpc_category_field_value', $category );
}

function ucc_bpc_status_field_name() {
	echo ucc_bpc_get_status_field_name();
}

function ucc_bpc_get_status_field_name() {
	return apply_filters( 'ucc_bpc_status_field_name', 'ucc_bpc_status' );
}

function ucc_bpc_status_field_value() {
	echo ucc_bpc_get_status_field_value();
}

function ucc_bpc_get_status_field_value() {
	global $bp;

	if ( bp_is_current_action( 'edit' ) )
		$action_variables = (array) $bp->action_variables;

	$status = 0;
	if ( ! empty( $action_variables ) ) {
		$status = ucc_bpc_get_status_id( $action_variables[0] );
	}

	return apply_filters( 'ucc_bpc_status_field_value', $status );
}

function ucc_bpc_date_field_name() {
	echo ucc_bpc_get_date_field_name();
}

function ucc_bpc_get_date_field_name() {
	return apply_filters( 'ucc_bpc_date_field_name', 'ucc_bpc_date' );
}

function ucc_bpc_date_field_value() {
	echo ucc_bpc_get_date_field_value();
}

function ucc_bpc_get_date_field_value() {
	global $bp;

	if ( bp_is_current_action( 'edit' ) )
		$action_variables = (array) $bp->action_variables;

	if ( ! empty( $action_variables ) )
		$date = ucc_bpc_get_date( $action_variables[0] );
	else
		$date = date( 'm/d/Y' );

	return apply_filters( 'ucc_bpc_date_field_value', $date );
}

function ucc_bpc_submit_field_name() {
	echo ucc_bpc_get_submit_field_name();
}

function ucc_bpc_get_submit_field_name() {
	if ( bp_is_current_action( 'bulk' ) )
		return apply_filters( 'ucc_bpc_submit_field_name', 'ucc_bpc_bulk_submit' );
	elseif ( bp_is_current_action( 'email' ) )
		return apply_filters( 'ucc_bpc_submit_field_name', 'ucc_bpc_email_submit' );
	else
		return apply_filters( 'ucc_bpc_submit_field_name', 'ucc_bpc_submit' );
}

function ucc_bpc_submit_field_value() {
	echo ucc_bpc_get_submit_field_value();
}

function ucc_bpc_get_submit_field_value() {
	global $bp;

	if ( bp_is_current_action( 'bulk' ) )
		$value = __( 'Bulk Add Tasks', 'buddypress-private-checklist' );
	elseif ( bp_is_current_action( 'edit' ) )
		$value = __( 'Save Task', 'buddypress-private-checklist' );
	elseif ( bp_is_current_action( 'email' ) )
		$value = __( 'Email Tasks', 'buddypress-private-checklist' );
	else
		$value = __( 'Create Task', 'buddypress-private-checklist' );

	return apply_filters( 'ucc_bpc_submit_field_value', $value );
}

function ucc_bpc_is_checkit_field_name() {
	echo ucc_bpc_get_is_checkit_field_name();
}

function ucc_bpc_get_is_checkit_field_name() {
	return apply_filters( 'ucc_bpc_is_checkit', 'ucc_bpc_is_checkit' );
}

function ucc_bpc_checkit_field_name() {
	echo ucc_bpc_get_checkit_field_name();
}

function ucc_bpc_get_checkit_field_name() {
	return apply_filters( 'ucc_bpc_checkit_field_name', 'ucc_bpc_checkit' );
}

function ucc_bpc_is_search() {
	global $checklist_template;

	if ( $checklist_template->is_search )
		return true;
	else
		return false;
}

function ucc_bpc_search_text_field_name() {
	echo ucc_bpc_get_search_text_field_name();
}

function ucc_bpc_get_search_text_field_name() {
	return apply_filters( 'ucc_bpc_search_text_field_name', 'search' );
}

function ucc_bpc_search_text_field_value() {
	echo ucc_bpc_get_search_text_field_value();
}

function ucc_bpc_get_search_text_field_value() {
	if ( isset( $_REQUEST ) && isset( $_REQUEST[ucc_bpc_get_search_text_field_name()] ) )
		$value = trim( $_REQUEST[ucc_bpc_get_search_text_field_name()] );
	else
		$value = ucc_bpc_get_search_text_default_field_value();

	return apply_filters( 'ucc_bpc_search_text_field_value', $value );
}	

function ucc_bpc_search_submit_field_name() {
	echo ucc_bpc_get_search_submit_field_name();
}

function ucc_bpc_get_search_submit_field_name() {
	return apply_filters( 'ucc_bpc_search_submit_field_name', 'ucc_bpc_search_submit' );
}

function ucc_bpc_search_submit_field_value() {
	echo ucc_bpc_get_search_submit_field_value();
}

function ucc_bpc_get_search_submit_field_value() {
	return apply_filters( 'ucc_bpc_search_submit_field_value', __( 'Search', 'buddypress-private-checklist' ) );
}

function ucc_bpc_search_text_default_field_value() {
	echo ucc_bpc_get_search_text_default_field_value();
}

function ucc_bpc_get_search_text_default_field_value() {
	return apply_filters( 'ucc_bpc_search_text_default_field_value', __( 'Search my tasks...', 'buddypress-private-checklist' ) );
}

function ucc_bpc_search_form_action() {
	echo ucc_bpc_get_search_form_action();
}

function ucc_bpc_get_search_form_action() {
	return apply_filters( 'ucc_bpc_search_form_action', user_trailingslashit( ucc_bpc_get_url() ) );
}

function ucc_bpc_ajax_category_field_name() {
	echo ucc_bpc_get_ajax_category_field_name();
}

function ucc_bpc_get_ajax_category_field_name() {
	return apply_filters( 'ucc_bpc_ajax_category_field_name', 'category' );
}

function ucc_bpc_ajax_status_field_name() {
	echo ucc_bpc_get_ajax_status_field_name();
}

function ucc_bpc_get_ajax_status_field_name() {
	return apply_filters( 'ucc_bpc_ajax_status_field_name', 'status' );
}

function ucc_bpc_ajax_sort_field_name() {
	echo ucc_bpc_get_ajax_sort_field_name();
}

function ucc_bpc_get_ajax_sort_field_name() {
	return apply_filters( 'ucc_bpc_ajax_sort_field_name', 'sort' );
}

function ucc_bpc_human_time_diff( $time, $end ) {
	echo ucc_bpc_get_human_time_diff( $time, $end );
}

function ucc_bpc_get_human_time_diff( $time, $now ) {
	$set = time();
	if ( ! is_int( $time ) )
		$time = $set;
	if ( ! is_int( $now ) )
		$now = $set;

	if ( $time < $now ) {
		return human_time_diff( $time, $now ) . ' ago';
	} elseif ( $time > $now ) {
		return 'in ' . human_time_diff( $time, $now );	
	} else {
		return human_time_diff( $time, $now );
	}
}

function ucc_bpc_custom_posts_per_page($arg){
	if (is_numeric($_REQUEST['itemcount'])) return $_REQUEST['itemcount'];
	return $arg;
}
add_filter('ucc_bpc_posts_per_page','ucc_bpc_custom_posts_per_page');