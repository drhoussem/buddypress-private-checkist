<?php
class BPCFilters {
	function  __construct(){
		//add a reset button to profile page
		add_action('ucc_bpc_before_directory_checklist', array( $this, 'bpc_reset_button'));
	}

	function bpc_reset_button() {
		if ( current_user_can( 'moderate' )) {
			$user_id = get_current_user_id();
			echo "<div id='checklist-tools' data-userid='$user_id' style='display:inline;'>";
			echo "<button id='reset-checklist' class='btn btn-primary'><a href='#'>Reset Checklist</a></button>";
			echo '<div id="checklist-reset-confirm"></div>';
			echo '</div>';
		}
	}

}

new BPCFilters;

