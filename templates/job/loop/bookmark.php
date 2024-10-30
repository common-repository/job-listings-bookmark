<?php
/**
 * Display button bookmark job
 *
 * This template can be overridden by copying it to yourtheme/job-listings/job/loop/bookmark.php.
 *
 * HOWEVER, on occasion NooTheme will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author      NooTheme
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $job;

echo sprintf( '<div class="jlt-bookmark"><a rel="nofollow" href="javascript:void(0);" data-job-id="%s" data-security="%s" class="jlt-btn jlt-btn-bookmark"><i class="jlt-icon %s"></i><span class="jlt-bookmark-label">%s</span></a><div class="jlt-bookmark-result"></div></div>', esc_attr( $job->id ), esc_attr( $security_code ), esc_attr( $icon_class ), esc_html( $bookmark_label ) );
