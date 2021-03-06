<?php
namespace Strata\Shell\Command\Generator;

class CustomPostTypeGenerator extends ClassWriter {

    /**
     * Creates a Model class file
     * @return null
     */
    public function generate()
    {
        $this->command->output->writeLn("Scaffolding custom post type <info>{$this->classname}</info>");

        $namespace = $this->_getNamespace();

        $destination = implode(DIRECTORY_SEPARATOR, array("src", "Model", $this->classname . ".php"));
        $this->_createFile($destination, "$namespace\Model", $this->classname, "\Strata\Model\CustomPostType\Entity");

        $destination = implode(DIRECTORY_SEPARATOR, array("test", "Model", $this->classname . "Test.php"));
        $this->_createFile($destination, "$namespace\Test\Model", "Test{$this->classname}", "\Strata\Test\Test", true);
    }
}
