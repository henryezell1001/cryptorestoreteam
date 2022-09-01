<?php
/**
 * The Header: Logo and main menu
 *
 * @package WordPress
 * @subpackage MONYXI
 * @since MONYXI 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js scheme_<?php
										 // Class scheme_xxx need in the <html> as context for the <body>!
										 echo esc_attr(monyxi_get_theme_option('color_scheme'));
										 ?>">
<head>
	<?php wp_head(); ?>
</head>

<body <?php	body_class(); ?>>
    <?php wp_body_open(); ?>

	<?php do_action( 'monyxi_action_before_body' ); ?>

	<div class="body_wrap">

		<div class="page_wrap"><?php
			// Desktop header
			$monyxi_header_type = monyxi_get_theme_option("header_type");
			if ($monyxi_header_type == 'custom' && !monyxi_is_layouts_available())
				$monyxi_header_type = 'default';
			get_template_part( apply_filters('monyxi_filter_get_template_part', "templates/header-{$monyxi_header_type}") );

			// Side menu
			if (in_array(monyxi_get_theme_option('menu_style'), array('left', 'right'))) {
				get_template_part( apply_filters('monyxi_filter_get_template_part', 'templates/header-navi-side') );
			}
			
			// Mobile menu
			get_template_part( apply_filters('monyxi_filter_get_template_part', 'templates/header-navi-mobile') );
			?>

			<div class="page_content_wrap">

				<?php if (monyxi_get_theme_option('body_style') != 'fullscreen') { ?>
				<div class="content_wrap">
				<?php } ?>

					<?php
					// Widgets area above page content
					monyxi_create_widgets_area('widgets_above_page');
					?>				

					<div class="content">
						<?php
						// Widgets area inside page content
						monyxi_create_widgets_area('widgets_above_content');
						?>				
