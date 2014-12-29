<?php

class UCC_BuddyPress_Private_Checklist extends WP_Query {
	public function __construct( $args = array() ) {
		$this->UCC_BuddyPress_Private_Checklist( $args );

		$add_args = ucc_bpc_get_referer_vars();
		unset( $add_args['upage'] );

		$base = user_trailingslashit( ucc_bpc_get_url() ) . '%_%';
		$this->pagination_links = paginate_links( array(
			'base'      => $base, 
			'format'    => '?upage=%#%',
			'total'     => ceil( (int) $this->found_posts / (int) $this->query_vars['posts_per_page'] ),
			'current'   => (int) $this->query_vars['paged'],
			'prev_text' => '&larr;',
			'next_text' => '&rarr;',
			'mid_size'  => 1,
			'add_args'  => $add_args
		) );
	}

	public function UCC_BuddyPress_Private_Checklist( $args = array() ) {
		global $bp;

		$current_user = $bp->loggedin_user->id; 
		if ( 0 == $current_user ) 
			return false;

		$per_page = (int) apply_filters( 'ucc_bpc_per_page', 10 );
		$defaults = array( 
			'author'         => $current_user,
			'post_type'      => 'ucc_bpc_task',
			'meta_key'       => '_ucc_bpc_task_date',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'posts_per_page' => $per_page,
			'paged'          => 1
		);
		$r = wp_parse_args( $args, $defaults );

		parent::query( $r );
	}
}

