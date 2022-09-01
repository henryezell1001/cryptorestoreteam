<?php
/**
 * The Classic template to display the content
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
$monyxi_expanded = !monyxi_sidebar_present() && monyxi_is_on(monyxi_get_theme_option('expand_content'));
$monyxi_animation = monyxi_get_theme_option('blog_animation');
$monyxi_components = monyxi_array_get_keys_by_value(monyxi_get_theme_option('meta_parts'));
$monyxi_counters = monyxi_array_get_keys_by_value(monyxi_get_theme_option('counters'));

$monyxi_post_format = get_post_format();
$monyxi_post_format = empty($monyxi_post_format) ? 'standard' : str_replace('post-format-', '', $monyxi_post_format);

?><div class="<?php
	if (!empty($monyxi_template_args['slider']))
		echo ' slider-slide swiper-slide';
	else
		echo ('classic' == $monyxi_blog_style[0] ? 'column' : 'masonry_item masonry_item') . '-1_' . esc_attr($monyxi_columns);
?>"><?php
	?><article id="post-<?php the_ID(); ?>" <?php
		post_class( 'post_item post_format_'.esc_attr($monyxi_post_format)
					. ' post_layout_classic post_layout_classic_'.esc_attr($monyxi_columns)
					. ' post_layout_'.esc_attr($monyxi_blog_style[0]) 
					. ' post_layout_'.esc_attr($monyxi_blog_style[0]).'_'.esc_attr($monyxi_columns)
		);
		echo (!monyxi_is_off($monyxi_animation) && empty($monyxi_template_args['slider']) ? ' data-animation="'.esc_attr(monyxi_get_animation_classes($monyxi_animation)).'"' : '');
	?>><?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$monyxi_hover = !empty($monyxi_template_args['hover']) && !monyxi_is_inherit($monyxi_template_args['hover'])
						? $monyxi_template_args['hover'] 
						: monyxi_get_theme_option('image_hover');
	monyxi_show_post_featured( array( 'thumb_size' => monyxi_get_thumb_size($monyxi_blog_style[0] == 'classic'
													? (strpos(monyxi_get_theme_option('body_style'), 'full')!==false 
															? ( $monyxi_columns > 2 ? 'big' : 'huge' )
															: (	$monyxi_columns > 2
																? ($monyxi_expanded ? 'med' : 'small')
																: ($monyxi_expanded ? 'big' : 'med')
																)
														)
													: (strpos(monyxi_get_theme_option('body_style'), 'full')!==false 
															? ( $monyxi_columns > 2 ? 'masonry-big' : 'full' )
															: (	$monyxi_columns <= 2 && $monyxi_expanded ? 'masonry-big' : 'masonry')
														)
												),
										'hover' => $monyxi_hover,
										'no_links' => !empty($monyxi_template_args['no_links']),
										'singular' => false
								) );

	if ( !in_array($monyxi_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php 
			do_action('monyxi_action_before_post_title'); 

			// Post title
			if (empty($monyxi_template_args['no_links']))
				the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
			else
				the_title( '<h4 class="post_title entry-title">', '</h4>' );

			do_action('monyxi_action_before_post_meta'); 

			// Post meta
			if (!empty($monyxi_components) && !in_array($monyxi_hover, array('border', 'pull', 'slide', 'fade'))) {
				monyxi_show_post_meta(apply_filters('monyxi_filter_post_meta_args', array(
					'components' => $monyxi_components,
					'counters' => $monyxi_counters,
					'seo' => false
					), $monyxi_blog_style[0], $monyxi_columns)
				);
			}

			do_action('monyxi_action_after_post_meta'); 
			?>
		</div><!-- .entry-header -->
		<?php
	}		
	?>

	<div class="post_content entry-content"><?php
		if (empty($monyxi_template_args['hide_excerpt'])) {
			?><div class="post_content_inner">
				<?php
				if (has_excerpt()) {
					the_excerpt();
				} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
					the_content( '' );
				} else if (in_array($monyxi_post_format, array('link', 'aside', 'status'))) {
					the_content();
				} else if ($monyxi_post_format == 'quote') {
					if (($quote = monyxi_get_tag(get_the_content(), '<blockquote>', '</blockquote>'))!='')
						monyxi_show_layout(wpautop($quote));
					else
						the_excerpt();
				} else if (substr(get_the_content(), 0, 4)!='[vc_') {
					the_excerpt();
				}
			?></div><?php
		}
		// Post meta
		if (in_array($monyxi_post_format, array('link', 'aside', 'status', 'quote'))) {
			if (!empty($monyxi_components))
				monyxi_show_post_meta(apply_filters('monyxi_filter_post_meta_args', array(
					'components' => $monyxi_components,
					'counters' => $monyxi_counters
					), $monyxi_blog_style[0], $monyxi_columns)
				);
		}
		// More button
		if ( false && empty($monyxi_template_args['no_links']) && !in_array($monyxi_post_format, array('link', 'aside', 'status', 'quote')) ) {
			?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'monyxi'); ?></a></p><?php
		}
		?>
	</div><!-- .entry-content -->

</article></div>