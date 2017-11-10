<?php
/**
 * staffer archive template
 */

get_header();

// loads the options
// must be carried over if using a custom template, else options will not work
$stafferoptions = get_option ( 'staffer' );
if (isset ($stafferoptions['customwrapper']) && isset ($stafferoptions['startwrapper'])) {
    $customstartwrapper = $stafferoptions['startwrapper'];
    echo $stafferoptions['startwrapper'];
} else {
    include ( plugin_dir_path (__FILE__) . 'inc/start-wrapper.php');
}
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
                <span itemprop="title"><?php echo $pagetitle; ?></span>
            </div>
        </div>
    <?php }
    echo '<h2>'.$pagetitle.'</h2>';

    // adds description if present
    $stafferdescription = $stafferoptions['sdesc'];
    if ($stafferdescription != '') {
        echo '<div class="staffer-page-description">'.wpautop( $stafferdescription ).'</div>';
    } ?>
</header>

<?php
// adds description if present
$taxdescription = term_description();
if ($taxdescription != '') {
    echo '<div class="staffer-page-description">'.wpautop( $taxdescription ).'</div>';
}

$args = array(
    'post_type'      => 'staff',
    'order'          => $order,
    'orderby'        => $orderby,
    'posts_per_page' => $number,
    'tax_query'      => $tax_query,
);
$staff_query = new WP_Query( $args );

if ( $staff_query->have_posts() ) {
    global $post;
    $stafferoptions = get_option( 'staffer' );
}

// chooses between the grid and list layout
// must be carried over if using a custom template, else options will not work
if (isset ($stafferoptions['gridlayout']) ) {
    include ( plugin_dir_path (__FILE__) . 'inc/staffer-grid.php');
}
if ( ! isset ($stafferoptions['gridlayout'] ) ) {
    include ( plugin_dir_path (__FILE__) . 'inc/staffer-list.php');
}

if ($staff_query->max_num_pages > 1) {
    echo '<div class="staffer-navigation">'.posts_nav_link().'</div>';
}

// prints the end wrapper
// must be carried over if using a custom template, else options will not work
if (isset ($stafferoptions['customwrapper']) && isset ($stafferoptions['endwrapper'])) {
    $customstartwrapper = $stafferoptions['endwrapper'];
    echo $stafferoptions['endwrapper'];
} else {
    include ( plugin_dir_path (__FILE__) . 'inc/end-wrapper.php');
}

get_footer();
