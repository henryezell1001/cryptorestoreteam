<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package WordPress
 * @subpackage MONYXI
 * @since MONYXI 1.0
 */

						// Widgets area inside page content
						monyxi_create_widgets_area('widgets_below_content');
						?>				
					</div><!-- </.content> -->

					<?php
					// Show main sidebar
					get_sidebar();

					// Widgets area below page content
					monyxi_create_widgets_area('widgets_below_page');

					$monyxi_body_style = monyxi_get_theme_option('body_style');
					if ($monyxi_body_style != 'fullscreen') {
						?></div><!-- </.content_wrap> --><?php
					}
					?>
			</div><!-- </.page_content_wrap> -->

			<?php
			// Footer
			$monyxi_footer_type = monyxi_get_theme_option("footer_type");
			if ($monyxi_footer_type == 'custom' && !monyxi_is_layouts_available())
				$monyxi_footer_type = 'default';
			get_template_part( apply_filters('monyxi_filter_get_template_part', "templates/footer-{$monyxi_footer_type}") );
			?>

		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php wp_footer(); ?>

</body>
</html>