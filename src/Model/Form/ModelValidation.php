<?php

namespace Strata\Model\Form;

use Strata\Controller\Request;
use Strata\View\Helper\FormHelper;

class ModelValidation {


    /**
     * The list of errors generated
     * @var array
     */
    private $_errors = array();

    /**
     * The list of assignments generated.
     * @var array
     */
    private $_assignments = array();

    private $_model = null;
    private $_form = null;

    function __construct(\Strata\Model\Model $model, \Strata\Model\Form\Form $form)
    {
        $this->_model = $model;
        $this->_form = $form;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function hasErrors()
    {
        return count($this->_errors) > 0;
    }

    public function getAssignments()
    {
        return $this->_assignments;
    }

    public function hasAssignments()
    {
        return count($this->_assignments) > 0;
    }

    public function validateSet($dataset)
    {
        // Check each of the values in the dataset prefixed with this
        // entities' short class name for availability and validators.
        if (is_array($dataset)) {
            foreach ($dataset as $key => $value) {
                $errors = null;
                if ($this->_model->isSupportedAttribute($key)) {
                    $errors = $this->_model->attemptAttributeSet($key, $value, $this->_form);
                    if (count($errors) > 0) {
                        $this->_errors[$key] = $errors;
                    } else {
                        $this->_assignments[$key] = $value;
                    }
                }
            }
        }
    }
}
