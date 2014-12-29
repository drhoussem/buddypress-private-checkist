<form action="" method="post" id="email-form" name="email-form" role="complementary">

	<h3><?php _e( 'Email Tasks', 'buddypress-private-checklist' ); ?></h3>

	<?php do_action( 'ucc_bpc_before_email_form_content' ); ?>

	<div id="email-options">

	<?php $email = ucc_bpc_get_email_address_field_value(); ?>
	
	<div id="email-actions">
		<input type="submit" name="<?php ucc_bpc_submit_field_name(); ?>" id="<?php ucc_bpc_submit_field_name(); ?>" value="<?php ucc_bpc_submit_field_value(); ?>" />
		<a href="<?php echo user_trailingslashit( ucc_bpc_get_url() ); ?>" class="bp-secondary-link cancel-email"><?php _e( 'Cancel' ); ?></a>
	</div><!-- #email-actions -->

	<div id="email-address">
		<label for="<?php ucc_bpc_email_address_field_name(); ?>"><?php _e( 'Email address: ', 'buddypress-private-checklist' ); ?></label>
		<input type="text" name="<?php ucc_bpc_email_address_field_name(); ?>" id="<?php ucc_bpc_email_address_field_name(); ?>" value="<?php echo esc_attr( $email ); ?>" />
	</div><!-- #email-address -->

	</div><!-- #email-options -->

	<?php wp_nonce_field( '_ucc_bpc_action_email' ); ?>

	<?php do_action( 'ucc_bpc_after_email_form_content' ); ?>

</form><!-- #email-form -->
