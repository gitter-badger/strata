<?php
namespace Strata\Model\CustomPostType\Registrar;

use \Strata\Strata;
use Strata\Model\CustomPostType\Registrar\Registrar;

class TaxonomyRegistrar extends Registrar
{
    public function register()
    {
        $status = true;
        if ($this->_hasTaxonomyConfiguration()) {
            foreach ($this->_getTaxonomyConfiguration() as $taxonomy) {
                $status = $status && $this->_registerTaxonomy($taxonomy);
            }
        }
        return $status;
    }

    private function _registerTaxonomy($taxonomyClassname)
    {
        $singular   = $this->_labelParser->singular();
        $plural     = $this->_labelParser->plural();

        $projectKey = Strata::getNamespace();
        $taxonomyKey = $taxonomyClassname::wordpressKey();

        $taxonomy = new $taxonomyClassname();
        $key = $this->_wordpressKey . "_" . $taxonomyKey;

        $customizedOptions = $taxonomy->configuration + array(
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'update_count_callback'     => '_update_post_term_count',
            'query_var'                 =>  $key,
            'show_in_nav_menus'          => false,
            'show_tagcloud'              => false,
            'rewrite' => array(
                'with_front' => true,
                'slug' => $key
            ),
            'capabilities' => array(
                'manage_terms' => 'read',
                'edit_terms'   => 'read',
                'delete_terms' => 'read',
                'assign_terms' => 'read',
            ),
            'labels'=> array()
        );

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

        return register_taxonomy($taxonomyKey, array($this->_wordpressKey), $customizedOptions);
    }

    private function _hasTaxonomyConfiguration()
    {
        return array_key_exists('has', $this->_entity->configuration) && count($this->_getTaxonomyConfiguration()) > 0;
    }

    private function _getTaxonomyConfiguration()
    {
        return $this->_entity->configuration['has'];
    }

}
