<?php
/**
 * The Front Page template file.
 *
 * @package WordPress
 * @subpackage MONYXI
 * @since MONYXI 1.0.31
 */

get_header();

// If front-page is a static page
if (get_option('show_on_front') == 'page') {

	// If Front Page Builder is enabled - display sections
	if (monyxi_is_on(monyxi_get_theme_option('front_page_enabled'))) {

		if ( have_posts() ) the_post();

		$monyxi_sections = monyxi_array_get_keys_by_value(monyxi_get_theme_option('front_page_sections'), 1, false);
		if (is_array($monyxi_sections)) {
			foreach ($monyxi_sections as $monyxi_section) {
				get_template_part( apply_filters('monyxi_filter_get_template_part', "front-page/section", $monyxi_section), $monyxi_section );
			}
		}
	
	// Else if this page is blog archive
	} else if (is_page_template('blog.php')) {
		get_template_part( apply_filters('monyxi_filter_get_template_part', 'blog') );

	// Else - display native page content
	} else {
		get_template_part( apply_filters('monyxi_filter_get_template_part', 'page') );
	}

// Else get index template to show posts
} else {
	get_template_part( apply_filters('monyxi_filter_get_template_part', 'index') );
}

get_footer();
?>