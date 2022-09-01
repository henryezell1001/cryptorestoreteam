<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package WordPress
 * @subpackage MONYXI
 * @since MONYXI 1.0
 */

if (monyxi_sidebar_present()) {
	ob_start();
	$monyxi_sidebar_name = monyxi_get_theme_option('sidebar_widgets');
	monyxi_storage_set('current_sidebar', 'sidebar');
	if ( is_active_sidebar($monyxi_sidebar_name) ) {
		dynamic_sidebar($monyxi_sidebar_name);
	}
	$monyxi_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($monyxi_out)) {
		$monyxi_sidebar_position = monyxi_get_theme_option('sidebar_position');
		?>
		<div class="sidebar <?php echo esc_attr($monyxi_sidebar_position); ?> widget_area<?php if (!monyxi_is_inherit(monyxi_get_theme_option('sidebar_scheme'))) echo ' scheme_'.esc_attr(monyxi_get_theme_option('sidebar_scheme')); ?>" role="complementary">
			<div class="sidebar_inner">
				<?php
				do_action( 'monyxi_action_before_sidebar' );
				monyxi_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $monyxi_out));
				do_action( 'monyxi_action_after_sidebar' );
				?>
			</div><!-- /.sidebar_inner -->
		</div><!-- /.sidebar -->
		<?php
	}
}
?>