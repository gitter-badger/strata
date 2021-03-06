<?php
namespace Strata\Model\CustomPostType;

use Strata\Utility\Hash;
use Strata\Utility\Inflector;
use Strata\Strata;
use Strata\Model\Model;

class LabelParser
{
    private $_plural = "";
    private $_singular = "";
    private $_entity;

    function __construct($entity)
    {
        $this->_entity = $entity;
    }

    public function plural()
    {
        return $this->_plural;
    }

    public function singular()
    {
        return $this->_singular;
    }

    public function parse()
    {
        // Fetch the basic values from possible user defined values.
       // $object = self::factory();
        if (Hash::check($this->_entity->configuration, "labels")) {
            if (Hash::check($this->_entity->configuration, "labels.singular_name")) {
                $this->_singular = Hash::get($this->_entity->configuration, "labels.singular_name");
            } elseif (Hash::check($this->_entity->configuration, "labels.name")) {
                $this->_plural = Hash::get($this->_entity->configuration, "labels.name");
            }
        }

        if (!empty($this->_singular) && empty($this->_plural)) {
            $this->_plural = Inflector::pluralize($this->_singular);
        }

        if (!empty($this->_plural) && empty($this->_singular)) {
            $this->_singular = Inflector::singularize($this->_plural);
        }

        // If nothing is sent in, guess the name from the object name.
        if (empty($this->_plural) && empty($this->_singular)) {
            $this->_singular   = ucfirst(Inflector::singularize($this->_entity->getShortName()));
            $this->_plural     = ucfirst(Inflector::pluralize($this->_entity->getShortName()));
        }
    }
}
