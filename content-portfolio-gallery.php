<?php
/**
 * The Gallery template to display posts
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
$monyxi_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

?><article id="post-<?php the_ID(); ?>" <?php
	post_class( 'post_item'
				. ' post_layout_portfolio'
				. ' post_layout_gallery'
				. ' post_layout_gallery_'.esc_attr($monyxi_columns)
				. ' post_format_'.esc_attr($monyxi_post_format)
				. (!empty($monyxi_template_args['slider']) ? ' slider-slide swiper-slide' : '')
			);
	echo (!monyxi_is_off($monyxi_animation) && empty($monyxi_template_args['slider']) ? ' data-animation="'.esc_attr(monyxi_get_animation_classes($monyxi_animation)).'"' : '');
	?>
	data-size="<?php if (!empty($monyxi_image[1]) && !empty($monyxi_image[2])) echo intval($monyxi_image[1]) .'x' . intval($monyxi_image[2]); ?>"
	data-src="<?php if (!empty($monyxi_image[0])) echo esc_url($monyxi_image[0]); ?>"
><?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$monyxi_image_hover = 'icon';	
	if (in_array($monyxi_image_hover, array('icons', 'zoom'))) $monyxi_image_hover = 'dots';
	$monyxi_components = monyxi_array_get_keys_by_value(monyxi_get_theme_option('meta_parts'));
	$monyxi_counters = monyxi_array_get_keys_by_value(monyxi_get_theme_option('counters'));
	monyxi_show_post_featured(array(
		'hover' => $monyxi_image_hover,
		'singular' => false,
		'no_links' => !empty($monyxi_template_args['no_links']),
		'thumb_size' => monyxi_get_thumb_size( strpos(monyxi_get_theme_option('body_style'), 'full')!==false || $monyxi_columns < 3 ? 'masonry-big' : 'masonry' ),
		'thumb_only' => true,
		'show_no_image' => true,
		'post_info' => '<div class="post_details">'
							. '<h2 class="post_title">'
								. (empty($monyxi_template_args['no_links']) 
									? '<a href="'.esc_url(get_permalink()).'">' . esc_html(get_the_title()) . '</a>'
									: esc_html(get_the_title())
									)
							. '</h2>'
							. '<div class="post_description">'
								. (!empty($monyxi_components)
									? monyxi_show_post_meta(apply_filters('monyxi_filter_post_meta_args', array(
											'components' => $monyxi_components,
											'counters' => $monyxi_counters,
											'seo' => false,
											'echo' => false
											), $monyxi_blog_style[0], $monyxi_columns))
									: ''
									)
								. (empty($monyxi_template_args['hide_excerpt'])
									? '<div class="post_description_content">' . get_the_excerpt() . '</div>'
									: ''
									)
								. (empty($monyxi_template_args['no_links']) 
									? '<a href="'.esc_url(get_permalink()).'" class="theme_button post_readmore"><span class="post_readmore_label">' . esc_html__('Learn more', 'monyxi') . '</span></a>' 
									: ''
									)
							. '</div>'
						. '</div>'
	));
?></article>