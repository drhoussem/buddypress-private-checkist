<form action="<?php ucc_bpc_search_form_action(); ?>" id="checklist-search-form" method="post">
	<label for="<?php ucc_bpc_search_text_field_name(); ?>"><input type="text" name="<?php ucc_bpc_search_text_field_name(); ?>" id="<?php ucc_bpc_search_text_field_name(); ?>" value="<?php esc_attr( ucc_bpc_search_text_field_value() ); ?>" onfocus="if (this.value == '<?php ucc_bpc_search_text_default_field_value(); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php ucc_bpc_search_text_default_field_value(); ?>';}" /></label>
	<input type="submit" name="<?php ucc_bpc_search_submit_field_name(); ?>" id="<?php ucc_bpc_search_submit_field_name(); ?>" value="<?php esc_attr( ucc_bpc_search_submit_field_value() ); ?>" />
</form>
