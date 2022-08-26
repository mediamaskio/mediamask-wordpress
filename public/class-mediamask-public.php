<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mediamask
 * @subpackage Mediamask/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mediamask
 * @subpackage Mediamask/public
 * @author     Your Name <email@example.com>
 */
class Mediamask_Public
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
     * @param string $mediamask The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($mediamask, $version)
    {

        $this->mediamask = $mediamask;
        $this->version = $version;

    }

    public function add_og_image()
    {

        $config = Mediamask\Configuration::getDefaultConfiguration()->setAccessToken(get_option('mediamask_api_key'));

        $apiInstance = new Mediamask\Api\MediamaskApi(
        // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
        // This is optional, `GuzzleHttp\Client` will be used as default.
            new GuzzleHttp\Client(),
            $config
        );

        $baseConfig = get_option('mediamask_base_configuration');
        $templateId = $baseConfig['template'];

        $configParameters = array_map(function ($configParameter){
            if($configParameter === 'title'){
                return get_the_title();
            }
            else if($configParameter === 'description'){
                return get_the_excerpt();
            }
            else if($configParameter === 'publish_date'){
                return get_the_date();
            }
            else if($configParameter === 'author_name'){
                return get_the_author();
            }
            else if($configParameter === 'permalink'){
                return get_the_permalink();
            }
            else if($configParameter === 'permalink'){
                return get_the_permalink();
            }
            else if($configParameter === 'post_thumbnail'){
                return get_the_post_thumbnail_url(null, 'full');
            }
            else if($configParameter === 'author_image'){
                return get_avatar_url(get_the_author_meta('ID'));
            }
            return $configParameter;
        }, $baseConfig['values']);

        $ogImageUrl = $apiInstance->getSignedUrl($templateId, $configParameters);

        echo '<meta property="og:image" content="' . esc_url($ogImageUrl) . '"><meta name="twitter:card" content="summary_large_image"><meta name="twitter:image" content="' . esc_url($ogImageUrl) . '">';
    }


    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style($this->mediamask, plugin_dir_url(__FILE__) . 'css/mediamask-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script($this->mediamask, plugin_dir_url(__FILE__) . 'js/mediamask-public.js', array('jquery'), $this->version, false);

    }

}
