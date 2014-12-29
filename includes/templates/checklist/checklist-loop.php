<?php do_action( 'bp_before_checklist_loop' ); ?>

<?php if ( ucc_bpc_have_tasks( ucc_bpc_ajax_query() ) ) : ?>

	<?php do_action( 'template_notices' ); ?>

	<?php if ( ucc_bpc_is_search() ) : ?>

		<div id="search-results" class="updated">

		<p><?php printf( __( 'You searched for <strong>%1$s</strong>. <a href="%2$s">Clear search parameters.</a>', 'buddypress-private-checklist' ), esc_html( ucc_bpc_get_search_text_field_value() ), user_trailingslashit( ucc_bpc_get_url() ) ); ?>

		</div><!-- .search-results -->

	<?php endif; ?>

	<div id="pag-top" class="pagination">

	<?php ucc_bpc_pagination(); ?>

	</div>

	<?php do_action( 'bp_before_directory_checklist_list' ); ?>

	<ul id="checklist-list" class="item-list" role="main">

	<?php while ( ucc_bpc_have_tasks() ) : ucc_bpc_the_task(); ?>

		<?php ucc_bp_locate_template( array( 'checklist/task.php' ), true, false, __FILE__ ); ?>

	<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_checklist_list' ); ?>

	<div id="pag-bottom" class="pagination">

	<?php ucc_bpc_pagination(); ?>

	</div>

<?php else: ?>

	<?php do_action( 'template_notices' ); ?>

	<?php do_action( 'ucc_bpc_before_directory_checklist_list_empty' ); ?>

        <ul id="checklist-list">

        <li>

        <?php do_action( 'bp_before_directory_checklist_empty' ); ?>

        <div id="message" class="info">
        
        <p><?php _e( 'No tasks found.', 'buddypress-private-checklist' ); ?></p>
        
        </div>

        <?php do_action( 'bp_after_directory_checklist_empty' ); ?>

        </li>

        </ul>

	<?php do_action( 'ucc_bpc_after_directory_checklist_list_empty' ); ?>

<?php endif; ?>

<?php do_action( 'bp_after_checklist_loop' ); ?>
