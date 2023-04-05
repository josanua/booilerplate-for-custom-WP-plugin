<?php

/*
Plugin Name: Movie Reviews
Description: Movie Review post type with all the trimmings
Author: Your Name
Author URI: http://www.example.org/
Version: 1.0
Text Domain: movie-reviews
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// required plugin class
require_once dirname( __FILE__ ) . '/lib/class-tgm-plugin-activation.php';
require_once dirname( __FILE__ ) . '/lib/widgets.class.php';

/**
 * Using Singleton Pattern
 */
class Movie_Reviews {
    private static $instance;

    const FIELD_PREFIX = 'jcmr_';
    const CPT_SLUG = 'movie_review';

    // this needs to be hard-coded, but this serves as a reminder,
    // and a find replace when searching and replacing
    const TEXT_DOMAIN = 'jc-movie-reviews';

    public static function getInstance() {
        if (self::$instance === NULL) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        // initialize Movie Review custom post type
        add_action('init', 'Movie_Reviews::register_post_type');

        // initialize custom taxonomy(ies) for movie reviews
        add_action( 'init', 'Movie_Reviews::register_taxonomies');

        // register custom widgets
        add_action( 'widgets_init',  array( $this, 'register_widgets' ));

        // initialize custom fields from Metabox.io:
        // first check for required plugin
        add_action( 'tgmpa_register', array( $this, 'check_required_plugins' ) );
        // then define the fields
        add_filter( 'rwmb_meta_boxes', array( $this, 'metabox_custom_fields' ) );

        // add custom template and stylesheet
        add_action('template_include', array($this, 'add_cpt_template'));
        add_action('wp_enqueue_scripts', array($this, 'add_styles_scripts'));
    }


    /**
     * Registers the Movie Review custom post type
     *
     * Defined statically for use in activation hook
     */
    public static function register_post_type() {
        register_post_type('movie_review', array(
            'labels' => array(
                'name' => __('Movie Reviews'),
                'singular_name' => __('Movie Review'),
            ),
            'description' => __('Highly opinionated movie reviews'),
            'supports' => array(
                'title', 'editor', 'excerpt', 'author', 'revisions', 'thumbnail', 'custom-fields',
            ),
            'public' => TRUE,
            'menu_icon' => 'dashicons-format-video',
            'menu_position' => 4,
        ));
    }


    /**
     * Registers the Movie Review taxonomy
     *
     * Defined statically for use in activation hook
     */
    public static function register_taxonomies() {
        register_taxonomy('movie_types', array('movie_review'), array(
            'labels' => array(
                'name' => __('Movie Types'),
                'singular_name' => __('Movie Type'),
            ),
            'public' => TRUE,
            'hierarchical' => TRUE,
            'rewrite' => array(
                'slug' => 'movie_types',
            )
        ));
    }


    /**
     * Registers the custom widgets for this plugin
     *
     * @see JCMR_Widget_Recent_Reviews in widgets.class.php
     */
    function register_widgets(){
        register_widget('JCMR_Widget_Recent_Reviews');
    }


    /**
     * Activation hook (see register_activation_hook)
     */
    public static function activate() {
        self::register_post_type();
        self::register_taxonomies();
        flush_rewrite_rules();
    }


