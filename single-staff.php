<?php

/**
 * staffer single template
 */

get_header();

// prints the start wrapper
// must be carried over if using a custom template, else options will not work
$stafferoptions = get_option( 'staffer' );
if ( isset ( $stafferoptions['customwrapper'] ) && isset ( $stafferoptions['startwrapper'] ) ) {
	$customstartwrapper = $stafferoptions['startwrapper'];
	echo $stafferoptions['startwrapper'];
} else {
	include( plugin_dir_path( __FILE__ ) . 'inc/start-wrapper.php' );
}

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        ?>
        <header class="staffer-staff-header">
            <?php
            // checks for slug and builds path
            if ( get_option( 'permalink_structure' ) ) {

                $pageslug = $stafferoptions['slug'];
                if ( $pageslug == '' ) {
                    $pageslug = 'staff';
                }
                $homeurl     = esc_url( home_url( '/' ) );
                $basepageurl = $homeurl . $pageslug;
            } else {
                $homeurl     = esc_url( home_url( '/' ) );
                $basepageurl = $homeurl . '?post_type=staff';
            }
            $pagetitle = $stafferoptions['ptitle'];
            if ( $pagetitle == '' ) {
                $pagetitle = 'Staff';
            }

            // checks for manual mode
            // does not display breadcrumb trail in manual mode
            if ( ! isset ( $stafferoptions['manual_mode'] ) ) { ?>
                <div class="staffer-breadcrumbs">
                    <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url"><?php _e( 'Home', 'staffer' ); ?></a> &#8250;
                        <a href="<?php echo esc_url( $basepageurl ); ?>" itemprop="url"><?php echo $pagetitle; ?></a> &#8250;
                        <span itemprop="title"><?php the_title(); ?></span>
                    </div>
                </div>
            <?php }

            echo '<h2>';
            echo the_title();
            echo '</h2>';
            if ( get_post_meta( $post->ID, 'staffer_staff_title', true ) != '' ) {
                //echo '<em>'.get_post_meta( $post->ID, 'staffer_staff_title', true ) . '</em><br>';
            }
            ?>
        </header>

        <div class="container-fluid col-xs-12 col-sm-12 col-md-12">
            <div class="row">

                <div class="col-xs-12 col-sm-3 col-md-3">

                    <?php
                        the_post_thumbnail( 'medium', array( 'class' => 'alignleft' ) );
                    ?>
                </div>

                <div class="col-xs-12 col-sm-9 col-md-9 text-left">
                    <p>
                        <?php
                            if ( get_post_meta( $post->ID, 'staffer_staff_title', true ) != '' ) {
                                echo get_post_meta( $post->ID, 'staffer_staff_title', true ).'<br />';
                            }
                            if ( get_post_meta( $post->ID, 'staffer_staff_email', true ) != '' ) {
                                $email = get_post_meta( $post->ID, 'staffer_staff_email', true );
                                echo '<a href="mailto:'.antispambot( $email ).'" target="_blank"><i class="fa fa-envelope"></i> '.$email.'</a><br />';
                            }
                            if ( get_post_meta( $post->ID, 'staffer_staff_phone', true ) != '' ) {
                                $phone = get_post_meta( $post->ID, 'staffer_staff_phone', true );
                                echo '<i class="fa fa-phone"></i> '.get_post_meta( $post->ID, 'staffer_staff_phone', true ).'<br />';
                            }
                            if ( get_post_meta( $post->ID, 'staffer_staff_website', true ) != '' ) {
                                $website = get_post_meta( $post->ID, 'staffer_staff_website', true );
                                echo '<a href="'.get_post_meta( $post->ID, 'staffer_staff_website', true ).'" target="_blank"><i class="fa fa-user fa-fw"></i></a>';
                            }
                        ?>
                    </p>
                    <p>
                        <?php
                            // social + contact links
                            if ( get_post_meta( $post->ID, 'staffer_staff_fb', true ) != '' ) {
                                echo '<a href="'.get_post_meta( $post->ID, 'staffer_staff_fb', true ).'" target="_blank"><i class="fa fa-facebook fa-fw"></i></a>';
                            }
                            if ( get_post_meta( $post->ID, 'staffer_staff_gplus', true ) != '' ) {
                                echo '<a href="'.get_post_meta( $post->ID, 'staffer_staff_gplus', true ).'" target="_blank"><i class="fa fa-google-plus fa-fw"></i></a>';
                            }
                            if ( get_post_meta( $post->ID, 'staffer_staff_twitter', true ) != '' ) {
                                echo '<a href="'.get_post_meta( $post->ID, 'staffer_staff_twitter', true ).'" target="_blank"><i class="fa fa-twitter fa-fw"></i></a>';
                            }
                            if ( get_post_meta( $post->ID, 'staffer_staff_linkedin', true ) != '' ) {
                                echo '<a href="'.get_post_meta( $post->ID, 'staffer_staff_linkedin', true ).'" target="_blank"><i class="fa fa-linkedin fa-fw"></i></a>';
                            }
                        ?>
                    </p>
                    <?php
                        $terms = get_the_term_list( $post->ID, 'department', '', ', ' );
                        if ( $terms != '' ) {
                            echo '<p>'.$terms.'</p>';
                        }

                        the_content();
                    ?>
                </div>
            </div>
        </div>

    <?php endwhile;
    endif; ?>

