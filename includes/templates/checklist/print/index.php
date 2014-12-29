<html>
<head>
	<title><?php printf( __( 'My Tasks (Generated %s)', 'buddypress-private-checklist' ), date( 'm/d/Y' ) ); ?></title>
</head>

<body style="padding: 36px 72px; font: 16px/20px Georgia, 'Times New Roman', Times, serif; color: #000;">

<h2><?php printf( __( '[%1$s] My Tasks', 'buddypress-private-checklist' ), get_bloginfo( 'name' ) ); ?></h2>

<table rules="all" style="border: 1px solid #000;">
<tbody>
<?php

if ( is_user_logged_in() ) {
	global $current_user, $wpdb, $post_ids, $checklist_template;

	$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'ucc_bpc_task' AND post_author = %d", $current_user->ID ) );
	if ( $post_ids )
        while ( $next_posts = array_splice( $post_ids, 0, 20 ) ) {
                $args = array(
                        'posts_per_page' => -1,
                        'author' => $current_user->ID,
                        'post__in' => (array) $next_posts,
			'post_type' => 'ucc_bpc_task'
                );
		$checklist_template = new UCC_BuddyPress_Private_Checklist( $args );
		if ( ucc_bpc_have_tasks() ) {
			while ( ucc_bpc_have_tasks() ) {
				ucc_bpc_the_task();
				?>
				<tr>
					<td style="padding: 10px; vertical-align: top;">
               				<div class="task-status">
               				<input type="checkbox" class="ucc_bpc_checkit_cb" <?php checked( ucc_bpc_get_checked_slug(), ucc_bpc_get_status_slug() ); ?> />
               				</div><!-- .task-status -->
					</td>
	
					<td style="padding: 10px; vertical-align: top;">
       	        			<div class="task-title">
			                <?php echo apply_filters( 'the_content', ucc_bpc_title() ); ?>
       	        			</div><!-- .task-title -->
	
			                <div class="task-notes">
       				        <?php ucc_bpc_content(); ?>
       	        			</div><!-- .task-notes -->
	
       	        			<div class="task-meta" style="font: 12px/16px Arial, Verdana, sans-serif;">
       	        			<?php printf( __( '<strong>Due:</strong> %1$s (%2$s) - %3$s | <strong>Filed in:</strong> %4$s', 'buddypress-private-checklist' ), date( 'F jS, Y', ucc_bpc_time() ), human_time_diff( ucc_bpc_time(), time() ), ucc_bpc_get_status(), ucc_bpc_get_category() ); ?>
					</div class="task-meta">
					</td>
				</tr>
				<?php
			}
                }
        }
} else {
	?>
	<tr>
	<td colspan="2">
	You have no tasks.
	</td>
	</tr>
	<?php
}
?>
</tbody>
</table>

</body>
</html>
