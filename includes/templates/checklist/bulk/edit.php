<form action="" method="post" id="bulk-form" name="bulk-form" role="complementary">
	<input type="hidden" name="<?php ucc_bpc_bulk_offset_field_name(); ?>" id="<?php ucc_bpc_bulk_offset_field_name(); ?>" value="<?php ucc_bpc_bulk_offset_field_value(); ?>" />
	<input type="hidden" name="<?php ucc_bpc_bulk_autosubmit_field_name(); ?>" id="<?php ucc_bpc_bulk_autosubmit_field_name(); ?>" value="<?php ucc_bpc_bulk_autosubmit_field_value(); ?>" />

	<h3><?php _e( 'Add Default Tasks', 'buddypress-private-checklist' ); ?></h3>

	<?php do_action( 'ucc_bpc_before_bulk_form_content' ); ?>

	<div id="bulk-options">

	<?php $date = ucc_bpc_get_bulk_date_field_value(); ?>
	
	<?php if ( ! empty( $date ) ) : ?>

                <div id="bulk-actions">
                        <input type="submit" name="<?php ucc_bpc_submit_field_name(); ?>" id="<?php ucc_bpc_submit_field_name(); ?>" value="<?php ucc_bpc_submit_field_value(); ?>" />
                </div><!-- #bulk-actions -->

		<p><?php _e( 'Please wait for the screen to refresh.', 'buddypress-private-checklist' ); ?></p>

		<input type="hidden" name="<?php ucc_bpc_bulk_date_field_name(); ?>" id="<?php ucc_bpc_bulk_date_field_name(); ?>" value="<?php echo esc_attr( $date ); ?>" />

	<?php else : ?>

                <div id="bulk-actions">
                        <input type="submit" name="<?php ucc_bpc_submit_field_name(); ?>" id="<?php ucc_bpc_submit_field_name(); ?>" value="<?php ucc_bpc_submit_field_value(); ?>" />
                        <a href="<?php echo user_trailingslashit( ucc_bpc_get_url() ); ?>" class="bp-secondary-link"><?php _e( 'Cancel' ); ?></a>
                </div><!-- #bulk-actions -->

		<p><?php _e( 'You can add default tasks to your checklist using this utility.', 'buddypress-private-checklist' ); ?>

		<div id="bulk-date">
			<label for="<?php ucc_bpc_bulk_date_field_name(); ?>"><?php _e( 'Event date:', 'buddypress-private-checklist' ); ?></label>
			<input type="text" name="<?php ucc_bpc_bulk_date_field_name(); ?>" id="<?php ucc_bpc_bulk_date_field_name(); ?>" value="<?php echo esc_attr( $date ); ?>" />
			<span><?php _e( 'mm/dd/YYYY', 'buddypress-private-checklist' ); ?></span>
		</div><!-- #bulk-date -->

	<?php endif; ?>

	</div><!-- #bulk-options -->

	<?php wp_nonce_field( '_ucc_bpc_action_bulk' ); ?>

	<?php do_action( 'ucc_bpc_after_bulk_form_content' ); ?>

</form><!-- #bulk-form -->
