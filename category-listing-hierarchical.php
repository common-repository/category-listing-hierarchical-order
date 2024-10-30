<?php /*
* Plugin Name: Category Listing Hierarchical Order
* Description: This plugin use to Display Listing Post's Category in Hierarchical Tree Using Template
you want create new page in admin panel & select "List All Categories in Hierarchical Tree"
Template & Publish it. Now, You can view page for display all category listing in hierarchical.
* Version:     1.0.0
* Author:      Shail Mehta
* Author URI:  https://profiles.wordpress.org/mehtashail/
* License:     GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: wordpress.org
*/
if (!class_exists('category_listing_in_hierarchical')) {
    class Category_listing_in_hierarchical
    {
        function category_listing_in_hierarchical_install()
        {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        }

        function activate()
        {
            flush_rewrite_rules();
        }

        function deactivate()
        {
            flush_rewrite_rules();
        }
    }

    // activation and deactivation
    $category_listing_in_hierarchical = new Category_listing_in_hierarchical();
    register_activation_hook(__FILE__, array($category_listing_in_hierarchical, 'activate'));
    register_deactivation_hook(__FILE__, array($category_listing_in_hierarchical, 'deactivate'));

    add_action('init', 'category_listing_in_hierarchical_enqueue');
    function category_listing_in_hierarchical_enqueue()
    {
        // enqueue style
        wp_enqueue_style('Category List Style', plugin_dir_url(__FILE__) . 'css/style.css', array(), false, 'all');
    }

    add_filter('theme_page_templates', 'category_listing_in_hierarchical_page_template_to_dropdown');

    function category_listing_in_hierarchical_page_template_to_dropdown($templates)
    {
        $templates[plugin_dir_path(__FILE__) . 'templates/page-template.php'] = __('List All Categories in Hierarchical Tree');
        return $templates;
    }

    add_filter('template_include', 'category_listing_in_hierarchical_change_page_template', 99);
    function category_listing_in_hierarchical_change_page_template($template)
    {
        get_header();
        ?>
        <div class="wrap">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                    <?php if (is_page()) {
                        $args = array(
                            'type' => 'post',
                            'child_of' => 0,
                            'parent' => 0,
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'hide_empty' => 1,
                            'title_li' => '',
                            'hierarchical' => false,
                            'exclude' => '',
                            'include' => '',
                            'number' => '',
                            'taxonomy' => 'category',
                            'depth' => 1,
                            'pad_counts' => false
                        );
                        $cats = get_categories($args);
                        foreach ($cats as $cat) {
                            if ($cat->parent == 0) {
                                $parent_cat = null;
                                $head = $cat->name;
                                $head_id = $cat->term_id;
                                $category_link = get_category_link($cat->cat_ID);
                            }

                            echo '<ul class="order-list-for-cat"><li class="order-list-for-catlist"><a href="' . esc_url($category_link) . '" >' . $cat->name . '</a></li>';
                            wp_list_categories("child_of={$head_id}&show_option_none=&exclude=&title_li=");
                            echo "</ul>";
                        }
                    } ?>
                </main>
            </div>
        </div>
        <?php
        get_footer();
    }
}
?>