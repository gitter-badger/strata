<?php
namespace Strata\Shell\Command\Generator;

class HelperGenerator extends ClassWriter {


    /**
     * Creates a View helper class file
     * @return null
     */
    public function generate()
    {
        $this->command->output->writeLn("Scaffolding view helper <info>{$this->classname}</info>");

        $namespace = $this->_getNamespace();

        $destination = implode(DIRECTORY_SEPARATOR, array("src", "View", "Helper", $this->classname . ".php"));
        $this->_createFile($destination, "$namespace\View\Helper", $this->classname, "\\".$namespace."\View\Helper\AppHelper");

        $destination = implode(DIRECTORY_SEPARATOR, array("test", "View", "Helper", $this->classname . "Test.php"));
        $this->_createFile($destination, "$namespace\Tests\View\Helper", "Test{$this->classname}", "\Strata\Test\Test", true);
    }
}
