<?php
class KodaTheme
{
    protected static $_instance;
    protected $config = [];

    public function __construct()
    {
        $this->load_config();
        $this->add_acf_option_pages();
        $this->load_typekit_tinymce_plugin();

        add_action('after_switch_theme', [$this, 'after_switch_theme']);
        add_action('after_setup_theme', [$this, 'after_setup_theme']);
        add_action('init', [$this, 'init'], 0);
        add_action('wp_enqueue_scripts', [$this, 'register_stylesheets_scripts'], 15);
        add_action('login', [$this, 'admin_logo']);


        $this->load_tinymce_additional_styles();
    }

    public static function load_instance()
    {
        if(null !== static::$_instance){
            return static::$_instance;
        }
        static::$_instance = new static();
        return static::$_instance;
    }

    public function config($key, $default = null)
    {
        return isset($this->config[$key])? $this->config[$key] : $default;
    }

    protected function load_config()
    {
        $config_file = get_stylesheet_directory() . '/.theme-config.php';

        $this->config = require($config_file);
    }

    public function after_setup_theme()
    {
        $this->enable_theme_support();
        $this->register_menu();
        $this->custom_thumbnail_size();

    }

    protected function enable_theme_support()
    {
        add_theme_support('post-thumbnails'); // add post thumbnail

    }

    public function after_switch_theme()
    {
        update_option('rg_gforms_disable_css', '1');
        update_option('rg_gforms_enable_html5', '1');
    }

    protected function load_tinymce_additional_styles()
    {
        add_filter('mce_buttons_2', [$this, 'wpb_mce_buttons_2'], 20);
        add_filter('teeny_mce_buttons', [$this, 'wpb_mce_buttons_2'], 20);
        add_filter('tiny_mce_before_init', [$this, 'tinycme_custom_format_styles'], 20);
    }
    
    protected function load_typekit_tinymce_plugin() {
        if (! $this->config('typekit_id')) {
            return false;
        }

        add_filter('mce_external_plugins', [$this, 'typekit_tinymce_plugin']);
        add_filter('tiny_mce_before_init', [$this, 'typekit_tinymce_config']);
    }

    protected function register_menu()
    {
        register_nav_menus($this->config('menu_settings')? : []);
    }


    protected function custom_thumbnail_size()
    {
        foreach($this->config('custom_thumbnail_sizes') as $name => $sizes) {
            add_image_size($name, $sizes[0], $sizes[1], $sizes[2]);
        }
    }

    public function init()
    {
        $this->register_post_types();
        $this->register_taxonomies();
    }

    public function register_stylesheets_scripts()
    {
        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js');

        foreach($this->config('custom_editor_styles')? : [] as $name => $stylesheet) {
            $externalLink = strpos($stylesheet, '//') === 0 || strpos($stylesheet, 'http') === 0;

            wp_enqueue_style(
                $name,
                $externalLink? $stylesheet : trailingslashit(get_stylesheet_directory_uri()) . $stylesheet,
                [],
                $externalLink? false : filemtime(trailingslashit(get_stylesheet_directory()) . $stylesheet)
            );
        }

        foreach($this->config('custom_editor_scripts')? : [] as $name => $javascript) {
            $externalLink = strpos($javascript, '//') === 0 || strpos($javascript, 'http') === 0;

            wp_enqueue_script(
                $name,
                $externalLink? $javascript : trailingslashit(get_stylesheet_directory_uri()) . $javascript,
                [],
                $externalLink? false : filemtime(get_stylesheet_directory() . $javascript),
                true
            );
        }
    }

    protected function register_post_types()
    {
        foreach ($this->config('custom_post_type') as $slug => $cpt) {
            // Register Custom Post Type
            $labels = [
                'name'                  => __($cpt['plural_name'], $this->config('theme_slug')),
                'singular_name'         => __($cpt['singular_name'], $this->config('theme_slug')),
                'add_new'               => __('Add New', $this->config('theme_slug')),
                'add_new_item'          => __('Add New ' . $cpt['singular_name'], $this->config('theme_slug')),
                'edit_item'             => __('Edit ' . $cpt['singular_name'], $this->config('theme_slug')),
                'new_item'              => __('New ' . $cpt['singular_name'], $this->config('theme_slug')),
                'view_item'             => __('View ' . $cpt['singular_name'], $this->config('theme_slug')),
                'search_items'          => __('Search ' . $cpt['singular_name'], $this->config('theme_slug')),
                'not_found'             => __('Nothing found in the Database.', $this->config('theme_slug')),
                'not_found_in_trash'    => __('Nothing found in Trash', $this->config('theme_slug')),
                'parent_item_colon'     => __('Parent ' . $cpt['singular_name'] . ':', $this->config('theme_slug')),

                'all_items'             => __('All ' . $cpt['plural_name'], $this->config('theme_slug')),
                'archives'              => __($cpt['singular_name'] . ' Archives', $this->config('theme_slug')),
                'insert_into_item'      => __('Insert into ' . $cpt['singular_name'], $this->config('theme_slug')),
                'uploaded_to_this_item' => __('Uploaded to this ' . $cpt['singular_name'], $this->config('theme_slug')),

                'filter_items_list'     => __('Filter ' . $cpt['plural_name'] . ' list', $this->config('theme_slug')),
                'items_list_navigation' => __($cpt['plural_name'] . ' list navigation', $this->config('theme_slug')),
                'items_list'            => __($cpt['plural_name'] . ' list', $this->config('theme_slug')),
            ];

            $args = wp_parse_args($cpt, [
                'labels' => $labels,
                'public' => true,
                'supports'      => ['title', 'editor', 'thumbnail', 'revisions', 'excerpt'],
                'rewrite'       => [
                    'slug' => $cpt['url_slug']? : '',
                    'with_front' => false
                ]
            ]);


            register_post_type($slug, $args);
        }
    }

