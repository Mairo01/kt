<?php /**
 * Returns word count of the sentences.
 *
 * @since Classic Blog 1.0.0
 */
if (!function_exists('classic_blog_words_count')) :
    function classic_blog_words_count($length = 25, $classic_blog_content = null)
    {
        $length = absint($length);
        $source_content = preg_replace('`\[[^\]]*\]`', '', $classic_blog_content);
        $trimmed_content = wp_trim_words($source_content, $length, '');
        return $trimmed_content;
    }
endif;


if (!function_exists('classic_blog_body_class')) :

    /**
     * body class.
     *
     * @since 1.0.0
     */
    function classic_blog_body_class($classic_blog_body_class)
    {
        $global_layout = classic_blog_get_option('global_layout');
        if ($global_layout == 'left-sidebar') {
            $classic_blog_body_class[] = 'left-sidebar ';
        } elseif ($global_layout == 'no-sidebar') {
            $classic_blog_body_class[] = 'no-sidebar ';
        } else {
            $classic_blog_body_class[] = 'right-sidebar ';

        }
        return $classic_blog_body_class;
    }
endif;

add_action('body_class', 'classic_blog_body_class');


if (!function_exists('classic_blog_excerpt_length') ):

    /**
     * Excerpt length
     *
     * @since  classic_blog 1.0.0
     *
     * @param null
     * @return int
     */
    function classic_blog_excerpt_length($length) {
        if ( is_admin() ) {
                return $length;
        }
        $excerpt_length = classic_blog_get_option('excerpt_length_global');
        if (absint($excerpt_length) > 0) {
            $excerpt_length = absint($excerpt_length);
        }

        return absint($excerpt_length);

    }

endif;
add_filter('excerpt_length', 'classic_blog_excerpt_length', 999);

if (!function_exists('classic_blog_excerpt_more') ):

    /**
     * Implement read more in excerpt.
     *
     * @since 1.0.0
     *
     * @param string $more The string shown within the more link.
     * @return string The excerpt.
     */
    function classic_blog_excerpt_more($more) {
        if ( is_admin() ) {
                return $more;
        }
        $flag_apply_excerpt_read_more = apply_filters('classic_blog_filter_excerpt_read_more', true);
        if (true !== $flag_apply_excerpt_read_more) {
            return $more;
        }

        $output         = $more;
        $read_more_text = esc_html(classic_blog_get_option('read_more_button_text'));
        if (!empty($read_more_text)) {
            $output = ' <a href="'.esc_url(get_permalink()).'" class="btn btn-link continue-link">'.esc_html($read_more_text).'</a>';
            $output = apply_filters('classic_blog_filter_read_more_link', $output);
        }
        return $output;

    }

    add_filter('excerpt_more', 'classic_blog_excerpt_more');
endif;

if (!function_exists('classic_blog_posts_navigations')):
    /**
     * Posts navigation.
     *
     * @since 1.0.0
     */
    function classic_blog_posts_navigations() {

        $pagination_type = classic_blog_get_option('pagination_type');

        switch ($pagination_type) {

            case 'default':
                the_posts_navigation();
                break;

            case 'numeric':
                the_posts_pagination();
                break;

            default:
                break;
        }

    }
endif;

add_action('classic_blog_posts_navigation', 'classic_blog_posts_navigations');

function classic_blog_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    }
    elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    }
    elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    }
    elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    }
    elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }
    elseif ( is_date() ) {
        $title = single_term_title( '', false );
    }
    return $title;
}

add_filter( 'get_the_archive_title', 'classic_blog_archive_title' );