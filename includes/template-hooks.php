<?php
/**
 * template-hooks.php
 *
 * @package:
 * @since  : 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'jlt_job_loop_action', 'jlt_job_bookmark_button', 5 );