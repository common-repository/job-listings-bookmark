<?php
/**
 * Manage Job Bookmark Page.
 *
 * This template can be overridden by copying it to yourtheme/job-listings/member/manage-job-bookmark.php.
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

?>
<?php do_action( 'jlt_member_manage_bookmark_job_before' ); ?>
<?php

$title_text = '';
if ( ! empty( $count_jobs ) ) {
	$title_text = sprintf( _n( "You've saved %s job", "You've saved %s jobs", $count_jobs, 'job-listings-bookmark' ), $count_jobs );
} else {
	$title_text = __( 'You have saved no job', 'job-listings-bookmark' );
}
?>
	<div class="member-manage">

		<div class="member-page-header">
			<p><?php echo $title_text; ?></p>
		</div>

		<form method="post">
			<div class="member-manage-table">
				<ul class="jlt-list jlt-job-bookmarked">
					<li>
						<div class="col-job-title jlt-col-40"><?php _e( 'Job Title', 'job-listings-bookmark' ) ?></div>
						<div class="col-job-info jlt-col-45"><?php _e( 'Information', 'job-listings-bookmark' ) ?></div>
						<div class="col-actions jlt-col-15"><?php _e( 'Actions', 'job-listings-bookmark' ) ?></div>
					</li>
					<?php if ( ! empty( $count_jobs ) && $list_jobs->have_posts() ): ?>
						<?php while ( $list_jobs->have_posts() ): $list_jobs->the_post();
							global $post, $job;
							?>
							<li>
								<div class="col-job-title jlt-col-40">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
									<?php
									if ( ! empty( $job->closing() ) ) :
										?>
										<p>
											<i class="jlt-icon jltfa-calendar"></i>&nbsp;<em><?php echo $job->closing(); ?></em>
										</p>
									<?php else : ?>
										<p>
											<i class="jlt-icon jltfa-calendar"></i>&nbsp;<em><?php echo __( 'Equal to expired date', 'job-listings-bookmark' ); ?></em>
										</p>
									<?php endif; ?>
								</div>
								<div class="col-job-info jlt-col-45">
									<?php

									echo $job->get_category_html();
									echo $job->get_tag_html();
									echo $job->get_type_html();

									?>
								</div>
								<div class="col-actions jlt-col-15">

									<a href="<?php echo jlt_job_bookmark_delete_url(); ?>" class="jlt-btn-link">
										<i class="jlt-icon jltfa-trash-o"></i>
									</a>

								</div>
							</li>
						<?php endwhile; ?>
					<?php else: ?>
						<li>
							<div class="jlt-not-found"><?php _e( 'No Bookmarked Jobs', 'job-listings-bookmark' ) ?></div>
						</li>
					<?php endif; ?>
				</ul>
			</div>

			<?php jlt_member_pagination( $list_jobs ) ?>

		</form>
	</div>
<?php
do_action( 'jlt_member_manage_bookmark_job_after' );