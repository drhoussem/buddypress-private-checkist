<?php
class BPCFilters {
	function  __construct(){
		//add a reset button to profile page
		add_action('bp_member_header_actions', array( $this, 'bpc_reset_button'));
	}

	function bpc_reset_button() {
		if ( current_user_can( 'moderate' )) {
			$user_id = bp_displayed_user_id();
			echo "<div id='checklist-tools' data-userid='$user_id' style='display:inline;'>";
			echo "<div id='reset-checklist' class='generic-button'><a href='#'>Reset Checklist</a></div>";
			echo '</div>';
		}
	}

}

new BPCFilters;

