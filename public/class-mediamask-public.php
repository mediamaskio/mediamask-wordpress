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

    public function double_encoded_nl_to_encoded_nl($url)
    {
        return str_ireplace('%250A', '%0A', $url);
    }

    public function add_og_image()
    {
        $configParameters = null;
        $ogImageUrl = null;
        $templateId = null;
        // Check if custom rule applies

//        $this->getSpecificCustomConfigIfExists
        $customConfigs = get_option('mediamask_custom_configuration', []);
        $currentObject = get_queried_object();

        $matchingSpecificConfigs = array_filter($customConfigs, function ($customConfig) use ($currentObject) {
            if ($currentObject instanceof WP_Post) {
                return $customConfig['post_type'] === $currentObject->post_type && $customConfig['template'] === get_page_template_slug();
            }
            if ($currentObject instanceof WP_Post_Type) {
                return $customConfig['post_type'] === $currentObject->name && $customConfig['template'] === 'archive';
            }
            if ($currentObject instanceof WP_Term) {
                return $customConfig['post_type'] === $currentObject->taxonomy && $customConfig['template'] === get_page_template_slug();
            }
            if ($currentObject instanceof WP_User) {
                return $customConfig['post_type'] === 'user';
            }
            return false;
        });

        $matchingDefaultCustomConfigs = array_filter($customConfigs, function ($customConfig) use ($currentObject) {
            if ($currentObject instanceof WP_Post) {
                return $customConfig['post_type'] === $currentObject->post_type && $customConfig['template'] === 'default';
            }
            if ($currentObject instanceof WP_Post_Type) {
                return $customConfig['post_type'] === $currentObject->name && $customConfig['template'] === 'default';
            }
            if ($currentObject instanceof WP_Term) {
                return $customConfig['post_type'] === $currentObject->taxonomy && $customConfig['template'] === 'default';
            }
            if($currentObject instanceof WP_User){
                return $customConfig['post_type'] === 'user';
            }
            return false;
        });
        // check if default config for post type exists

        if (count($matchingSpecificConfigs) > 0) {
            $this->renderOgImages(reset($matchingSpecificConfigs)['mediamask_template_id'], reset($matchingSpecificConfigs)['dynamic_layers']);
        } else if (count($matchingDefaultCustomConfigs) > 0) {
            $this->renderOgImages(reset($matchingDefaultCustomConfigs)['mediamask_template_id'], reset($matchingDefaultCustomConfigs)['dynamic_layers']);
        } else {
            if ($baseConfig = get_option('mediamask_base_configuration')) {
                if(array_key_exists('only_twitter', $baseConfig) && $baseConfig['only_twitter'] === true){
                    $this->renderOnlyTwitterOgImage($baseConfig['mediamask_template_id'], $baseConfig['dynamic_layers']);
                }
                else {
                    $this->renderOgImages($baseConfig['mediamask_template_id'], $baseConfig['dynamic_layers']);
                }
            }
        }
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

    private function mapConfigParametersToPostValues($configValues)
    {
        $currentObject = get_queried_object();
        return array_map(function ($configParameter) use ($currentObject) {
            if ($configParameter === 'title') {
                if (is_archive()) {
                    if ($currentObject instanceof WP_Term) {
                        return $currentObject->name;
                    } else if ($currentObject instanceof WP_Post_Type) {
                        return get_post_type_object(get_post_type())->label;
                    }
                    else if ($currentObject instanceof WP_USER) {
                        return get_userdata($currentObject->ID)->display_name;
                    }
                }
                return get_the_title();
            } else if ($configParameter === 'description') {
                if (is_archive()) {
                    if ($currentObject instanceof WP_Term) {
                        return get_term($currentObject->term_id)->description;
                    } else if ($currentObject instanceof WP_Post_Type) {
                        return get_post_type_object(get_post_type())->description;
                    }
                    else if ($currentObject instanceof WP_USER) {
                        $descriptions = get_user_meta( $currentObject->data->ID )['description'];
                        if(count($descriptions) > 0){
                            return $descriptions[0];
                        }
                    }
                }
                return get_the_excerpt();
            } else if ($configParameter === 'publish_date') {
                return get_the_date();
            } else if ($configParameter === 'author_name') {
                if($currentObject instanceof WP_Post){
                    return get_the_author_meta('display_name', $currentObject->post_author);
                }
                return get_the_author();
            } else if ($configParameter === 'permalink') {
                return get_the_permalink();
            } else if ($configParameter === 'post_thumbnail') {
                return get_the_post_thumbnail_url(null, 'full');
            } else if ($configParameter === 'author_image') {
                if($currentObject instanceof WP_Post){
                    return get_avatar_url($currentObject->post_author);
                }
                return get_avatar_url(get_the_author_meta('ID'));
            }
            return $configParameter;
        }, $configValues);
    }

    private function renderOgImages($templateId, $configParameters)
    {
        $ogImageUrl = $this->getSignedUrl($templateId, $configParameters);

        // wordpress requires output to be escaped. To allow new lines in the URL, the Character %0A will be replaces before the escaping
        // it gets reverted back in the clean_url filter
        $ogImageUrl = str_ireplace('%0A', '%250A', $ogImageUrl);
        echo '<meta property="og:image" content="' . esc_url_raw($ogImageUrl) . '"><meta name="twitter:card" content="summary_large_image"><meta name="twitter:image" content="' . esc_url($ogImageUrl) . '">';
    }

    private function getSignedUrl($templateId, $configParameters){
        $config = \Mediamask\Configuration::getDefaultConfiguration()->setAccessToken(get_option('mediamask_api_key'));

        $apiInstance = new \Mediamask\Api\MediamaskApi(
            new GuzzleHttp\Client(),
            $config
        );

        return $apiInstance->getSignedUrl($templateId, $this->mapConfigParametersToPostValues($configParameters));
    }

    private function renderOnlyTwitterOgImage($templateId, $configParameters)
    {
        $ogImageUrl = $this->getSignedUrl($templateId, $configParameters);

        // wordpress requires output to be escaped. To allow new lines in the URL, the Character %0A will be replaces before the escaping
        // it gets reverted back in the clean_url filter
        $ogImageUrl = str_ireplace('%0A', '%250A', $ogImageUrl);
        echo '<meta name="twitter:card" content="summary_large_image"><meta name="twitter:image" content="' . esc_url($ogImageUrl) . '">';
    }

}
