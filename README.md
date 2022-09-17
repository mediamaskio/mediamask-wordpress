# Mediamask Wordpress Plugin

This plugin allows you to automatically generate open graph images for posts, pages etc. via mediamask.io
Mediamask allows rendering of custom images via predefined templates via APIs and integrations. 

This plugin makes it easy to setup automatically generated custom open graph images, no coding required!

## Add API Key

1. Register an account at [mediamask.io](https://mediamask.io/) and confirm your e-mail address.
2. Head to the [API Keys Area](https://mediamask.io/team/api-tokens) and create a new API Key for your Wordpress instance. 
3. Copy the API Key, paste and save it into the "API Token" Section of the Mediamask Plugin Settings

## Configure Template

1. To configure a template in the plugin you need to add it on mediamask first. 
You can either choose one of the existing templates or create a new one from scratch.
   Make sure the Template has "REST API" and "Signed URL API" enabled when you create it (those are the default options, so don't worry).
2. After creating the template, you can select it in the mediamask plugin. 
Every Dynamic Layer (texts and images) can be configured to be filled with Wordpress Attributes like the_title(), the_excerpt(), the_author() etc.  

## Attribute Mapping

* Title 
  * for posts/pages: The title of your post/page, get_the_title()
  * for terms/categories: The name of the term/category
  * for taxonomies: The taxonomy label
  * for users: The users display name
* Excerpt
  * for posts/pages: The excerpt of the title/post, get_the_excerpt()
  * for terms/categories: The description of the term/category
  * for taxonomies: The description of the taxonomy
  * for users: The biography of the user
* Published Date 
  * for all: get_the_date()
* Author Name 
  * for all: get_the_author()
* Permalink 
  * for all: get_the_permalink()