<?php

namespace Strata\Model\Validator;

use Strata\Model\Validator\Validator;

class PostalcodeValidator extends Validator {

    protected $_errorMessage = "Only Canadian postal codes are supported (ex: H0H 0H0).";

    public function test($value, $context)
    {
        return preg_match("/\w\d\w\s?\d\w\d/i", trim($value));
    }

}
