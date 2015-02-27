<?php

class BPC_Ajax {
	//global $bp;

	function __construct(){
		//Reset button on membership form
		add_action( 'wp_ajax_bpc-reset', array( $this, 'reset_callback' ) );
		add_action( 'wp_ajax_bpc_bulk_import', array( $this, 'bulk_add_callback' ) );
	}


	//Ajax reset callback
	public function reset_callback(){
		$userid = intval( $_POST['user_id'] );
		$user = get_userdata( $userid ) ;
		if (empty($userid)) {
			echo "No user ID passed";
			wp_die();
		}
		delete_user_meta( $userid, '_ucc_bpc_action_bulk_timeout' );
		delete_user_meta( $userid, '_ucc_bpc_action_bulk' );

		global $wpdb;
		$tasks = $wpdb->delete( $wpdb->posts , array('post_author' => $userid, 'post_type' => 'ucc_bpc_task'));
		$wpdb->show_errors();
		echo "Deleted tasks and reset lockout for $user->user_login";
		wp_die();
	}

	public function bulk_add_callback(){
		$result = ucc_bpc_action_bulk_process();
		echo json_encode($result);
		wp_die();
	}
}

new BPC_Ajax;