    /**
     * Implementation of the TGM Plugin Activation library
     *
     * Checks for the plugin(s) we need, and displays the appropriate messages
     */
    function check_required_plugins() {
        $plugins = array(
            array(
                'name'               => 'Meta Box',
                'slug'               => 'meta-box',
                'required'           => true,
                'force_activation'   => false,
                'force_deactivation' => false,
            ),
        );

        $config  = array(
            'domain'           => 'jc_movie_reviews',
            'default_path'     => '',
            'parent_slug'      => 'plugins.php',
            'capability'       => 'update_plugins',
            'menu'             => 'install-required-plugins',
            'has_notices'      => true,
            'is_automatic'     => false,
            'message'          => '',
            'strings'          => array(
                'page_title'                      => __( 'Install Required Plugins', 'jc-movie-reviews' ),
                'menu_title'                      => __( 'Install Plugins', 'jc-movie-reviews' ),
                'installing'                      => __( 'Installing Plugin: %s', 'jc-movie-reviews' ),
                'oops'                            => __( 'Something went wrong with the plugin API.', 'jc-movie-reviews' ),
                'notice_can_install_required'     => _n_noop( 'The Movie Reviews plugin depends on the following plugin: %1$s.', 'The Movie Reviews plugin depends on the following plugins: %1$s.' ),
                'notice_can_install_recommended'  => _n_noop( 'The Movie Reviews plugin recommends the following plugin: %1$s.', 'The Movie Reviews plugin recommends the following plugins: %1$s.' ),
                'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ),
                'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ),
                'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ),
                'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ),
                'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ),
                'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ),
                'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
                'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
                'return'                          => __( 'Return to Required Plugins Installer', 'jc-movie-reviews' ),
                'plugin_activated'                => __( 'Plugin activated successfully.', 'jc-movie-reviews' ),
                'complete'                        => __( 'All plugins installed and activated successfully. %s', 'jc-movie-reviews' ),
                'nag_type'                        => 'updated',
            )
        );
        tgmpa( $plugins, $config );
    }


    /**
     * Create custom fields using metabox.io
     */
    function metabox_custom_fields() {
        // define the movie custom fields
        $meta_boxes[] = array(
            'id'       => 'movie_data',
            'title'    => 'Additional Information',
            'pages'    => array( 'movie_review' ),
            'context'  => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'  => 'Release Year',
                    'desc'  => 'Year the movie was released',
                    'id'    => self::FIELD_PREFIX . 'movie_year',
                    'type'  => 'number',
                    'std'   => date('Y'),
                    'min'   => '1896',
                ),
                array(
                    'name'  => 'Director',
                    'desc'  => 'Who directed this movie',
                    'id'    => self::FIELD_PREFIX . 'movie_director',
                    'type'  => 'text',
                    'std'   => '',
                ),
                array(
                    'name'  => 'IMDB Link',
                    'desc'  => 'Link for this movie on IMDB',
                    'id'    => self::FIELD_PREFIX . 'movie_imdb',
                    'type'  => 'url',
                    'std'   => '',
                ),
            )
        );

        // define the review custom field(s)
        $meta_boxes[] = array(
            'id'       => 'review_data',
            'title'    => 'Review',
            'pages'    => array( 'movie_review' ),
            'context'  => 'side',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'    => 'Rating',
                    'desc'    => 'On a scale of 1 - 10, 10 being best',
                    'id'      => self::FIELD_PREFIX . 'review_rating',
                    'type'    => 'select',
                    'options' => array(
                        '' => __('TBR (To be rated)'),
                        1  => __('1 - I walked out. And I was home!'),
                        2  => __('2 - I will never get those hours back'),
                        3  => __('3 - Not recommended'),
                        4  => __('4 - Might stay awake on an airplane for it'),
                        5  => __('5 - As they say on the internet: meh'),
                        6  => __('6 - Totally decent'),
                        7  => __('7 - Quite good. Recommended.'),
                        8  => __('8 - One of my favorites of the last X years'),
                        9  => __('9 - Loved it, and you probably will too.'),
                        10 => __("10 - Life changing! Mine, yours, everyone's!"),
                    ),
                    'std' => '',
                ),
            )
        );

        return $meta_boxes;
    }


    /**
     * Implementation of template_include to add a custom template
     * @param $template
     *
     * @return string
     */
    function add_cpt_template($template) {
        if (is_singular(self::CPT_SLUG)) {
            // check the active theme directory
            if (file_exists(get_stylesheet_directory() . '/single-' . self::CPT_SLUG . '.php')) {
                return get_stylesheet_directory() . '/single-' . self::CPT_SLUG . '.php';
            }

            // failing that, use the bundled copy
            return plugin_dir_path(__FILE__) . 'single-' . self::CPT_SLUG . '.php';
        }

        // always make sure if we returned this in the end
        return $template;
    }

    function add_styles_scripts() {
        wp_enqueue_style('movie-review-style', plugin_dir_url(__FILE__) . 'movie-reviews.css');
    }
}

Movie_Reviews::getInstance();

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'Movie_Reviews::activate' );
