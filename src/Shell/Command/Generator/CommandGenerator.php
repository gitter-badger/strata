<?php
namespace Strata\Shell\Command\Generator;

class CommandGenerator extends ClassWriter {

    /**
     * Creates a Command class file
     * @return null
     */
    public function generate()
    {
        $this->command->output->writeLn("Scaffolding command <info>{$this->classname}</info>");

        $namespace = $this->_getNamespace();

        $destination = implode(DIRECTORY_SEPARATOR, array("src", "Shell", "Command", $this->classname . ".php"));
        $this->_createFile($destination, "$namespace\Shell\Command", $this->classname, "\Strata\Shell\Command\StrataCommand");

        $destination = implode(DIRECTORY_SEPARATOR, array("test", "Shell", "Command", $this->classname . "Test.php"));
        $this->_createFile($destination, "$namespace\Test\Model", "Test{$this->classname}", "\Strata\Test\Test", true);
    }
}
