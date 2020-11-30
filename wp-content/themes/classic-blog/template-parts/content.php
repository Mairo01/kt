<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package classic_blog
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-mh="united-article">
    <?php if (is_archive() || is_home()) {
        echo "<div class='united-article'>";
    } ?>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php
				classic_blog_posted_on();
				classic_blog_posted_by();
			?>
		</div><!-- .entry-meta -->
		<?php
		endif; ?>
	</header><!-- .entry-header -->

    <?php
    if ( is_singular() ) :
        if (has_post_thumbnail($post->ID)) {
            $featured_image_single_post = get_post_meta($post->ID, 'classic-blog-meta-checkbox', true);
            if ('yes' != $featured_image_single_post) {
                classic_blog_post_thumbnail();
            }
            } else {
            }
        else :
        classic_blog_post_thumbnail();
    endif;
    ?>
    <?php if (has_excerpt() && !is_singular()) {
        echo "<div class='entry-content'>";
        the_excerpt();
        echo "</div>";
    } elseif (!is_singular()) {
        echo "<div class=\"entry-content\">";
        the_excerpt();
        echo "</div>";
    } else {?>
	<div class="entry-content">
        <?php
        $read_more_text = esc_html(classic_blog_get_option('read_more_button_text'));
        the_content(sprintf(
        /* translators: %s: Name of current post. */
            wp_kses($read_more_text, __('%s <i class="ion-ios-arrow-right read-more-right"></i>', 'classic-blog'), array('span' => array('class' => array()))),
            the_title('<span class="screen-reader-text">"', '"</span>', false)
        ));

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'classic-blog' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
<?php } ?>
	<footer class="entry-footer">
		<?php classic_blog_entry_footer(); ?>
	</footer><!-- .entry-footer -->
    <?php if (is_archive() || is_home()) {
        echo "</div>";
    } ?>
</article><!-- #post-<?php the_ID(); ?> -->
