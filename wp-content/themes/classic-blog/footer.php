<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package classic_blog
 */

?>

	</div><!-- #content -->


<div class="footer-social-menu">
    <div class="wrapper">
        <?php
        if ( has_nav_menu( 'social' ) ) : ?>
            <nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Social Links Menu', 'classic-blog' ); ?>">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'social',
                    'menu_class'     => 'social-links-menu',
                    'depth'          => 1,
                    'link_before'    => '<span>',
                    'link_after'     => '</span>' . classic_blog_get_svg( array( 'icon' => 'chain' ) ),
                ) );
                ?>
            </nav>
        <?php endif; ?>
    </div>
</div>
<footer id="colophon" class="site-footer">
		<?php if (is_active_sidebar('footer-col-1') || is_active_sidebar('footer-col-2') || is_active_sidebar('footer-col-3')) { ?>
	    <div class="site-widget-area clear">
		   <div class="wrapper">
			    <div class="row">
					<?php if (is_active_sidebar('footer-col-1')) : ?>
					   <div class="col col-three-1">
						   <?php dynamic_sidebar('footer-col-1'); ?>
					   </div>
					<?php endif; ?>
					<?php if (is_active_sidebar('footer-col-2')) : ?>
					   <div class="col col-three-1">
						   <?php dynamic_sidebar('footer-col-2'); ?>
					   </div>
					<?php endif; ?>
					<?php if (is_active_sidebar('footer-col-3')) : ?>
					   <div class="col col-three-1">
						   <?php dynamic_sidebar('footer-col-3'); ?>
					   </div>
					<?php endif; ?>
			    </div>
		   </div>
	    </div>
		<?php } ?>
		<div class="site-info clear <?php if ( !has_nav_menu( 'footer' ) ){ echo "footer-nav-disabled";} ?>" data-mh="site-bottom">
			<div class="wrapper">
				<div class="row">
					<div class="col col-five" data-mh="site-bottom">
						<?php
						if ( has_nav_menu( 'footer' ) ) : ?>
							<nav class="footer-navigation" role="navigation">
								<?php
								wp_nav_menu( array(
									'theme_location' => 'footer',
									'menu_class'     => 'footer-menu',
									'depth'          => 1,
								) );
								?>
							</nav>
						<?php endif; ?>
					</div>
					<div class="col col-five" data-mh="site-bottom">
						<div class="copyright-info">
                            <div class="copyright-info-details">
                                <?php
                                $pb_copyright_text = classic_blog_get_option('copyright_text');
                                if (!empty ($pb_copyright_text)) {
                                    echo wp_kses_post(classic_blog_get_option('copyright_text'));
                                }
                                ?>
                                <span class="sep"> | </span>
                                <?php printf(esc_html__('Theme: %1$s by %2$s.', 'classic-blog'), 'Classic Blog', '<a href="http://unitedtheme.com/">Unitedtheme</a>');
                                ?>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>
</div>
<a href="#" onclick="event.preventDefault()" class="scroll-up"><span> <?php echo esc_html('Scroll up','flash-blog'); ?></span></a>

<?php wp_footer(); ?>

</body>
</html>
