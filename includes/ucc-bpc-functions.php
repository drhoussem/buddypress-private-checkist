<?php

function ucc_bp_locate_template( $template_names, $load = false, $require_once = true, $file = '' ) {
	$located = '';

	foreach ( (array) $template_names as $template_name ) {
		if ( ! $template_name )
			continue;

		// Child theme.
		if ( file_exists( STYLESHEETPATH . '/' . $template_name ) ) {
			$located = STYLESHEETPATH . '/' . $template_name;
			break;

		// Parent theme.
		} elseif ( file_exists( TEMPLATEPATH . '/' . $template_name ) ) {
			$located = TEMPLATEPATH . '/' . $template_name;
			break;

		// Last-ditch attempts at location of calling file.
		} elseif ( $file != '' ) {
			$pathinfo = pathinfo( $file, PATHINFO_DIRNAME ); 
			$plugin = plugin_basename( $pathinfo );

			// Don't go past the calling plugin's basedir.
			$levels = count( explode( '/', $plugin ) ) - 1;

			for ( $level = 0; $level < $levels; $level++ ) {
				if ( file_exists( $pathinfo . '/' . $template_name ) ) {
					$located = $pathinfo . '/' . $template_name;
					break;
				}
				$pathinfo = dirname( $pathinfo );
			}
		}
	}
	if ( $load && '' != $located )
		load_template( $located, $require_once );

	return $located;
}

function ucc_bpc_load_template_filter( $found_template, $templates ) {
        global $bp;

        if ( $bp->current_component != $bp->checklist->slug )
                return $found_template;

        foreach ( (array) $templates as $template ) {
                if ( file_exists( STYLESHEETPATH . '/' . $template ) )
                        $filtered_templates[] = STYLESHEETPATH . '/' . $template;
                else
                        $filtered_templates[] = dirname( __FILE__ ) . '/templates/' . $template;
        }

        $found_template = $filtered_templates[0];
        return apply_filters( 'ucc_bpc_load_template_filter', $found_template );
}
add_filter( 'bp_located_template', 'ucc_bpc_load_template_filter', 10, 2 );

