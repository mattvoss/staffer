<div class="container-fluid col-xs-12 col-sm-12 col-md-12">
    <?php while ( $staff_query->have_posts() ) : $staff_query->the_post(); ?>
        <div class="row staffer-entry">
            <div class="col-xs-12 col-sm-3 col-md-3">
                <?php
                    if ( isset ( $stafferoptions['gridlayout'] ) ) {
                        the_post_thumbnail( 'thumb', array( 'class' => 'aligncenter' ) );
                    } else {
                        the_post_thumbnail( 'thumb', array( 'class' => 'aligncenter' ) );
                    }
                ?>
            </div>
            <div class="col-xs-12 col-sm-9 col-md-9 text-left">
                <h2><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></h2>
                <?php
                    if ( get_post_meta( $post->ID, 'staffer_staff_title', true ) != '' ) {
                        echo get_post_meta( $post->ID, 'staffer_staff_title', true ).'<br />';
                    }
                    if ( get_post_meta( $post->ID, 'staffer_staff_email', true ) != '' ) {
                        $email = get_post_meta( $post->ID, 'staffer_staff_email', true );
                        echo '<a href="mailto:'.antispambot( $email ).'" target="_blank"><i class="fa fa-envelope"></i> '.$em$
                    }
                    if ( get_post_meta( $post->ID, 'staffer_staff_phone', true ) != '' ) {
                        $phone = get_post_meta( $post->ID, 'staffer_staff_phone', true );
                        echo '<i class="fa fa-phone"></i> '.get_post_meta( $post->ID, 'staffer_staff_phone', true );
                    }

                    if ($stafferoptions['estyle'] == null or $stafferoptions['estyle'] == 'excerpt' ) {
                        the_excerpt();
                    } elseif ($stafferoptions['estyle'] == 'full' ) {
                        the_content();
                    } elseif ($stafferoptions['estyle'] == 'none' ) {
                        // nothing to see here
                    }
                ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>