    protected function register_taxonomies() {
        foreach ($this->config('custom_taxonomy') as $slug => $tax) {
            // Register Custom Taxonomy
            $labels = array(
                'name'                       => _x( $tax['plural_name'], $this->config('theme_slug')),
                'singular_name'              => _x( $tax['singular_name'], $this->config('theme_slug')),
                'menu_name'                  => __( $tax['singular_name'], $this->config('theme_slug')),
                'all_items'                  => __( 'All Items', $this->config('theme_slug')),
                'parent_item'                => __( 'Parent Item', $this->config('theme_slug')),
                'parent_item_colon'          => __( 'Parent Item:', $this->config('theme_slug')),
                'new_item_name'              => __( 'New Item Name', $this->config('theme_slug')),
                'add_new_item'               => __( 'Add New Item', $this->config('theme_slug')),
                'edit_item'                  => __( 'Edit Item', $this->config('theme_slug')),
                'update_item'                => __( 'Update Item', $this->config('theme_slug')),
                'view_item'                  => __( 'View Item', $this->config('theme_slug')),
                'separate_items_with_commas' => __( 'Separate items with commas', $this->config('theme_slug')),
                'add_or_remove_items'        => __( 'Add or remove items', $this->config('theme_slug')),
                'choose_from_most_used'      => __( 'Choose from the most used', $this->config('theme_slug')),
                'popular_items'              => __( 'Popular Items', $this->config('theme_slug')),
                'search_items'               => __( 'Search Items', $this->config('theme_slug')),
                'not_found'                  => __( 'Not Found', $this->config('theme_slug')),
                'no_terms'                   => __( 'No items', $this->config('theme_slug')),
                'items_list'                 => __( 'Items list', $this->config('theme_slug')),
                'items_list_navigation'      => __( 'Items list navigation', $this->config('theme_slug')),
            );

            $args = wp_parse_args($tax, [
                'labels' => $labels,
                'public' => true,
                'hierarchical'      => true,
                'show_admin_column' => true,
                'rewrite'           => [
                    'slug' => $tax['url_slug'],
                    'with_front' => false
                ],
            ]);

            register_taxonomy($slug, $tax['post_type'], $args);
        }
    }

    public function tinycme_custom_format_styles($init_array) {
        $init_array['style_formats'] = json_encode($this->config('tinycme_custom_format_styles'));
        return $init_array;
    }

    public function wpb_mce_buttons_2($buttons) {
        array_unshift($buttons, 'styleselect');
        return $buttons;
    }

    public function typekit_tinymce_plugin($plugins)
    {
        $plugins['fs_typekit'] = get_stylesheet_directory_uri() . '/scripts/tinymce.fs_typekit.js';
        return $plugins;
    }

    public function typekit_tinymce_config($mceInit)
    {
        $mceInit['fs_typekit_id'] = $this->config('typekit_id');
        return $mceInit;
    }

    protected function add_acf_option_pages()
    {
        if (! $this->config('acf_option_pages')) {
            return;
        }

        if (! function_exists('acf_add_options_page')) {
            return;
        }

        acf_add_options_page();

        foreach ($this->config('acf_option_pages') as $pageName) {
            acf_add_options_sub_page($pageName);
        }
    }

    public function admin_logo() {
        if(! $this->config('admin_logo')) {
            return false;
        }

        printf(
            '<style type="text/css">
                h1 a{background:0 0!important;height:auto!important;width:100%%!important;text-indent:0!important}
                h1 a img{max-width:240px;width:auto}
            </style>
            <script type="text/javascript">
                window.onload = functions(){
                    var aTag = document.getElementById("login").getElementsByTagName("a")[0];
                    aTag.innerHTML = \' < img src = "%s" alt = "%s" > \';
                    aTag.href = "%s";
                    aTag.title = "Go to site";
                }
            </script>',
            trailingslashit(get_stylesheet_directory_uri()) . $this->config('admin_logo'),
            get_bloginfo('name'),
            home_url()
        );
    }

}

new KodaTheme();
