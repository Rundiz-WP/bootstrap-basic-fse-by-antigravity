<?php
/**
 * Bootstrap Basic FSE functions and definitions
 */

if ( ! function_exists( 'bootstrap_basic_fse_setup' ) ) :
	function bootstrap_basic_fse_setup() {
		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );
	}
endif;
add_action( 'after_setup_theme', 'bootstrap_basic_fse_setup' );

/**
 * Enqueue scripts and styles.
 */
function bootstrap_basic_fse_scripts() {
	// Enqueue Bootstrap CSS
	wp_enqueue_style( 'bootstrap-css', get_theme_file_uri( 'assets/vendor/bootstrap/css/bootstrap.min.css' ), array(), '5.3.2' );

	// Enqueue Theme Styles
	wp_enqueue_style( 'bootstrap-basic-fse-style', get_stylesheet_uri(), array('bootstrap-css'), '1.0.0' );

	// Enqueue Bootstrap JS
	// Loaded in footer as requested
	wp_enqueue_script( 'bootstrap-js', get_theme_file_uri( 'assets/vendor/bootstrap/js/bootstrap.bundle.min.js' ), array(), '5.3.2', true );
	
	// Enqueue Theme JS for Bootstrap integration
	wp_enqueue_script( 'bootstrap-basic-fse-js', get_theme_file_uri( 'assets/js/theme.js' ), array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'bootstrap_basic_fse_scripts' );

/**
 * Filter core/query-pagination for exact Bootstrap markup.
 */
function bootstrap_basic_fse_pagination_filter( $block_content, $block ) {
    if ( 'core/query-pagination' !== $block['blockName'] ) {
        return $block_content;
    }

    // 1. Extract all anchors and spans (the actual page numbers/buttons)
    // We use a simplified regex because block output can be varied
    preg_match_all( '/<(a|span)[^>]*class="[^"]*page-numbers[^"]*"[^>]*>.*?<\/\1>/s', $block_content, $matches );

    if ( empty( $matches[0] ) ) {
        return $block_content;
    }

    $pagination_items = $matches[0];
    $bootstrap_output = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';

    foreach ( $pagination_items as $item ) {
        $is_active = strpos( $item, 'current' ) !== false;
        $is_dots = strpos( $item, 'dots' ) !== false;
        
        $item_class = 'page-item';
        if ( $is_active ) {
            $item_class .= ' active';
        }
        if ( $is_dots ) {
            $item_class .= ' disabled';
        }

        // Add 'page-link' class to the internal element
        // If it's a span without a class attribute, we add it. 
        // If it has one, we append it.
        if ( strpos( $item, 'class="' ) !== false ) {
            $item = str_replace( 'class="', 'class="page-link ', $item );
        } else {
            $item = str_replace( '<a ', '<a class="page-link" ', $item );
            $item = str_replace( '<span ', '<span class="page-link" ', $item );
        }

        $bootstrap_output .= '<li class="' . esc_attr( $item_class ) . '">' . $item . '</li>';
    }

    $bootstrap_output .= '</ul></nav>';

    return $bootstrap_output;
}
add_filter( 'render_block', 'bootstrap_basic_fse_pagination_filter', 10, 2 );

/**
 * Register "Bootstrap Basic FSE pagination" pattern.
 */
function bootstrap_basic_fse_register_patterns() {
    register_block_pattern(
        'bootstrap-basic-fse/pagination',
        array(
            'title'       => __( 'Bootstrap Basic FSE pagination', 'bootstrap-basic-fse' ),
            'description' => _x( 'A Bootstrap 5 styled pagination block.', 'Block pattern description', 'bootstrap-basic-fse' ),
            'categories'  => array( 'query' ),
            'content'     => '<!-- wp:query-pagination {"paginationArrow":"none","className":"pagination justify-content-center","layout":{"type":"flex","justifyContent":"center"}} -->
                <!-- wp:query-pagination-previous {"label":"Previous"} /-->
                <!-- wp:query-pagination-numbers /-->
                <!-- wp:query-pagination-next {"label":"Next"} /-->
            <!-- /wp:query-pagination -->',
        )
    );
}
add_action( 'init', 'bootstrap_basic_fse_register_patterns' );
