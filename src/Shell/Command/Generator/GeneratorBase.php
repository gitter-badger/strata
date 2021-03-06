<?php
/**
 */
namespace Strata\Shell\Command\Generator;

use Strata\Strata;

/**
 * Automates repetitive creation of code files. It validates the class names and
 * file locations based on the set of guidelines promoted by Strata.
 *
 * Intended use include:
 *     <code>bin/strata generate controller User</code>
 *     <code>bin/strata generate customposttype Task</code>
 *     ...
 */
class GeneratorBase
{

    protected $classname = "";

    /**
     * The base string template for creating empty class files.
     *
     * @var string
     */
    protected $_classTemplate = "<?php
namespace {NAMESPACE};

class {CLASSNAME} extends {EXTENDS} {


}";

    /**
     * A reference to the current command interface
     *
     * @var Strata\Shell\Command\GenerateCommand
     */
    protected $command = null;

    function __construct($command)
    {
        $this->command = $command;
    }

    /**
     * Returns the namespace of the current project.
     * @return string A valid namespace string.
     */
    protected function _getNamespace()
    {
        return Strata::getNamespace();
    }
}
