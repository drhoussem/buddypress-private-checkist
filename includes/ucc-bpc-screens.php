<?php


if ( ! defined( 'ABSPATH' ) ) exit;


function ucc_bpc_directory_setup() {
	global $bp;

	if ( ucc_bpc_is_component() ) {
		do_action( 'ucc_bpc_directory_setup' );
		if ( ! bp_current_action() && ! bp_current_item() ) {
			bp_core_load_template( apply_filters( 'ucc_bpc_directory_setup', 'checklist/index' ) );
		} elseif ( bp_current_action() == 'edit' ) {
			do_action( 'ucc_bpc_edit_setup' );
			bp_core_load_template( apply_filters( 'ucc_bpc_directory_setup', 'checklist/edit/index' ) );
		} elseif ( bp_current_action() == 'bulk' ) {
			do_action( 'ucc_bpc_bulk_setup' );
			bp_core_load_template( apply_filters( 'ucc_bpc_directory_setup', 'checklist/bulk/index' ) );
		} elseif ( bp_current_action() == 'export' ) {
			do_action( 'ucc_bpc_export_setup' );
			bp_core_load_template( apply_filters( 'ucc_bpc_directory_setup', 'checklist/export/index' ) );
		} elseif ( bp_current_action() == 'print' ) {
			do_action( 'ucc_bpc_print_setup' );
			bp_core_load_template( apply_filters( 'ucc_bpc_directory_setup', 'checklist/print/index' ) );
		} elseif ( bp_current_action() == 'email' ) {
			do_action( 'ucc_bpc_email_setup' );
			bp_core_load_template( apply_filters( 'ucc_bpc_directory_setup', 'checklist/email/index' ) );
		}
	} 
}
add_action( 'bp_screens', 'ucc_bpc_directory_setup' );
