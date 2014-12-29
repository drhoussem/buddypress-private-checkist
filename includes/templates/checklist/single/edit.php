<div id="checklist-edit-form">

<form action="<?php ucc_bpc_edit_form_action(); ?>" method="post" id="edit-form" name="edit-form" role="complementary">
	<input type="hidden" name="<?php ucc_bpc_id_field_name(); ?>" id="<?php ucc_bpc_id_field_name(); ?>" value="<?php echo esc_attr( ucc_bpc_get_id_field_value() ); ?>" />
	<input type="hidden" name="<?php ucc_bpc_is_checkit_field_name(); ?>" id="<?php ucc_bpc_is_checkit_field_name(); ?>" value="0" />
	<input type="hidden" name="<?php ucc_bpc_checkit_field_name(); ?>" id="<?php ucc_bpc_checkit_field_name(); ?>" value="0" />
	<input type="hidden" name="status" id="status" value="0" />
	<input type="hidden" name="category" id="category" value="0" />
	<input type="hidden" name="sort" id="sort" value="0" />
	<input type="hidden" name="upage" id="upage" value="0" />

	<h3><?php ucc_bpc_edit_form_title(); ?> <?php ucc_bpc_bulk_link(); ?></h3>

	<?php do_action( 'ucc_bpc_before_edit_form_content' ); ?>

	<div id="edit-title">
		<label for="<?php ucc_bpc_title_field_name(); ?>"><?php _e( 'Task: ', 'buddypress-private-checklist' ); ?></label> 
		<input type="text" name="<?php ucc_bpc_title_field_name(); ?>" id="<?php ucc_bpc_title_field_name(); ?>" value="<?php echo esc_attr( ucc_bpc_get_title_field_value() ); ?>" />
	</div>

	<div id="edit-content">
		<label for="<?php ucc_bpc_content_field_name(); ?>"><?php _e( 'Notes: ', 'buddypress-private-checklist' ); ?></label><br />
		<textarea name="<?php ucc_bpc_content_field_name(); ?>" id="<?php ucc_bpc_content_field_name(); ?>" cols="50" rows="10"><?php echo esc_html( ucc_bpc_content_field_value() ); ?></textarea>
	</div>

	<div id="edit-options">
		<div id="edit-date">
			<label for="<?php ucc_bpc_date_field_name(); ?>"><?php _e( 'Due date:', 'buddypress-private-checklist' ); ?></label>
			<input type="text" name="<?php ucc_bpc_date_field_name(); ?>" id="<?php ucc_bpc_date_field_name(); ?>" value="<?php echo esc_attr( ucc_bpc_date_field_value() ); ?>"></input>
		</div>

		<div id="edit-category">
			<label for="<?php ucc_bpc_category_field_name(); ?>"><?php _e( 'File in:', 'buddypress-private-checklist' ); ?></label>
			<?php 
			wp_dropdown_categories( array( 
				'taxonomy'      => 'ucc_bpc_category', 
				'name'          => ucc_bpc_get_category_field_name(), 
				'id'            => ucc_bpc_get_category_field_name(), 
				'hide_empty'    => false,
				'hide_if_empty' => true,
				'selected'      => ucc_bpc_get_category_field_value()
			) ); 
			?>
		</div>

		<div id="edit-status">
			<label for="<?php ucc_bpc_status_field_name(); ?>"><?php _e( 'Status:', 'buddypress-private-checklist' ); ?></label>
			<?php 
			wp_dropdown_categories( array(
				'taxonomy'      => 'ucc_bpc_status',
				'name'          => ucc_bpc_get_status_field_name(),
				'id'            => ucc_bpc_get_status_field_name(),
				'hide_empty'    => false,
				'hide_if_empty' => true,
				'selected'      => ucc_bpc_get_status_field_value()
			) ); 
			?>
		</div>

		<?php do_action( 'ucc_bpc_edit_form_options' ); ?>

                <div id="edit-actions">
                        <input type="submit" name="<?php ucc_bpc_submit_field_name(); ?>" id="<?php ucc_bpc_submit_field_name(); ?>" value="<?php ucc_bpc_submit_field_value(); ?>" />
                        <a href="<?php echo user_trailingslashit( ucc_bpc_get_url() ); ?>" class="cancel-edit-link"><?php _e( 'Cancel' ); ?></a>
                </div>
	</div><!-- #edit-options -->

	<?php wp_nonce_field( '_ucc_bpc_action_edit' ); ?>

	<?php do_action( 'uc_bpc_after_edit_form_content' ); ?>

</form><!-- #edit-form -->

</div><!-- #checklist-edit-form -->
