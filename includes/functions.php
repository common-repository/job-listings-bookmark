<?php

if ( ! function_exists( 'jlt_job_set_bookmarked' ) ) :
	function jlt_job_set_bookmarked( $user_id = 0, $job_id = 0 ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		if ( empty( $user_id ) ) {
			return false;
		}

		if ( empty( $job_id ) ) {
			$job_id = get_the_ID();
		}

		$job_id = absint( $job_id );

		$bookmarks = get_option( "jlt_bookmark_job_{$user_id}" );
		if ( empty( $bookmarks ) || ! is_array( $bookmarks ) ) {
			$bookmarks = array();
		}

		if ( isset( $bookmarks[ $job_id ] ) && $bookmarks[ $job_id ] == 1 ) {
			return true;
		} else {
			$bookmarks[ $job_id ] = 1;
		}

		return update_option( "jlt_bookmark_job_{$user_id}", $bookmarks );
	}
endif;

if ( ! function_exists( 'jlt_job_clear_bookmarked' ) ) :
	function jlt_job_clear_bookmarked( $user_id = 0, $job_id = 0 ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		if ( empty( $user_id ) ) {
			return false;
		}

		if ( empty( $job_id ) ) {
			$job_id = get_the_ID();
		}

		$job_id = absint( $job_id );

		$bookmarks = get_option( "jlt_bookmark_job_{$user_id}", array() );
		if ( empty( $bookmarks ) || ! is_array( $bookmarks ) ) {
			$bookmarks = array();
		}

		if ( ! isset( $bookmarks[ $job_id ] ) ) {
			return true;
		}

		unset( $bookmarks[ $job_id ] );

		return update_option( "jlt_bookmark_job_{$user_id}", $bookmarks );
	}
endif;

if ( ! function_exists( 'jlt_is_job_bookmarked' ) ) :
	function jlt_is_job_bookmarked( $user_id = 0, $job_id = 0 ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		if ( empty( $user_id ) ) {
			return false;
		}

		if ( empty( $job_id ) ) {
			$job_id = get_the_ID();
		}

		if ( empty( $job_id ) || 'job' != get_post_type( $job_id ) ) {
			return false;
		}

		$job_id = absint( $job_id );

		$bookmarks = get_option( "jlt_bookmark_job_{$user_id}", array() );

		if ( empty( $bookmarks ) || ! is_array( $bookmarks ) ) {
			return false;
		}

		return ( isset( $bookmarks[ $job_id ] ) && ! empty( $bookmarks[ $job_id ] ) );
	}
endif;

if ( ! function_exists( 'jlt_get_candidate_bookmarked_job' ) ) :
	function jlt_get_candidate_bookmarked_job( $user_id = 0 ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		if ( empty( $user_id ) ) {
			return array();
		}

		$bookmarks = get_option( "jlt_bookmark_job_{$user_id}", array() );
		if ( empty( $bookmarks ) || ! is_array( $bookmarks ) ) {
			return array();
		}

		return $bookmarks;
	}
endif;

if ( ! function_exists( 'jlt_job_bookmark_delete_url' ) ) :

	function jlt_job_bookmark_delete_url() {
		return wp_nonce_url( add_query_arg( array(
			'action' => 'delete_bookmark',
			'job_id' => get_the_ID(),
		) ), 'bookmark-job-manage-action' );
	}

endif;

