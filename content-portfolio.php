<?php
/**
 * The Portfolio template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage MONYXI
 * @since MONYXI 1.0
 */

$monyxi_template_args = get_query_var('monyxi_template_args');
if (is_array($monyxi_template_args)) {
	$monyxi_columns = empty($monyxi_template_args['columns']) ? 2 : max(2, $monyxi_template_args['columns']);
	$monyxi_blog_style = array($monyxi_template_args['type'], $monyxi_columns);
} else {
	$monyxi_blog_style = explode('_', monyxi_get_theme_option('blog_style'));
	$monyxi_columns = empty($monyxi_blog_style[1]) ? 2 : max(2, $monyxi_blog_style[1]);
}
$monyxi_post_format = get_post_format();
$monyxi_post_format = empty($monyxi_post_format) ? 'standard' : str_replace('post-format-', '', $monyxi_post_format);
$monyxi_animation = monyxi_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" <?php
	post_class( 'post_item'
				. ' post_layout_portfolio'
				. ' post_layout_portfolio_'.esc_attr($monyxi_columns)
				. ' post_format_'.esc_attr($monyxi_post_format)
				. (is_sticky() && !is_paged() ? ' sticky' : '')
				. (!empty($monyxi_template_args['slider']) ? ' slider-slide swiper-slide' : '')
			);
	echo (!monyxi_is_off($monyxi_animation) && empty($monyxi_template_args['slider']) ? ' data-animation="'.esc_attr(monyxi_get_animation_classes($monyxi_animation)).'"' : '');
?>><?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	$monyxi_image_hover = !empty($monyxi_template_args['hover']) && !monyxi_is_inherit($monyxi_template_args['hover'])
								? $monyxi_template_args['hover']
								: monyxi_get_theme_option('image_hover');
	// Featured image
	monyxi_show_post_featured(array(
		'singular' => false,
		'hover' => $monyxi_image_hover,
		'no_links' => !empty($monyxi_template_args['no_links']),
		'thumb_size' => monyxi_get_thumb_size(strpos(monyxi_get_theme_option('body_style'), 'full')!==false || $monyxi_columns < 3 
								? 'masonry-big' 
								: 'masonry'),
		'show_no_image' => true,
		'class' => $monyxi_image_hover == 'dots' ? 'hover_with_info' : '',
		'post_info' => $monyxi_image_hover == 'dots' ? '<div class="post_info">'.esc_html(get_the_title()).'</div>' : ''
	));
	?>
</article>