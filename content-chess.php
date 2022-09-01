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
	$monyxi_columns = empty($monyxi_template_args['columns']) ? 1 : max(1, min(3, $monyxi_template_args['columns']));
	$monyxi_blog_style = array($monyxi_template_args['type'], $monyxi_columns);
} else {
	$monyxi_blog_style = explode('_', monyxi_get_theme_option('blog_style'));
	$monyxi_columns = empty($monyxi_blog_style[1]) ? 1 : max(1, min(3, $monyxi_blog_style[1]));
}
$monyxi_expanded = !monyxi_sidebar_present() && monyxi_is_on(monyxi_get_theme_option('expand_content'));
$monyxi_post_format = get_post_format();
$monyxi_post_format = empty($monyxi_post_format) ? 'standard' : str_replace('post-format-', '', $monyxi_post_format);
$monyxi_animation = monyxi_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" <?php
	post_class( 'post_item'
				. ' post_layout_chess'
				. ' post_layout_chess_'.esc_attr($monyxi_columns)
				. ' post_format_'.esc_attr($monyxi_post_format)
				. (!empty($monyxi_template_args['slider']) ? ' slider-slide swiper-slide' : '')
				);
	echo (!monyxi_is_off($monyxi_animation) && empty($monyxi_template_args['slider']) ? ' data-animation="'.esc_attr(monyxi_get_animation_classes($monyxi_animation)).'"' : '');
?>>

	<?php
	// Add anchor
	if ($monyxi_columns == 1 && !is_array($monyxi_template_args) && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="post_'.esc_attr(get_the_ID()).'" title="'.the_title_attribute( array( 'echo' => false ) ).'" icon="'.esc_attr(monyxi_get_post_icon()).'"]');
	}

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$monyxi_hover = !empty($monyxi_template_args['hover']) && !monyxi_is_inherit($monyxi_template_args['hover'])
						? $monyxi_template_args['hover'] 
						: monyxi_get_theme_option('image_hover');
	monyxi_show_post_featured( array(
											'class' => $monyxi_columns == 1 && !is_array($monyxi_template_args) ? 'monyxi-full-height' : '',
											'singular' => false,
											'hover' => $monyxi_hover,
											'no_links' => !empty($monyxi_template_args['no_links']),
											'show_no_image' => true,
											'thumb_bg' => true,
											'thumb_size' => monyxi_get_thumb_size(
																	strpos(monyxi_get_theme_option('body_style'), 'full')!==false
																		? ( $monyxi_columns > 1 ? 'huge' : 'original' )
																		: (	$monyxi_columns > 2 ? 'big' : 'huge')
																	)
											) 
										);

	?><div class="post_inner"><div class="post_inner_content"><?php 

		?><div class="post_header entry-header"><?php 
			do_action('monyxi_action_before_post_title'); 

			// Post title
			if (empty($monyxi_template_args['no_links']))
				the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
			else
				the_title( '<h3 class="post_title entry-title">', '</h3>' );
			
			do_action('monyxi_action_before_post_meta'); 

			// Post meta
			$monyxi_components = monyxi_array_get_keys_by_value(monyxi_get_theme_option('meta_parts'));
			$monyxi_counters = monyxi_array_get_keys_by_value(monyxi_get_theme_option('counters'));
			$monyxi_post_meta = empty($monyxi_components) || in_array($monyxi_hover, array('border', 'pull', 'slide', 'fade'))
										? '' 
										: monyxi_show_post_meta(apply_filters('monyxi_filter_post_meta_args', array(
												'components' => $monyxi_components,
												'counters' => $monyxi_counters,
												'seo' => false,
												'echo' => false
												), $monyxi_blog_style[0], $monyxi_columns)
											);
			monyxi_show_layout($monyxi_post_meta);
		?></div><!-- .entry-header -->
	
		<div class="post_content entry-content"><?php
			if (empty($monyxi_template_args['hide_excerpt'])) {
				?><div class="post_content_inner"><?php
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
				monyxi_show_layout($monyxi_post_meta);
			}
			// More button
			if ( empty($monyxi_template_args['no_links']) && !in_array($monyxi_post_format, array('link', 'aside', 'status', 'quote')) ) {
				?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'monyxi'); ?></a></p><?php
			}
			?>
		</div><!-- .entry-content -->

	</div></div><!-- .post_inner -->

</article>