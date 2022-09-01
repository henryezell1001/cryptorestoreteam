<?php
/**
 * The default template to display the content
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
	if (!empty($monyxi_template_args['slider'])) {
		?><div class="slider-slide swiper-slide"><?php
	} else if ($monyxi_columns > 1) {
		?><div class="column-1_<?php echo esc_attr($monyxi_columns); ?>"><?php
	}
}
$monyxi_expanded = !monyxi_sidebar_present() && monyxi_is_on(monyxi_get_theme_option('expand_content'));
$monyxi_post_format = get_post_format();
$monyxi_post_format = empty($monyxi_post_format) ? 'standard' : str_replace('post-format-', '', $monyxi_post_format);
$monyxi_animation = monyxi_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_excerpt post_format_'.esc_attr($monyxi_post_format) ); ?>
	<?php echo (!monyxi_is_off($monyxi_animation) && empty($monyxi_template_args['slider']) ? ' data-animation="'.esc_attr(monyxi_get_animation_classes($monyxi_animation)).'"' : ''); ?>
	><?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$monyxi_hover = !empty($monyxi_template_args['hover']) && !monyxi_is_inherit($monyxi_template_args['hover'])
						? $monyxi_template_args['hover'] 
						: monyxi_get_theme_option('image_hover');
	monyxi_show_post_featured(array(
									'singular' => false,
									'no_links' => !empty($monyxi_template_args['no_links']),
									'hover' => $monyxi_hover,
									'thumb_size' => monyxi_get_thumb_size( strpos(monyxi_get_theme_option('body_style'), 'full')!==false ? 'full' : ($monyxi_expanded ? 'huge' : 'big') ) 
									));
?>
<div class="extra_wrap<?php echo esc_html(get_the_title() != '' ? '' : ' no_title'); ?>">
<?php
	// Title and post meta
	if (get_the_title() != '') {
		?>
		<div class="post_header entry-header">
			<?php
			do_action('monyxi_action_before_post_title');

            $monyxi_components = monyxi_array_get_keys_by_value(monyxi_get_theme_option('meta_parts'));
            $monyxi_counters = monyxi_array_get_keys_by_value(monyxi_get_theme_option('counters'));

            $pos = strpos($monyxi_components, 'categories', 0);
            $cats = get_post_type()=='post' ? get_the_category_list(' ') : apply_filters('monyxi_filter_get_post_categories', '');
            if ($pos !== false && !empty($cats)) {
                ?>
                <span class="post_meta_item post_categories"><?php monyxi_show_layout($cats); ?></span>
                <?php
            }

			// Post title
			if (empty($monyxi_template_args['no_links']))
				the_title( sprintf( '<h2 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			else
				the_title( '<h2 class="post_title entry-title">', '</h2>' );

			?>
		</div><!-- .post_header --><?php
	}
	
	// Post content
	if (empty($monyxi_template_args['hide_excerpt'])) {

		?><div class="post_content entry-content"><?php
			if (monyxi_get_theme_option('blog_content') == 'fullpost') {
				// Post content area
				?><div class="post_content_inner"><?php
					the_content( '' );
				?></div><?php
				// Inner pages
				wp_link_pages( array(
					'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'monyxi' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'monyxi' ) . ' </span>%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				) );
			} else {
				// Post content area
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
				// More button
				if ( get_the_title() == '' && empty($monyxi_template_args['no_links']) && !in_array($monyxi_post_format, array('link', 'aside', 'status', 'quote')) ) {
					?><p><a class="more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'monyxi'); ?></a></p><?php
				}
			}


        do_action('monyxi_action_before_post_meta');

        // Post meta
        if (!in_array($monyxi_post_format, array('quote')) && !empty($monyxi_components) && !in_array($monyxi_hover, array('border', 'pull', 'slide', 'fade')))
            monyxi_show_post_meta(apply_filters('monyxi_filter_post_meta_args', array(
                    'components' => $monyxi_components,
                    'counters' => $monyxi_counters,
                    'seo' => false
                ), 'excerpt', 1)
            );


		?></div><!-- .entry-content --><?php
	}
?></div></article><?php

if (is_array($monyxi_template_args)) {
	if (!empty($monyxi_template_args['slider']) || $monyxi_columns > 1) {
		?></div><?php
	}
}
?>