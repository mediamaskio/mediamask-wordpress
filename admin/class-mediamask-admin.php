<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mediamask
 * @subpackage Mediamask/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mediamask
 * @subpackage Mediamask/admin
 * @author     Your Name <email@example.com>
 */
class Mediamask_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $mediamask The ID of this plugin.
     */
    private $mediamask;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $mediamask The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($mediamask, $version)
    {

        $this->mediamask = $mediamask;
        $this->version = $version;

    }

    public function add_admin_page()
    {

        // add top level menu page
        add_menu_page(
            'Mediamask', //Page Title
            'Mediamask', //Menu Title
            'manage_options', //Capability
            'mediamask', //Page slug
            function () {
                echo '<div id="mediamask-wrapper">Loading...</div>';
            }
        );
    }

    public function update_api_key(WP_REST_Request $request)
    {
        update_option('mediamask_api_key', $request->get_json_params()['api_token']);

        return [
            'api_token' => get_option('mediamask_api_key', null)
        ];
    }

    public function get_api_key()
    {
        return [
            'api_token' => get_option('mediamask_api_key', null)
        ];
    }

    public function get_templates()
    {
        $config = Mediamask\Configuration::getDefaultConfiguration()->setAccessToken(get_option('mediamask_api_key'));

        $apiInstance = new Mediamask\Api\MediamaskApi(
            new GuzzleHttp\Client(),
            $config
        );

        return $apiInstance->templates();
    }

    public function save_base_configuration($request){
        update_option('mediamask_base_configuration', $request->get_json_params());

        return [
            'base_configuration' => get_option('mediamask_base_configuration', null)
        ];
    }

    public function get_base_configuration(){
        return [
            'base_configuration' => get_option('mediamask_base_configuration', null)
        ];
    }
    public function save_custom_configuration($request){
        update_option('mediamask_custom_configuration', $request->get_json_params());

        return [
            'custom_configuration' => get_option('mediamask_custom_configuration', [])
        ];
    }

    public function get_custom_configuration(){
        return [
            'custom_configuration' => get_option('mediamask_custom_configuration', [])
        ];
    }


    public function reset_settings(){
        delete_option('mediamask_custom_configuration');
        delete_option('mediamask_base_configuration');
        delete_option('mediamask_api_key');
    }

    public function add_rest_api_init()
    {
        register_rest_route('mediamask/v1', '/api-key', array(
            'methods' => 'POST',
            'callback' => [$this, 'update_api_key'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ));
        register_rest_route('mediamask/v1', '/reset-settings', array(
            'methods' => 'POST',
            'callback' => [$this, 'reset_settings'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ));

        register_rest_route('mediamask/v1', '/api-key', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_api_key'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ));

        register_rest_route('mediamask/v1', '/templates', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_templates'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ));

        register_rest_route('mediamask/v1', '/base-configuration', array(
            'methods' => 'POST',
            'callback' => [$this, 'save_base_configuration'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ));

        register_rest_route('mediamask/v1', '/base-configuration', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_base_configuration'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ));

        register_rest_route('mediamask/v1', '/custom-configuration', array(
            'methods' => 'POST',
            'callback' => [$this, 'save_custom_configuration'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ));

        register_rest_route('mediamask/v1', '/custom-configuration', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_custom_configuration'],
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ));


    }

    public function enqueue_vite()
    {
        if(get_current_screen()->base === 'toplevel_page_mediamask'){
            $manifest = json_decode(file_get_contents(plugin_dir_path(__FILE__) . '../frontend/dist/manifest.json'), true);

            wp_enqueue_script($this->mediamask . '-vite-scripts', plugin_dir_url(__FILE__) . '../frontend/dist/' . $manifest['../src/main.ts']['file'], [], $this->version, false);

            wp_enqueue_style($this->mediamask . '-vite-style', plugin_dir_url(__FILE__) . '../frontend/dist/' . $manifest['../src/main.ts']['css'][0], [], $this->version, false);

            $builtInPostTypes = ["attachment","revision","nav_menu_item","custom_css","customize_changeset","oembed_cache","user_request","wp_block","wp_template","wp_template_part","wp_global_styles","wp_navigation"];
            $postTypes = array_filter(array_keys(get_post_types()), function ($postType) use ($builtInPostTypes){
                return !in_array($postType, $builtInPostTypes);
            });
            $taxonomies = array_keys(get_taxonomies(['public'   => true,], 'names', 'and'));

            $postTypesAndTaxonomies = array_merge($postTypes, $taxonomies);

            $postTypesAndTaxonomies = array_map(function ($tag){
                return [
                    'id' => $tag,
                    'templates' => array_keys(array_merge(array_flip(get_page_templates(null, $tag)), array_flip(['default', 'archive'])))];
            }, $postTypesAndTaxonomies);

            $postTypesAndTaxonomies[] = ['id' => 'user', 'templates' => ['default']];


            wp_localize_script($this->mediamask . '-vite-scripts', 'wpApiSettings', array(
                'root' => esc_url_raw(rest_url()),
                'nonce' => wp_create_nonce('wp_rest'),
                'post_types_and_taxonomies' => $postTypesAndTaxonomies,
            ));
        }
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mediamask_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mediamask_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->mediamask, plugin_dir_url(__FILE__) . 'css/mediamask-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mediamask_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mediamask_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->mediamask, plugin_dir_url(__FILE__) . 'js/mediamask-admin.js', array('jquery'), $this->version, false);

    }

}
