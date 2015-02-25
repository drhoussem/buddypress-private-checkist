<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class UCC_BuddyPress_Private_Checklist_Component extends BP_Component {
	public static $version;

	public function __construct() {
		global $bp, $current_screen;

		parent::start(
			'checklist',
			__( 'Private Checklist', 'buddypress-private-checklist' ),
			UCC_BPC_PLUGIN_DIR
		);

		$this->version = '2013090901';

		$this->includes();
		$bp->active_components[$this->id] = (int) 1;

		// Custom post types and taxonomies.
		add_action( 'init', array( $this, 'register_taxonomies' ), 12 );
		add_action( 'init', array( $this, 'register_post_types' ), 12 );
		
		// Custom post meta data.
		add_action( 'save_post', array( $this, 'save_post_meta' ) );

		// Keep slugs reasonable, since title is the task.
		add_filter( 'wp_unique_post_slug', array( $this, 'wp_unique_post_slug' ), 10, 5 );

		// Allow target in title hrefs.
		add_filter( 'init', array( $this, 'allowed_tags' ), 12 );

		// AJAX callbacks.
		add_action( 'wp_ajax_nopriv_ucc-bpc-filter', array( $this, 'ajax_cb' ) );
		add_action( 'wp_ajax_ucc-bpc-filter', array( $this, 'ajax_cb' ) );
		add_action( 'wp_ajax_bpc-reset', array( $this, 'reset_callback' ) );

		// Load appropriate scripts and styles.
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'admin_init' ) );

			if ( current_user_can( 'edit_users' ) ) {
				add_action( 'show_user_profile', array( $this, 'edit_user_profile' ) );
				add_action( 'edit_user_profile', array( $this, 'edit_user_profile' ) );
				add_action( 'personal_options_update', array( $this, 'edit_user_profile_update' ) );
				add_action( 'edit_user_profile_update', array($this, 'edit_user_profile_update' ) );
			}
		} else {
			add_action( 'wp_enqueue_scripts', array($this, 'wp_enqueue_scripts' ) );
		}
		
		// Privacy concerns.
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		
		// BuddyPress compatability.
		add_filter( 'bp_modify_page_title', array( $this, 'page_title' ), 10, 4 );
	}

	public function includes() {
		$includes = array(
			'includes/ucc-bpc-actions.php',
			'includes/ucc-bpc-classes.php',
			'includes/ucc-bpc-filters.php',
			'includes/ucc-bpc-functions.php',
			'includes/ucc-bpc-screens.php',
			'includes/ucc-bpc-template.php'
		);

		parent::includes( $includes );
	}

	public function setup_globals() {
		global $bp;

		if ( ! defined( 'UCC_BPC_SLUG' ) )
			define( 'UCC_BPC_SLUG', $this->id );

		$globals = array(
			'slug'		  => UCC_BPC_SLUG,
			'root_slug'	     => isset( $bp->pages->{$this->id}->slug ) ? $bp->pages->{$this->id}->slug : UCC_BPC_SLUG,
			'has_directory'		=> true, 
			'notification_callback' => 'ucc_bpc_format_notifications',
			'search_string'	 => __( 'Search My Tasks...', 'buddypress-private-checklist' )
		);

		parent::setup_globals( $globals );
	}

	public function setup_nav() {
		$main_nav = array();
		$sub_nav = array();

		parent::setup_nav( $main_nav, $sub_nav );
	}

	public function setup_title() {
		global $bp;
		$bp->bp_options_title = __( 'Private Checklist', 'buddypress-private-checklist' );
		parent::setup_title();
	}

	public function register_taxonomies() {
		$taxonomy = 'ucc_bpc_category';
		$object_type = null;
		$labels = array(
			'name'              => _x( 'Task Categories', 'taxonomy general name', 'buddypress-private-checklist' ),
			'singular_name'     => _x( 'Task Category', 'taxonomy singular name', 'buddypress-private-checklist' ),
			'search_items'      => __( 'Search Task Categories', 'buddypress-private-checklist' ),
			'popular_items'     => __( 'Popular Task Categories', 'buddypress-private-checklist' ),
			'all_items'         => __( 'All Task Categories', 'buddypress-private-checklist' ),
			'parent_item'       => __( 'Parent Task Category', 'buddypress-private-checklist' ),
			'parent_item_colon' => __( 'Parent Task Category:', 'buddypress-private-checklist' ),
			'edit_item'         => __( 'Edit Task Category', 'buddypress-private-checklist' ),
			'update_item'       => __( 'Update Task Category', 'buddypress-private-checklist' ),
			'add_new_item'      => __( 'Add New Task Category', 'buddypress-private-checklist' ),
			'new_item_name'     => __( 'New Task Category Name', 'buddypress-private-checklist' ),
			'menu_namu'         => __( 'Task Categories', 'buddypress-private-checklist' )
		);
		$args = array(
			'labels'                => $labels,
			'public'                => true,
			'show_in_nav_menus'     => false,
			'show_ui'               => true,
			'show_tagcloud'         => true,
			'hierarchical'          => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => 'ucc_bpc_category',
			'rewrite'               => false
		);
		register_taxonomy( $taxonomy, $object_type, $args );

		$taxonomy = 'ucc_bpc_status';
		$object_type = null;
		$labels = array(
			'name'              => _x( 'Task Statuses', 'taxonomy general name', 'buddypress-private-checklist' ),
			'singular_name'     => _x( 'Task Status', 'taxonomy singular name', 'buddypress-private-checklist' ),
			'search_items'      => __( 'Search Task Statuses', 'buddypress-private-checklist' ),
			'popular_items'     => __( 'Popular Task Statuses', 'buddypress-private-checklist' ),
			'all_items'         => __( 'All Task Statuses', 'buddypress-private-checklist' ),
			'parent_item'       => __( 'Parent Task Status', 'buddypress-private-checklist' ),
			'parent_item_colon' => __( 'Parent Task Status:', 'buddypress-private-checklist' ),
			'edit_item'         => __( 'Edit Task Status', 'buddypress-private-checklist' ),
			'update_item'       => __( 'Update Task Status', 'buddypress-private-checklist' ),
			'add_new_item'      => __( 'Add New Task Status', 'buddypress-private-checklist' ),
			'new_item_name'     => __( 'New Task Status Name', 'buddypress-private-checklist' ),
			'menu_name'         => __( 'Task Statuses', 'buddypress-private-checklist' )
		);
		$args = array(
			'labels'                => $labels,
			'public'                => true,
			'show_in_nav_menus'     => false,
			'show_ui'               => true,
			'show_tagcloud'         => true,
			'hierarchical'          => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => 'ucc_bpc_status',
			'rewrite'               => false
		);
		register_taxonomy( $taxonomy, $object_type, $args );
	}

	public function register_post_types() {
		$labels = array(
			'name'	   => __( 'Tasks', 'buddypress-private-checklist' ),
			'singular' => __( 'Task', 'buddypress-private-checklist' )
		);
		$args = array(
			'label'                => __( 'Tasks', 'buddypress-private-checklist' ),
			'labels'               => $labels,
			'public'               => false,
			'show_ui'              => true,
			'show_in_menu'         => true,
			'hierarchical'         => false,
			'supports'             => array( 'title', 'editor', 'author' ),
			'has_archive'          => false,
			'rewrite'              => false,
			'query_var'            => 'ucc_bpc_task',
			'can_export'           => false,
			'show_in_nav_menus'    => false,
			'register_meta_box_cb' => array( &$this, 'register_meta_box_cb' ),
			'taxonomies'           => array( 'ucc_bpc_category', 'ucc_bpc_status' )
		);
		register_post_type( 'ucc_bpc_task', $args );

		parent::register_post_types();
	}

	// Post meta data.
	public function register_meta_box_cb() {
		add_meta_box( 'ucc_bpc_task_meta_box', __( 'Task Details', 'buddypress-private-checklist' ), array( &$this, 'task_meta_box_cb' ), 'ucc_bpc_task', 'side', 'high' );
	}

	public function task_meta_box_cb( $post ) {
		if ( 'ucc_bpc_task' == $post->post_type ) {
			$time = get_post_meta( $post->ID, '_ucc_bpc_task_date', true );
			if ( empty( $time ) )
				$time = time();
			wp_nonce_field( '_ucc_bpc_action_edit', 'ucc_bpc_nonce' ); 
			?>
			<p><label for="<?php ucc_bpc_date_field_name(); ?>"><?php _e( 'Due date' ); ?></label>
			<input type="text" name="<?php ucc_bpc_date_field_name(); ?>" id="<?php ucc_bpc_date_field_name(); ?>" value="<?php echo date( 'm/d/Y', $time ); ?>"></input><br />
			<small>mm/dd/yyyy</small></p>
			<?php
		}
	}
	
	public function save_post_meta( $post_id ) {
		//  For lack of a better term, something has to stick.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
	
		if ( defined( 'DOING_CRON' ) && DOING_CRON ) 
			return $post_id;
	
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return $post_id;
		}
	
		if ( isset( $_POST['ucc_bpc_nonce'] ) && ! wp_verify_nonce( $_POST['ucc_bpc_nonce'], '_ucc_bpc_action_edit' ) )
			return $post_id;
	
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) 
			return $post_id;

		if ( isset( $_POST[ucc_bpc_get_date_field_name()] ) ) {
			$_task_date = $_POST[ucc_bpc_get_date_field_name()];
			$task_date = strtotime( $_task_date );
			if ( $task_date )
				update_post_meta( $post_id, '_ucc_bpc_task_date', $task_date );
			else
				update_post_meta( $post_id, '_ucc_bpc_task_date', time() );
		}

		return $post_id;
	}

	// Keep slugs reasonable.
	public function wp_unique_post_slug( $slug, $post_id, $post_status, $post_type, $post_parent ) {
		if ( 'ucc_bpc_task' == $post_type ) {
			return 'task-' . $post_id;
		}
		return $slug;
	}

	public function allowed_tags() {
		global $allowedtags;
		$allowedtags['a']['target'] = true;

	}

	// AJAX callback.
	public function ajax_cb() {
		ucc_bp_locate_template( 'templates/checklist/checklist-loop.php', true, true, __FILE__ ); 
		die();
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

	
	// jQuery datepicker setup.
	public function datepicker() {
		?>
		<script>
		jQuery(document).ready(function(){
			jQuery('#ucc_bpc_date').datepicker({
				changeMonth: true,
				changeYear: true,
				minDate: "-2Y", 
				maxDate: "+4Y"
			});
			jQuery('#ucc_bpc_bulk_date').datepicker({
				changeMonth: true,
				changeYear: true,
				minDate: "+1D",
				maxDate: "+4Y"
			});
		});
		</script>
		<?php
	}

	// Admin-side settings handler.
	public function admin_init() {
		register_setting( 'ucc_bpc_options', 'ucc_bpc_options', array( &$this, 'validate_plugin_options' ) );
		add_settings_section( 'ucc_bpc_default_tasks', 'Set Default Tasks', array( &$this, 'plugin_options_text' ), 'ucc_bpc_options' );
		add_settings_field( 'ucc_bpc_preload', 'Default Tasks', array( &$this, 'plugin_options_default_tasks' ), 'ucc_bpc_options', 'ucc_bpc_default_tasks' );
	}

	public function admin_menu() {
		add_submenu_page( 'bp-general-settings', __( 'Private Checklist', 'buddypress-private-checklist' ), __( 'Private Checklist', 'buddypress-private-checklist' ), 'manage_options', 'ucc-bpc-options', array( &$this, 'plugin_options_page' ) );	
	}

	// User settings handler.
	public function edit_user_profile( $user ) {
		// Does the user have a timeout?
		$user_has_timeout = get_user_meta( $user->ID, '_ucc_bpc_action_bulk_timeout', true );
	        $timeout = apply_filters( 'ucc_bpc_action_bulk_timeout', 60 * 5 );
        	$now = time();
        	if ( $user_has_timeout && (int) $user_has_timeout + $timeout < $now )
			$timeout = sprintf( __( 'Timed out at %1$s', 'buddypress-private-checklist' ), date( 'm/d/Y H:i:s', $user_has_timeout + $timeout ) );
		else
			$timeout = __( 'None', 'buddypress-private-checklist' );

		// Does the user have a lock?
		$user_has_added = get_user_meta( $user->ID, '_ucc_bpc_action_bulk', true );
        	if ( $user_has_added )
               		$lock = sprintf( __( 'Locked at %1$s', 'buddypress-private-checklist' ), date( 'm/d/Y H:i:s', $user_has_added ) );
		else 
			$lock = __( 'Not locked', 'buddypress-private-checklist' ); 
		?>
		<h3>BuddyPress Private Checklist</h3>

		<table class="form-table">
		<tbody>
		<tr>
			<th scope="row"><?php _e( 'User Settings', 'buddypress-private-checklist' ); ?></th>
			<td>
				<fieldset>
					<legend class="screen-reader-text">
					<span><?php _e( 'User Settings', 'buddypress-private-checklist' ); ?></span>
					</legend>
					<label for="ucc_bpc_user_reset">
					<input type="checkbox" name="ucc_bpc_user_reset" id="ucc_bpc_user_reset" value="1" />
					<?php _e( "Reset user's bulk import and timeout privileges.", 'buddypress-private-checklist' ); ?><br />
					<strong><?php _e( 'Timeout:', 'buddypress-private-checklist' ); ?></strong> <?php echo $timeout; ?> <strong><?php _e( 'Lock:', 'buddypress-private-checklist' ); ?></strong> <?php echo $lock; ?>
					</label>
					<br />
				</fieldset>
			</td>
		</tr>
		</tbody>
		</table>
		<?php
	}

	public function edit_user_profile_update( $user ) {
		if ( ! current_user_can( 'edit_user', $user ) )
			return false;

		if ( isset( $_POST['ucc_bpc_user_reset'] ) ) {
			delete_user_meta( $user, '_ucc_bpc_action_bulk_timeout' );
			delete_user_meta( $user, '_ucc_bpc_action_bulk' );
		}
	}

	// Plugin options page.
	public function plugin_options_page() {
		?>
		<div class="wrap">
		<h2><?php _e( 'BuddyPress Private Checklist Options', 'buddypress-private-checklist' ); ?></h2>

		<form action="options.php" method="post">
		<?php settings_fields( 'ucc_bpc_options' ); ?>
		<?php do_settings_sections( 'ucc_bpc_options' ); ?>

		<input name="submit" type="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
		</form></div>

		<?php
	}

	public function plugin_options_text() {
		?>
		<p>You can specify default tasks for a user to add to their checklist based on a date of their choice.</p>

		<p>This field accepts a CSV of the format time,category,task.</p>
		<?php	
	}

	public function plugin_options_default_tasks() {
		$options = get_option( 'ucc_bpc_options' );
		if ( isset( $options['bulk_add_tasks'] ) )
			$bulk_add_tasks = $options['bulk_add_tasks'];
		else
			$bulk_add_tasks = '';
		?>
		<textarea id="ucc_bpc_options" name="ucc_bpc_options[bulk_add_tasks]" rows="12" cols="60"><?php echo esc_html( $bulk_add_tasks ); ?></textarea>
		<?php
	}

	public function validate_plugin_options( $r ) {
		$new['bulk_add_tasks'] = trim( $r['bulk_add_tasks'] );
		return $new;
	}

	// Admin-side scripts and styles.
	public function admin_enqueue_scripts( $hook ) {
		global $post;

		if ( 'post-new.php' == $hook || 'post.php' == $hook ) {
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/ui-lightness/jquery-ui.css' );
			add_action( 'admin_footer', array( &$this, 'datepicker' ) );

//  Timepicker has issues with minDate and maxDate, shelving for now. 
//			wp_register_script( 'jquery-ui-timepicker', plugins_url( 'buddypress-private-checklist/includes/js/jquery-ui-timepicker-addon.js' ), array( 'jquery-ui-datepicker' ), $this->version, true );
//			wp_enqueue_script( 'jquery-ui-timepicker' );
//			wp_enqueue_script( 'jquery-ui-sliderAccess', plugins_url( 'buddypress-private-checklist/includes/js/jquery-ui-sliderAccess.js' ), null, $this->version );
//			wp_enqueue_style( 'jquery-style-timepicker', plugins_url( 'buddypress-private-checklist/includes/css/jquery-ui-timepicker-addon.css' ), null );
		}
	}
	
	// BuddyPress-side scripts and styles.
	public function wp_enqueue_scripts() {
		if ( ucc_bpc_is_component() ) { 
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/ui-lightness/jquery-ui.css' );
			wp_enqueue_script( 'ucc-bpc-checklist', plugins_url( 'buddypress-private-checklist/includes/js/checklist.js' ), array( 'jquery' ), $this->version, true );
			wp_localize_script( 
				'ucc-bpc-checklist', 
				'ucc_bpc', 
				array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ), 
					'edit_task' => __( 'Edit Task', 'buddypress-private-checklist' ),
					'save_task' => __( 'Save Task', 'buddypress-private-checklist' ),
					'reset_fields' => __( 'Reset fields', 'buddypress-private-checklist' ) 
				) 
			);

			wp_enqueue_style( 'ucc-bpc-checklist', plugins_url( 'buddypress-private-checklist/includes/css/checklist.css' ), $this->version, true );

			add_action( 'wp_footer', array( $this, 'datepicker' ) );
		} if ( bp_is_user() ) {
			wp_enqueue_script( 'ucc-bpc-checklist', plugins_url( 'buddypress-private-checklist/includes/js/checklist.js' ), array( 'jquery' ), $this->version, true );

		}
	}
	
	// Privacy concerns.
	public function pre_get_posts( $wp_query ) {
		global $current_user;
		if ( ! current_user_can( apply_filters( 'ucc_bpc_pre_get_posts', 'edit_others_posts' ) ) ) {
			if ( 'ucc_bpc_task' == $wp_query->get( 'post_type' ) ) {
				$wp_query->set( 'author', $current_user->ID );
				if ( is_admin() ) {
					add_filter( 'views_edit-ucc_bpc_task', array( &$this, 'admin_views_edit_ucc_bpc_task' ) );
				}
			}
		}
	}

	public function admin_views_edit_ucc_bpc_task( $views ) {
		unset( $views['all'] );
		unset( $views['publish'] );
		unset( $views['draft'] );
		unset( $views['pending'] );
		unset( $views['trash'] );
	}
	
	// BuddyPress compatability.
	public function page_title( $current, $title, $sep, $seplocation ) {
		if ( ucc_bpc_is_component() )
			return __( 'My Checklist', 'buddypress-private-checklist' ) . ' ' . $sep . ' ';
	}
}


function ucc_bpc_load_core_component() {
	global $bp;

	// Restrict usage.
	if ( current_user_can( apply_filters( 'ucc_bpc_current_user_can', 'read' ) ) ) {
		$locale = apply_filters('plugin_locale', get_locale(), $domain);
		load_textdomain('buddypress-private-checklist', WP_LANG_DIR.'/buddypress-private-checklist-'.$locale.'.mo');
		load_plugin_textdomain('buddypress-private-checklist', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
		$bp->checklist = new UCC_BuddyPress_Private_Checklist_Component();
	}
}
add_action( 'bp_loaded', 'ucc_bpc_load_core_component' );


?>
