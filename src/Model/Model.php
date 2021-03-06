<?php
namespace Strata\Model;

use Strata\Model\Form\ModelValidation;
use Strata\Model\Validator\Validator;
use Strata\Utility\Hash;
use Strata\Strata;

use Exception;

/**
 * A base class for model objects
 */
class Model {

    /**
     * Generates a possible namespace and classname combination of a
     * Strata controller. Mainly used to avoid hardcoding the '\\Controller\\'
     * string everywhere.
     * @param  string $name The class name of the controller
     * @return string       A fulle namespaced controller name
     */
    public static function generateClassPath($name)
    {
        return Strata::getNamespace() . "\\Model\\" . ucfirst($name);
    }

    public static function factory($name)
    {
        $classpath = self::generateClassPath($name);
        if (class_exists($classpath)) {
            return new $classpath();
        }

        throw new Exception("Strata : No file matched the model '$classpath'.");
    }

    public static function staticFactory()
    {
        $class = get_called_class();
        return new $class();
    }

    public $attributes  = array();

    function __construct()
    {
        $this->_normalizeAttributes();
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function isSupportedAttribute($attr)
    {
        return in_array($attr, array_keys($this->getAttributes()));
    }

    public function hasAttributeValidation($attr)
    {
        return Hash::check($this->getAttributes(), "$attr.validations");
    }

    public function attemptAttributeSet($attr, $value, $formObject = null)
    {
        $attributeErrors = array();
        if ($this->hasAttributeValidation($attr)) {

            $validations = $this->_extractNormalizedValidations($attr);
            foreach ($validations as $validationKey => $validatorConfig) {

                $validator = Validator::factory($validationKey);
                $validator->configure($validatorConfig);

                if (!$validator->test($value, $formObject)) {
                    $attributeErrors[$validationKey] = $validator->getMessage();
                }
                break;
            }
        }
        return $attributeErrors;
    }

    public function validateForm($formObject, $dataset)
    {
        $validation = new ModelValidation($this, $formObject);
        $validation->validateSet($dataset);
        return $validation;
    }

    public function getPostPrefix()
    {
        return strtolower($this->getShortName());
    }

    public function getShortName()
    {
        $rc = new \ReflectionClass($this);
        return $rc->getShortName();
    }

    private function _extractNormalizedValidations($attr)
    {
        return Hash::normalize(Hash::extract($this->getAttributes(), "$attr.validations"));
    }

    private function _normalizeAttributes()
    {
        $this->attributes = Hash::normalize($this->attributes);
    }


}
