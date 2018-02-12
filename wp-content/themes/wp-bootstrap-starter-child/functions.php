<?php

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

?>

<?php
function cptui_register_my_cpts() {

/**
 * Post Type: projets.
 */

$labels = array(
    "name" => __( "projets", "wp-bootstrap-starter" ),
    "singular_name" => __( "projet", "wp-bootstrap-starter" ),
);

$args = array(
    "label" => __( "projets", "wp-bootstrap-starter" ),
    "labels" => $labels,
    "description" => "",
    "public" => true,
    "publicly_queryable" => true,
    "show_ui" => true,
    "show_in_rest" => false,
    "rest_base" => "",
    "has_archive" => true,
    "show_in_menu" => true,
    "exclude_from_search" => false,
    "capability_type" => "post",
    "map_meta_cap" => true,
    "hierarchical" => false,
    "rewrite" => array( "slug" => "projet", "with_front" => true ),
    "query_var" => true,
    "supports" => array( "title", "thumbnail", "custom-fields" ),
);

register_post_type( "projet", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );
?>


<?php add_filter( 'pre_get_posts', 'my_get_posts' );

function my_get_posts( $query ) {
 if ( is_home() )
 $query->set( 'post_type', array( 'projet' ) );

 return $query;
}
?>
