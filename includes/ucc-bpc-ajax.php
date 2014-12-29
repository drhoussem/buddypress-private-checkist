<?php

function ucc_bpc_filter_callback() {
	ucc_bp_locate_template( 'templates/checklist/checklist-loop.php', true, true, __FILE__ ); 
	die();
}

?>
