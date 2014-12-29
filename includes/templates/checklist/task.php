        <li class="task task-list task-status-<?php ucc_bpc_status_slug(); ?> task-category-<?php ucc_bpc_category_slug(); ?> task-state-<?php ucc_bpc_state(); ?>">

	<?php if ( ucc_bpc_user_can_view() ) : ?>

		<div id="task-<?php ucc_bpc_id(); ?>">

		<div class="task-status">
		<input type="checkbox" class="ucc_bpc_checkit_cb" name="<?php ucc_bpc_checkit_field_name(); ?>" value="<?php ucc_bpc_id(); ?>" <?php checked( ucc_bpc_get_checked_slug(), ucc_bpc_get_status_slug() ); ?> />
		</div><!-- .task-status -->

        	<div class="task-title">
		<?php echo apply_filters( 'the_content', ucc_bpc_title() ); ?>
		</div><!-- .task-title -->

		<div class="task-notes">
		<?php ucc_bpc_content(); ?>
		</div><!-- .task-notes -->

		<div class="task-meta">
		<?php printf( __( '<strong>Due:</strong> %1$s (%2$s) - %3$s | <strong>Filed in:</strong> %4$s', 'buddypress-private-checklist' ), date( 'F jS, Y', ucc_bpc_time() ), ucc_bpc_get_human_time_diff( ucc_bpc_time(), time() ), ucc_bpc_get_status(), ucc_bpc_get_category() ); ?> 
		</div><!-- .task-meta -->

		<div class="task-actions">
        	<?php if ( ucc_bpc_user_can_edit() ) ucc_bpc_edit_link(); ?>
        	<?php if ( ucc_bpc_user_can_delete() ) ucc_bpc_delete_link(); ?>
		</div><!-- .task-actions -->

		<div class="hidden" id="inline-<?php ucc_bpc_id(); ?>">
		<div class="ucc_bpc_id"><?php ucc_bpc_id(); ?></div>
		<div class="ucc_bpc_title"><?php ucc_bpc_title(); ?></div>
		<div class="ucc_bpc_content"><?php ucc_bpc_content_raw(); ?></div>
		<div class="ucc_bpc_category"><?php ucc_bpc_category_id(); ?></div>
		<div class="ucc_bpc_status"><?php ucc_bpc_status_id(); ?></div>	
		<div class="ucc_bpc_date"><?php ucc_bpc_date(); ?></div>
		</div><!-- .hidden -->

		</div><!-- #task-<?php ucc_bpc_id(); ?> -->

	<?php else : ?>

		<p><?php _e( 'You are not allowed to see this task.' ); ?></p>

	<?php endif; ?>

        </li>