<?php
/* -------- code for displaying latest blog posts by the member ------- */
$staffer_options = get_option( 'staffer' );
if ( ! empty( $staffer_options['display_author_posts'] ) ) {
	$synced_wp_profile = get_post_meta( $post->ID, 'staffer_synced_wp_profile', true );
	if ( ! empty( $synced_wp_profile ) ) {
		$author_query = array(
			'author'         => $synced_wp_profile,
			'posts_per_page' => 9,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		$author_posts = new WP_Query( $author_query );
		if ( $author_posts->have_posts() ) :
			?>
			<div id="staffer-author-posts">
				<h2>Latest posts by <?php echo get_the_author_meta( 'display_name', $synced_wp_profile ); ?></h2>
				<ul class="posts-container">
					<?php
					while ( $author_posts->have_posts() ) :
						$author_posts->the_post();
						?>
						<li>
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<div class="post-header">
									<h2 class="title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h2>
									<span class="meta-author"><span><?php echo __( 'By', 'staffer' ); ?></span> <?php the_author_posts_link(); ?></span>
									<span class="meta-category">| <?php the_category( ', ' ); ?></span> <span class="meta-comment-count">| <a href="<?php comments_link(); ?>">
											<?php comments_number( __( 'No Comments', 'staffer' ), __( 'One Comment ', 'staffer' ), __( '% Comments', 'staffer' ) ); ?></a></span>
								</div>
								<!--/post-header-->
								<div class="excerpt post-excerpt">
									<?php the_excerpt(); ?>
								</div>
								<a class="more-link" href="<?php echo get_permalink(); ?>"><span class="continue-reading"><?php echo __( 'Read More', 'staffer' ); ?></span></a>
							</article>
							<!--/article-->
						</li>
					<?php
					endwhile;
					?>
				</ul>
			</div><!--/staffer-author-posts-->
		<?php
		endif;
		wp_reset_postdata();
	} // end of checking synced author id
} // end of checking staffer option - display author posts
/* -------- / code for displaying latest blog posts by the member ------- */
?>

<?php
// prints the end wrapper
// must be carried over if using a custom template, else options will not work
if ( isset ( $stafferoptions['customwrapper'] ) && isset ( $stafferoptions['endwrapper'] ) ) {
	$customstartwrapper = $stafferoptions['endwrapper'];
	echo $stafferoptions['endwrapper'];
} else {
	include( plugin_dir_path( __FILE__ ) . 'inc/end-wrapper.php' );
}
?>
<?php get_footer(); ?>
