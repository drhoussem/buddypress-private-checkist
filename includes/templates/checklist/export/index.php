<?php 

global $current_user, $wpdb, $post_ids;

// Do not proceed if user is not logged in, not viewing checklist, or action is not bulk.
if ( ! is_user_logged_in() || ! ucc_bpc_is_component() || ! bp_is_current_action( 'export' ) )
	bp_core_redirect( ucc_bpc_get_referer_url( ucc_bpc_get_referer_vars() ) );

if ( empty( $_REQUEST ) || ! isset( $_REQUEST['_wpnonce'] ) )
	bp_core_redirect( ucc_bpc_get_referer_url( ucc_bpc_get_referer_vars() ) );

// Check the nonce.
check_admin_referer( '_ucc_bpc_action_export' );

$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'ucc_bpc_task' AND post_author = %d", $current_user->ID ) );
if ( $post_ids ) { 
	header("Content-disposition: attachment; filename=export.csv");
	header('Content-type: text/csv');
	$out = fopen( 'php://output', 'w' );

	while ( $next_posts = array_splice( $post_ids, 0, 20 ) ) {
		$args = array(
			'posts_per_page' => -1,
			'author' => $current_user->ID,
			'post__in' => (array) $next_posts
		);
		$tasks = new UCC_BuddyPress_Private_Checklist( $args );
		if ( $tasks ) {
			foreach ( $tasks->posts as $task ) {
				fputcsv( $out, array( date( 'm/d/Y', ucc_bpc_time( $task->ID ) ), ucc_bpc_get_category( $task->ID ), ucc_bpc_get_status( $task->ID ), strip_tags( $task->post_title ), strip_tags( $task->post_content ) ) );
			}
		}
	}
	fclose( $out );
	exit();
} else {
	bp_core_add_message( 'You have no tasks to export.' );
	ucc_bp_locate_template( 'checklist/index.php', true, true, __FILE__ );
}
