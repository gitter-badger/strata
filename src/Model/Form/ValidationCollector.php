<?php

namespace Strata\Model\Form;

use Strata\Controller\Request;
use Strata\View\Helper\FormHelper;

class ValidationCollector {

    /**
     * The current form scope on which the entity is tested
     * @var null
     */
    private $_form = null;

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

    /**
     * Collects the results of all the automated validators applied on the list of entity in
     * the sandbox of the request object
     * @param \Strata\Model\Form\Form $form  The $form in which values are posted.
     */
    function __construct(\Strata\Model\Form\Form $form)
    {
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

    /**
     *
     * @param array                      $entities A list of entities to be tests
     */
    public function collect(array $entities)
    {
        // Validate posted values against the entities if there is one set
        foreach ($entities as $entity) {
            $entityValues = $this->_getPostedData($entity);
            if (!is_null($entityValues)) {
                $this->_processEntity($entity, $entityValues);
            }
        }
    }

    /**
     * Return the entity scope of the posted array. Ex: data[entity]
     * @param  \Strata\Model\CustomPostType\Entity $entity [description]
     * @return [type]                                      [description]
     */
    private function _getPostedData(\Strata\Model\CustomPostType\Entity $entity)
    {
        $helper = $this->_form->getHelper();
        return $helper->getPostedValue($entity->getPostPrefix());
    }

    private function _addError($fieldkey, $errorMessage)
    {
        return $this->_errors[$fieldkey] = $errorMessage;
    }

    private function _addAssignment($fieldkey, $statusMessage)
    {
        return $this->_assignments[$fieldkey] = $statusMessage;
    }

    private function _checkSingleEntityValues($entity, $entityValues)
    {
        $feedback = $entity->validateForm($this->_form, $entityValues);

        $this->_collectEntityErrors($entity->getPostPrefix(), $feedback->getErrors());
        $this->_collectEntityAssignments($entity->getPostPrefix(), $feedback->getAssignments());
    }

    private function _checkSingleEntityValuesInSet($entity, $entityValues, $idx)
    {
        $contextualPostPrefix = sprintf("%s[%s]", $entity->getPostPrefix(), $idx);
        $feedback = $entity->validateForm($this->_form, $entityValues);

        $this->_collectEntityErrors($contextualPostPrefix, $feedback->getErrors());
        $this->_collectEntityAssignments($contextualPostPrefix, $feedback->getAssignments());
    }

    private function _collectEntityErrors($entityPostPrefix, $errors)
    {
        foreach ($errors as $attr => $error) {
            $fieldkey = sprintf("%s[%s]", $entityPostPrefix, $attr);
            $this->_addError($fieldkey, $error);
        }
    }

    private function _collectEntityAssignments($entityPostPrefix, $assignments)
    {
        foreach ($assignments as $attr => $status) {
            $fieldkey = sprintf("%s[%s]", $entityPostPrefix, $attr);
            $this->_addAssignment($fieldkey, $status);
        }
    }

    // Switch between multiple entities posted, or just one.
    private function _processEntity($entity, $entityValues)
    {
        if(!array_key_exists(0, $entityValues)) {
            $this->_checkSingleEntityValues($entity, $entityValues);
            return;
        }

        foreach ($entityValues as $idx => $repeatingEntityValues) {
            $this->_checkSingleEntityValuesInSet($entity, $repeatingEntityValues, $idx);
        }
    }
}
