<?php
/*
Plugin Name: BuddyPress Private Checklist
Description: Provide logged-in members with a private checklist they can use for task management. 
Version: 0.8
Author: Jennifer M. Dodd
Author URI: http://uncommoncontent.com/
*/

/*
	Copyright 2012 Jennifer M. Dodd <jmdodd@gmail.com>

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


if ( ! defined( 'ABSPATH' ) ) exit;


// Define constants.
define( 'UCC_BPC_IS_INSTALLED', 1 );
define( 'UCC_BPC_VERSION', '0.8' );
define( 'UCC_BPC_PLUGIN_DIR', dirname( __FILE__ ) );


function ucc_bpc_init() {
	if ( version_compare( BP_VERSION, '1.3', '>' ) ) {
		require( UCC_BPC_PLUGIN_DIR . '/includes/ucc-bpc-loader.php' );
	}
}
add_action( 'bp_include', 'ucc_bpc_init' );


function ucc_bpc_activation() {
	// Add default statuses.
	register_taxonomy( 'ucc_bpc_status', null );

	$statuses = array( 'Not started', 'In progress', 'On hold', 'Completed' );
	foreach ( $statuses as $status )
		wp_insert_term( $status, 'ucc_bpc_status' );
}
register_activation_hook( __FILE__, 'ucc_bpc_activation' );

// Allow symlinking this plugin
//add_filter( 'plugins_url', 'obe_plugin_symlink_fix', 10, 3 );

function obe_plugin_symlink_fix( $url, $path, $plugin ) {
	// Do it only for this plugin
	if ( strstr( $plugin, basename(__FILE__) ) )
		return str_replace( dirname(__FILE__), '/' . basename( dirname( $plugin ) ), $url );

	return $url;
}
