<?php
namespace Strata\Model\CustomPostType\Registrar;

use \Strata\Strata;
use Strata\Model\CustomPostType\Registrar\Registrar;

class CustomPostTypeRegistrar extends Registrar
{
    function register()
    {
        // Ensure the default options have been set.
        $customizedOptions = $this->_entity->configuration + array(
            'labels'              => array(),
            'supports'            => array( 'title' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => false,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'rewrite'             => null,
            'capability_type'     => 'post',
        );

        $singular   = $this->_labelParser->singular();
        $plural     = $this->_labelParser->plural();
        $projectKey = Strata::getNamespace();

        $customizedOptions['labels'] += array(
            'name'                => _x( $plural, 'Post Type General Name', $projectKey ),
            'singular_name'       => _x( $singular, 'Post Type Singular Name', $projectKey ),
            'menu_name'           => __( $plural, $projectKey ),
            'parent_item_colon'   => __( $singular. ' Item:', $projectKey ),
            'all_items'           => __( 'All ' . $plural, $projectKey ),
            'view_item'           => __( 'View ' . $singular. ' Item', $projectKey ),
            'add_new_item'        => __( 'Add New', $projectKey ),
            'add_new'             => __( 'Add New', $projectKey ),
            'edit_item'           => __( 'Edit ' . $singular, $projectKey ),
            'update_item'         => __( 'Update ' . $singular, $projectKey ),
            'search_items'        => __( 'Search ' . $plural, $projectKey ),
            'not_found'           => __( 'Not found', $projectKey ),
            'not_found_in_trash'  => __( 'Not found in Trash', $projectKey ),
        );

        return register_post_type($this->_wordpressKey, $customizedOptions);
    }
}
