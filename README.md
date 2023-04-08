# My Custom Plugin Boilerplate

The project was created for learning purposes and as a helper utility file for developing WordPress plugins.

## Background

The code was inspired by an online course on Udemy.

## Getting Started

To use the code, simply [download the repository](https://github.com/your-username/your-repo). 
<!-- and follow the instructions in the [documentation](https://github.com/your-username/your-repo/docs). -->
<!--
<details>
  <summary>Click to expand</summary>

This text will be hidden by default, but can be revealed by clicking on the summary.
</details>

-->

### Description
This is a PHP code for a WordPress plugin that creates a custom post type for movie reviews, custom taxonomies for movie types, custom fields using Metabox.io, custom widgets, a custom template, and a stylesheet for the custom post type.

The code creates a Singleton class called Movie_Reviews and initializes all the necessary functions in its constructor. It also defines several constants for use throughout the code, such as FIELD_PREFIX, CPT_SLUG, and TEXT_DOMAIN.

The register_post_type function registers a new post type called "movie_review" with the necessary parameters for the post type.

The register_taxonomies function registers a new taxonomy called "movie_types" for the "movie_review" post type with the necessary parameters.

The register_widgets function registers a custom widget called "JCMR_Widget_Recent_Reviews" for the plugin.

The admin_menu function creates a new submenu page for the "movie_review" post type called "Movie Review Options" and sets it as accessible to users with "manage_options" capability.

The create_settings function registers a new setting for the plugin and creates a new section with a field for a rating option.

The setting_rating_options function creates the HTML for the rating option field.

The options_page function creates the HTML for the options page for the plugin.

Finally, the activate function registers the "movie_review" post type and the "movie_types" taxonomy and flushes the rewrite rules to ensure the new post type and taxonomy are registered correctly.

## Issues
* At the moment custom input isn't displayed in 'Movie Reviews Options' 


## License

This project is licensed under the [MIT License](LICENSE). See the LICENSE file for details.

## Acknowledgments

* [Udemy](https://www.udemy.com/share/101OMx3@HvIpa-yyKJWH-L-mEikUj7JeqrHk_mMOMd2uUK0VVBMpbKtwip_PW8dwN5BnbqN9/ for inspiring the project.


