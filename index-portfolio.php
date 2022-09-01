<?php
/**
 * The template for homepage posts with "Portfolio" style
 *
 * @package WordPress
 * @subpackage MONYXI
 * @since MONYXI 1.0
 */

monyxi_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	monyxi_blog_archive_start();

	$monyxi_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$monyxi_sticky_out = monyxi_get_theme_option('sticky_style')=='columns' 
							&& is_array($monyxi_stickies) && count($monyxi_stickies) > 0 && get_query_var( 'paged' ) < 1;
	
	// Show filters
	$monyxi_cat = monyxi_get_theme_option('parent_cat');
	$monyxi_post_type = monyxi_get_theme_option('post_type');
	$monyxi_taxonomy = monyxi_get_post_type_taxonomy($monyxi_post_type);
	$monyxi_show_filters = monyxi_get_theme_option('show_filters');
	$monyxi_tabs = array();
	if (!monyxi_is_off($monyxi_show_filters)) {
		$monyxi_args = array(
			'type'			=> $monyxi_post_type,
			'child_of'		=> $monyxi_cat,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'taxonomy'		=> $monyxi_taxonomy,
			'pad_counts'	=> false
		);
		$monyxi_portfolio_list = get_terms($monyxi_args);
		if (is_array($monyxi_portfolio_list) && count($monyxi_portfolio_list) > 0) {
			$monyxi_tabs[$monyxi_cat] = esc_html__('All', 'monyxi');
			foreach ($monyxi_portfolio_list as $monyxi_term) {
				if (isset($monyxi_term->term_id)) $monyxi_tabs[$monyxi_term->term_id] = $monyxi_term->name;
			}
		}
	}
	if (count($monyxi_tabs) > 0) {
		$monyxi_portfolio_filters_ajax = true;
		$monyxi_portfolio_filters_active = $monyxi_cat;
		$monyxi_portfolio_filters_id = 'portfolio_filters';
		?>
		<div class="portfolio_filters monyxi_tabs monyxi_tabs_ajax">
			<ul class="portfolio_titles monyxi_tabs_titles">
				<?php
				foreach ($monyxi_tabs as $monyxi_id=>$monyxi_title) {
					?><li><a href="<?php echo esc_url(monyxi_get_hash_link(sprintf('#%s_%s_content', $monyxi_portfolio_filters_id, $monyxi_id))); ?>" data-tab="<?php echo esc_attr($monyxi_id); ?>"><?php echo esc_html($monyxi_title); ?></a></li><?php
				}
				?>
			</ul>
			<?php
			$monyxi_ppp = monyxi_get_theme_option('posts_per_page');
			if (monyxi_is_inherit($monyxi_ppp)) $monyxi_ppp = '';
			foreach ($monyxi_tabs as $monyxi_id=>$monyxi_title) {
				$monyxi_portfolio_need_content = $monyxi_id==$monyxi_portfolio_filters_active || !$monyxi_portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr(sprintf('%s_%s_content', $monyxi_portfolio_filters_id, $monyxi_id)); ?>"
					class="portfolio_content monyxi_tabs_content"
					data-blog-template="<?php echo esc_attr(monyxi_storage_get('blog_template')); ?>"
					data-blog-style="<?php echo esc_attr(monyxi_get_theme_option('blog_style')); ?>"
					data-posts-per-page="<?php echo esc_attr($monyxi_ppp); ?>"
					data-post-type="<?php echo esc_attr($monyxi_post_type); ?>"
					data-taxonomy="<?php echo esc_attr($monyxi_taxonomy); ?>"
					data-cat="<?php echo esc_attr($monyxi_id); ?>"
					data-parent-cat="<?php echo esc_attr($monyxi_cat); ?>"
					data-need-content="<?php echo (false===$monyxi_portfolio_need_content ? 'true' : 'false'); ?>"
				>
					<?php
					if ($monyxi_portfolio_need_content) 
						monyxi_show_portfolio_posts(array(
							'cat' => $monyxi_id,
							'parent_cat' => $monyxi_cat,
							'taxonomy' => $monyxi_taxonomy,
							'post_type' => $monyxi_post_type,
							'page' => 1,
							'sticky' => $monyxi_sticky_out
							)
						);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		monyxi_show_portfolio_posts(array(
			'cat' => $monyxi_cat,
			'parent_cat' => $monyxi_cat,
			'taxonomy' => $monyxi_taxonomy,
			'post_type' => $monyxi_post_type,
			'page' => 1,
			'sticky' => $monyxi_sticky_out
			)
		);
	}

	monyxi_blog_archive_end();

} else {

	if ( is_search() )
		get_template_part( apply_filters('monyxi_filter_get_template_part', 'content', 'none-search'), 'none-search' );
	else
		get_template_part( apply_filters('monyxi_filter_get_template_part', 'content', 'none-archive'), 'none-archive' );

}

get_footer();
?>