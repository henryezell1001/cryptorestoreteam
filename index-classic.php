<?php
/**
 * The template for homepage posts with "Classic" style
 *
 * @package WordPress
 * @subpackage MONYXI
 * @since MONYXI 1.0
 */

monyxi_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	monyxi_blog_archive_start();

	$monyxi_classes = 'posts_container '
						. (substr(monyxi_get_theme_option('blog_style'), 0, 7) == 'classic' 
							? 'columns_wrap columns_padding_bottom' 
							: 'masonry_wrap'
							);
	$monyxi_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$monyxi_sticky_out = monyxi_get_theme_option('sticky_style')=='columns' 
							&& is_array($monyxi_stickies) && count($monyxi_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($monyxi_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	if (!$monyxi_sticky_out) {
		if (monyxi_get_theme_option('first_post_large') && !is_paged() && !in_array(monyxi_get_theme_option('body_style'), array('fullwide', 'fullscreen'))) {
			the_post();
			get_template_part( apply_filters('monyxi_filter_get_template_part', 'content', 'excerpt'), 'excerpt' );
		}
		
		?><div class="<?php echo esc_attr($monyxi_classes); ?>"><?php
	}
	while ( have_posts() ) { the_post(); 
		if ($monyxi_sticky_out && !is_sticky()) {
			$monyxi_sticky_out = false;
			?></div><div class="<?php echo esc_attr($monyxi_classes); ?>"><?php
		}
		$monyxi_part = $monyxi_sticky_out && is_sticky() ? 'sticky' : 'classic';
		get_template_part( apply_filters('monyxi_filter_get_template_part', 'content', $monyxi_part), $monyxi_part );
	}
	
	?></div><?php

	monyxi_show_pagination();

	monyxi_blog_archive_end();

} else {

	if ( is_search() )
		get_template_part( apply_filters('monyxi_filter_get_template_part', 'content', 'none-search'), 'none-search' );
	else
		get_template_part( apply_filters('monyxi_filter_get_template_part', 'content', 'none-archive'), 'none-archive' );

}

get_footer();
?>