if ( ! function_exists( 'jlt_job_bookmark_ajax_add' ) ) :

	function jlt_job_bookmark_ajax_add() {
		if ( ! jlt_is_logged_in() ) {
			$result = array(
				'success' => false,
				'message' => '<span class="error-response">' . __( 'You have not logged in yet', 'job-listings-bookmark' ) . '</span>',
			);

			wp_send_json( $result );

			return;
		}

		if ( ! check_ajax_referer( 'jlt-bookmark-job', 'security', false ) ) {
			$result = array(
				'success' => false,
				'message' => '<span class="error-response">' . __( 'Your session has expired or you have submitted an invalid form.', 'job-listings-bookmark' ) . '</span>',
			);

			wp_send_json( $result );

			return;
		}

		$job_id  = isset( $_REQUEST[ 'job_id' ] ) ? absint( sanitize_text_field( $_REQUEST[ 'job_id' ] ) ) : '';

		if ( empty( $job_id ) ) {
			$result = array(
				'success' => false,
				'message' => '<span class="error-response">' . __( 'There\'s an unknown error. Please retry or contact Administrator.', 'job-listings-bookmark' ) . '</span>',
			);

			wp_send_json( $result );

			return;
		}

		$current_user = wp_get_current_user();

		$user_id = $current_user->ID;

		if ( jlt_is_job_bookmarked( $user_id, $job_id ) ) {
			if ( jlt_job_clear_bookmarked( $user_id, $job_id ) ) {
				$result = array(
					'success'      => true,
					'status'       => 'removed',
					'message'      => '<span class="success-response">' . __( 'Bookmark removed.', 'job-listings-bookmark' ) . '</span>',
					'message_text' => __( 'Bookmark Job', 'job-listings-bookmark' ),
				);
				wp_send_json( $result );
			}
		} else {
			if ( jlt_job_set_bookmarked( $user_id, $job_id ) ) {
				$result = array(
					'success'      => true,
					'status'       => 'bookmarked',
					'message'      => '<span class="success-response">' . __( 'Job bookmarked.', 'job-listings-bookmark' ),
					'message_text' => __( 'Bookmarked', 'job-listings-bookmark' ),
				);
				wp_send_json( $result );
			}
		}

		$result = array(
			'success' => false,
			'message' => '<span class="error-response">' . __( 'There\'s an unknown error. Please retry or contact Administrator.', 'job-listings-bookmark' ) . '</span>',
		);

		wp_send_json( $result );
	}

	add_action( 'wp_ajax_nopriv_jlt_bookmark_job', 'jlt_job_bookmark_ajax_add' );
	add_action( 'wp_ajax_jlt_bookmark_job', 'jlt_job_bookmark_ajax_add' );

endif;

if ( ! function_exists( 'jlt_job_bookmark_delete_action' ) ) :

	function jlt_job_bookmark_delete_action() {
		if ( ! jlt_is_logged_in() ) {
			return;
		}

		$action = isset( $_REQUEST[ 'action' ] ) && - 1 != $_REQUEST[ 'action' ];

		if ( ! empty( $action ) && ! empty( $_REQUEST[ '_wpnonce' ] ) && wp_verify_nonce( $_REQUEST[ '_wpnonce' ], 'bookmark-job-manage-action' ) ) {

			$job_id = isset( $_REQUEST[ 'job_id' ] ) ? absint( sanitize_text_field( $_REQUEST[ 'job_id' ] ) ) : '';

			if ( empty( $job_id ) ) {
				jlt_message_add( __( 'There\'s an unknown error. Please retry or contact Administrator.', 'job-listings-bookmark' ), 'error' );
			}
			try {
				switch ( $action ) {
					case 'delete_bookmark':
						$user_id = get_current_user_id();
						$job     = get_post( $job_id );
						if ( empty( $job ) || $job->post_type !== 'job' ) {
							jlt_message_add( __( 'Can not find this job.', 'job-listings-bookmark' ), 'error' );
							break;
						}

						if ( jlt_job_clear_bookmarked( $user_id, $job_id ) ) {
							jlt_message_add( __( 'Bookmark cleared.', 'job-listings-bookmark' ), 'success' );
						} else {
							jlt_message_add( __( 'There\'s an unknown error. Please retry or contact Administrator.', 'job-listings-bookmark' ), 'error' );
						}
						break;
				}

				wp_safe_redirect( JLT_Member::get_endpoint_url( 'bookmark-job' ) );
				die;
			} catch ( Exception $e ) {
				throw new Exception( $e->getMessage() );
			}
		}
	}

	add_action( 'init', 'jlt_job_bookmark_delete_action' );
endif;