<?php get_header( 'buddypress' ); ?>

	<?php do_action( 'ucc_bpc_before_directory_checklist_page' ); ?>

	<div id="content">
		<div class="padder">

		<?php if ( is_user_logged_in() ) : ?>

			<?php do_action( 'ucc_bpc_before_directory_checklist' ); ?>
		
			<?php do_action( 'ucc_bpc_before_edit_form' ); ?>

			<?php ucc_bp_locate_template( 'single/edit.php', true, true, __FILE__ ); ?>

			<?php do_action( 'ucc_bpc_after_edit_form' ); ?>
	
			<?php do_action( 'ucc_bpc_before_checklist_form' ); ?>

			<form action="<?php ucc_bpc_search_form_action(); ?>" method="post" id="checklist-directory-form" class="dir-form">

				<h3><?php _e( 'My Tasks', 'form header', 'buddypress-private-checklist' ); ?></h3>

				<?php do_action( 'ucc_bpc_before_directory_checklist_content' ); ?>

				<div id="checklist-dir-search" class="dir-search" role="search">

				<?php include( 'search.php' ); ?>

				</div><!-- #checklist-dir-search -->

				<div class="item-list-tabs" id="subnav" role="navigation">
					<ul>

						<?php $categories = ucc_bpc_get_category_dropdown(); ?>

						<?php if ( $categories ) : ?>

							<li id="category-select">
							<label for="category-filter-by"><?php _e( 'Show:', 'buddypress-private-checklist' ); ?></label>
							<?php ucc_bpc_category_dropdown(); ?>
							</li>

						<?php endif; ?>

						<?php $statuses = ucc_bpc_get_status_dropdown(); ?>

						<?php if ( $statuses ) : ?>

							<li id="status-select">
							<label for="status-filter-by"><?php _e( 'Filter:', 'buddypress-private-checklist' ); ?></label>
							<?php ucc_bpc_status_dropdown(); ?>
							</li>

						<?php endif; ?>
						<li id="itemcount-select">
							<label for="sort-order-by"><?php _e( 'Items per page:', 'buddypress-private-checklist' ); ?></label>
							<?php ucc_bpc_itemcount_dropdown() ?>
						</li>
						<li id="sort-select" class="last">
							<label for="sort-order-by"><?php _e( 'Sort:', 'buddypress-private-checklist' ); ?></label>
							<?php ucc_bpc_sort_dropdown(); ?>
						</li>
						
						
					<ul>

				</div><!-- .item-list-tabs -->

				<?php do_action( 'ucc_bpc_before_directory_checklist_list' ); ?>

				<div id="checklist-dir-list" class="checklist dir-list" role="main">

					<?php ucc_bp_locate_template( 'checklist/checklist-loop.php', true, true, __FILE__ ); ?>

				</div><!-- #checklist-dir-list -->

				<?php do_action( 'ucc_bpc_after_directory_checklist_list' ); ?>

				<?php do_action( 'ucc_bpc_directory_checklist_content' ); ?>

				<?php wp_nonce_field( '_ucc_bpc_ajax_query' ); ?>

				<?php do_action( 'ucc_bpc_after_directory_checklist_content' ); ?>

			</form><!-- #checklist-directory-form -->

			<?php do_action( 'ucc_bpc_after_checklist_form' ); ?>

			<div id="checklist-dir-tools">

			<?php do_action( 'ucc_bpc_before_checklist_tools' ); ?>

			<?php ucc_bpc_email_link(); ?>

			<?php ucc_bpc_print_link(); ?>

			<?php ucc_bpc_export_link(); ?>

			<?php do_action( 'ucc_bpc_before_checklist_tools' ); ?>

			</div>

			<?php do_action( 'ucc_bpc_after_directory_checklist' ); ?>

		<?php else : ?>

		<?php _e( 'This is only available to logged-in users.', 'buddypress-private-checklist' ); ?>

		<?php endif; ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php do_action( 'ucc_bpc_after_directory_checklist_page' ); ?>

<?php get_sidebar( 'buddypress' ); ?>
<?php get_footer( 'buddypress' ); ?>

