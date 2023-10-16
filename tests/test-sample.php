<?php
/**
 * Class SampleTest
 *
 * @package Sample_Plugin
 */

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase
{

    /**
     * A single example test.
     */
    function testThatPostHeadWithoutConfigDoesNotChange()
    {
        // Arrange
        $my_post = array(
            'post_title' => 'Awesome Testpage',
            'post_content' => 'Some fancy content',
            'post_status' => 'publish',
        );
        // Need to initialize twice because wordpress is doing some weird stuff
        $postId = wp_insert_post($my_post);
        global $post;
        $post = get_post($postId);
        ob_start();
        wp_head();
        $originalHeader = ob_get_clean();

        global $post;
        $post = get_post($postId);
        ob_start();
        wp_head();
        $originalHeader = ob_get_clean();
        activate_plugin('mediamask');
        update_option('mediamask_api_key', 'ABC');

        // Act
        global $post;
        $post = get_post($postId);
        ob_start();
        wp_head();
        $newHeader = ob_get_clean();

        // Assert
        \PHPUnit\Framework\assertEquals($newHeader, $originalHeader);
    }


    function testThatBaseConfigWorks()
    {
        // Arrange
        activate_plugin('mediamask');
        update_option('mediamask_api_key', 'ABC');
        update_option('mediamask_base_configuration',
            [
                "mediamask_template_id" => "ef997dab-a1a9-4743-af5f-566e78c267cc",
                "dynamic_layers" => [
                    "title" => "title"
                ]
            ]);
        $my_post = array(
            'post_title' => 'Awesome Testpage',
            'post_content' => 'Some fancy content',
            'post_status' => 'publish',
        );
        $postId = wp_insert_post($my_post);

        // Act
        global $post;
        $post = get_post($postId);
        ob_start();
        wp_head();
        $output = ob_get_clean();

        // Assert
        \PHPUnit\Framework\assertStringContainsString('<meta property="og:image" content="https://mediamask.io/image/ef997dab-a1a9-4743-af5f-566e78c267cc?title=Awesome%20Testpage&#038;signature=55f9cca5ea086c83d5f5a91feee91d60f38c2d75a0ad3b9f2628f30d379b20ea">',
            $output);
        \PHPUnit\Framework\assertStringContainsString('<meta name="twitter:card" content="summary_large_image"><meta name="twitter:image" content="https://mediamask.io/image/ef997dab-a1a9-4743-af5f-566e78c267cc?title=Awesome%20Testpage&#038;signature=55f9cca5ea086c83d5f5a91feee91d60f38c2d75a0ad3b9f2628f30d379b20ea">',
            $output);
    }



    function testThatBaseConfigOnlyForTwitterWorks()
    {
        // Arrange
        activate_plugin('mediamask');
        update_option('mediamask_api_key', 'ABC');
        update_option('mediamask_base_configuration',
            [
                "mediamask_template_id" => "ef997dab-a1a9-4743-af5f-566e78c267cc",
                "dynamic_layers" => [
                    "title" => "title"
                ],
                'only_twitter' => true
            ]);
        $my_post = array(
            'post_title' => 'Awesome Testpage',
            'post_content' => 'Some fancy content',
            'post_status' => 'publish',
        );
        $postId = wp_insert_post($my_post);

        // Act
        global $post;
        $post = get_post($postId);
        ob_start();
        wp_head();
        $output = ob_get_clean();

        // Assert
        \PHPUnit\Framework\assertStringNotContainsString('<meta property="og:image" content="https://mediamask.io/image/ef997dab-a1a9-4743-af5f-566e78c267cc?title=Awesome%20Testpage&#038;signature=55f9cca5ea086c83d5f5a91feee91d60f38c2d75a0ad3b9f2628f30d379b20ea">',
            $output);
        \PHPUnit\Framework\assertStringContainsString('<meta name="twitter:card" content="summary_large_image"><meta name="twitter:image" content="https://mediamask.io/image/ef997dab-a1a9-4743-af5f-566e78c267cc?title=Awesome%20Testpage&#038;signature=55f9cca5ea086c83d5f5a91feee91d60f38c2d75a0ad3b9f2628f30d379b20ea">',
            $output);
    }

    function testThatCustomSpecificConfigWorks()
    {

        file_put_contents(get_template_directory_uri() . '/page-blank.php', '<?php /* Template Name: Blank Template */ ?>');
        // Arrange
        activate_plugin('mediamask');
        update_option('mediamask_api_key', 'ABC');
        update_option('mediamask_base_configuration',
            [
                "mediamask_template_id" => "ef997dab-a1a9-4743-af5f-566e78c267cc",
                "values" => [
                    "title" => "title"
                ]
            ]);
        update_option('mediamask_custom_configuration',
            [
                [
                    'post_type' => 'page',
                    'template' => 'page-blank.php',
                    'mediamask_template_id' => "734ffb36-8e02-46e8-a62e-150f7cf9408d",
                    "dynamic_layers" => [
                        "title" => "description"
                    ]
                ]
            ]);
        $my_post = array(
            'post_title' => 'Awesome Testpage',
            'post_content' => 'Some fancy content',
            'post_status' => 'publish',
            'post_type' => 'page',
            'page_template' => 'page-blank.php'
        );
        $postId = wp_insert_post($my_post);

        // Act
        global $post;
        global $wp_query;
        $post = get_post($postId);
        $wp_query->queried_object = $post;
        ob_start();
        wp_head();
        $output = ob_get_clean();

        // Assert
        \PHPUnit\Framework\assertStringContainsString('<meta property="og:image" content="https://mediamask.io/image/734ffb36-8e02-46e8-a62e-150f7cf9408d?title=Some%20fancy%20content&#038;signature=f783227820f9c2cb3aee817fb5f9530f44229574805f2068614fafff654c8ee0">',
            $output);
        \PHPUnit\Framework\assertStringContainsString('<meta name="twitter:card" content="summary_large_image"><meta name="twitter:image" content="https://mediamask.io/image/734ffb36-8e02-46e8-a62e-150f7cf9408d?title=Some%20fancy%20content&#038;signature=f783227820f9c2cb3aee817fb5f9530f44229574805f2068614fafff654c8ee0">',
            $output);
    }


    function testThatCustomDefaultConfigWorks()
    {
        // Arrange
        file_put_contents(get_template_directory_uri() . '/page-blank.php', '<?php /* Template Name: Blank Template */ ?>');
        activate_plugin('mediamask');
        update_option('mediamask_api_key', 'ABC');
        update_option('mediamask_base_configuration',
            [
                "mediamask_template_id" => "ef997dab-a1a9-4743-af5f-566e78c267cc",
                "values" => [
                    "title" => "title"
                ]
            ]);
        update_option('mediamask_custom_configuration',
            [
                [
                    'post_type' => 'page',
                    'template' => 'page-blank.php',
                    'mediamask_template_id' => "734ffb36-8e02-46e8-a62e-150f7cf9408d",
                    "dynamic_layers" => [
                        "title" => "title"
                    ]
                ],
                [
                    'post_type' => 'page',
                    'template' => 'default',
                    'mediamask_template_id' => "a2623aec-b2ef-47f1-b74c-4875befcc5cc",
                    "dynamic_layers" => [
                        "title" => "description"
                    ]
                ]]);
        $my_post = array(
            'post_title' => 'Awesome Testpage',
            'post_content' => 'Some fancy content',
            'post_status' => 'publish',
            'post_type' => 'page',
        );
        $postId = wp_insert_post($my_post);

        // Act
        global $post;
        global $wp_query;
        $post = get_post($postId);
        $wp_query->queried_object = $post;
        ob_start();
        wp_head();
        $output = ob_get_clean();

        // Assert
        \PHPUnit\Framework\assertStringContainsString('<meta property="og:image" content="https://mediamask.io/image/a2623aec-b2ef-47f1-b74c-4875befcc5cc?title=Some%20fancy%20content&#038;signature=3a96d38082986a862a703fbc08226122c520a0fa3856f2e50b4f71792b39843f">',
            $output);
        \PHPUnit\Framework\assertStringContainsString('<meta name="twitter:card" content="summary_large_image"><meta name="twitter:image" content="https://mediamask.io/image/a2623aec-b2ef-47f1-b74c-4875befcc5cc?title=Some%20fancy%20content&#038;signature=3a96d38082986a862a703fbc08226122c520a0fa3856f2e50b4f71792b39843f">',
            $output);
    }
}
