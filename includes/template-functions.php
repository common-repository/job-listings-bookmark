<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'jlt_job_bookmark_endpoint_define' ) ) :

	function jlt_job_bookmark_endpoint_define() {
		$endpoints   = [ ];
		$endpoints[] = array(
			'key'          => 'bookmark-job',
			'value'        => jlt_get_endpoints_setting( 'bookmark-job', 'bookmark-job' ),
			'text'         => __( 'Bookmark Job', 'job-listings-bookmark' ),
			'order'        => 25,
			'show_in_menu' => true,
		);

		return $endpoints;
	}

endif;

if ( ! function_exists( 'jlt_job_bookmark_add_endpoints' ) ) :

	function jlt_job_bookmark_add_endpoints() {
		foreach ( jlt_job_bookmark_endpoint_define() as $endpoint ) {
			add_rewrite_endpoint( $endpoint[ 'value' ], EP_ROOT | EP_PAGES );
		}
	}

	add_action( 'init', 'jlt_job_bookmark_add_endpoints' );
endif;

if ( ! function_exists( 'jlt_job_bookmark_endpoint' ) ) :

	function jlt_job_bookmark_endpoint( $endpoints ) {

		$endpoints = array_merge( $endpoints, jlt_job_bookmark_endpoint_define() );

		return $endpoints;
	}

	add_filter( 'jlt_list_endpoints_candidate', 'jlt_job_bookmark_endpoint' );

endif;

if ( ! function_exists( 'jlt_job_bookmark_manage' ) ) :
	function jlt_job_bookmark_manage() {

		$paged = jlt_member_get_paged();

		$bookmarked_ids = jlt_get_candidate_bookmarked_job();

		if ( ! empty( $bookmarked_ids ) ) {
			$args = array(
				'post_type'   => 'job',
				'post_status' => array( 'publish' ),
				'paged'       => $paged,
				'post__in'    => array_keys( $bookmarked_ids ),
			);

			$list_jobs = new WP_Query( $args );
		} else {
			$list_jobs              = new stdClass();
			$list_jobs->found_posts = 0;
		}

		$array = array(
			'list_jobs'  => $list_jobs,
			'count_jobs' => $list_jobs->found_posts,
		);

		jlt_get_template( 'member/manage-job-bookmark.php', $array, '', JLT_BOOKMARK_PLUGIN_TEMPLATE_DIR );
		wp_reset_query();
	}

	add_action( 'jlt_account_bookmark-job_endpoint', 'jlt_job_bookmark_manage' );

endif;

if ( ! function_exists( 'jlt_job_bookmark_button' ) ) {
	function jlt_job_bookmark_button() {

		if ( jlt_is_job_bookmarked( 0, get_the_ID() ) ) {
			$icon_class     = 'jltfa-minus-circle';
			$bookmark_label = __( 'Bookmarked', 'job-listings-bookmark' );
		} else {
			$icon_class     = 'jltfa-plus-circle';
			$bookmark_label = __( 'Bookmark Job', 'job-listings-bookmark' );
		}

		$atts = array(
			'icon_class'     => $icon_class,
			'bookmark_label' => $bookmark_label,
			'security_code'  => wp_create_nonce( 'jlt-bookmark-job' ),
		);

		if ( ! jlt_is_employer() ) {
			jlt_get_template( 'job/loop/bookmark.php', $atts, '', JLT_BOOKMARK_PLUGIN_TEMPLATE_DIR );
		}
	}
}