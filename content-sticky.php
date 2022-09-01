<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 * @package WordPress
 * @subpackage MONYXI
 * @since MONYXI 1.0
 */

$monyxi_columns = max(1, min(3, count(get_option( 'sticky_posts' ))));
$monyxi_post_format = get_post_format();
$monyxi_post_format = empty($monyxi_post_format) ? 'standard' : str_replace('post-format-', '', $monyxi_post_format);
$monyxi_animation = monyxi_get_theme_option('blog_animation');

?><div class="column-1_<?php echo esc_attr($monyxi_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_sticky post_format_'.esc_attr($monyxi_post_format) ); ?>
	<?php echo (!monyxi_is_off($monyxi_animation) ? ' data-animation="'.esc_attr(monyxi_get_animation_classes($monyxi_animation)).'"' : ''); ?>
	>

	<?php
	if ( is_sticky() && is_home() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	monyxi_show_post_featured(array(
		'thumb_size' => monyxi_get_thumb_size($monyxi_columns==1 ? 'big' : ($monyxi_columns==2 ? 'med' : 'avatar'))
	));

	if ( !in_array($monyxi_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h6 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );
			// Post meta
			monyxi_show_post_meta(apply_filters('monyxi_filter_post_meta_args', array(), 'sticky', $monyxi_columns));
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div>