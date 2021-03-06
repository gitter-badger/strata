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
class ClassWriter extends GeneratorBase {

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

    public function setClassName($classname)
    {
        $this->classname = $classname;
    }

    /**
     * Generates a file string content based on the global template.
     * @param  string $namespace The class' namespace
     * @param  string $classname The class name
     * @param  string $extends   The extending class
     * @return string            The generated class string
     */
    protected function _generateFileContents($namespace, $classname, $extends)
    {
        $data = $this->_classTemplate;

        $data = str_replace("{EXTENDS}", $extends, $data);
        $data = str_replace("{NAMESPACE}", $namespace, $data);
        $data = str_replace("{CLASSNAME}", $classname, $data);

        return $data;
    }

    /**
     * Generates and writes a file in the file system
     * @param  string  $destination The file's destination
     * @param  string $namespace The class' namespace
     * @param  string $classname The class name
     * @param  string $extends   The extending class
     * @param  boolean $last     Specifies if this is the last file in a queue
     * @return null
     */
    protected function _createFile($destination, $namespace, $classname, $extends, $last = false)
    {
        if (!file_exists($destination)) {

            $dir = dirname($destination);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $contents = $this->_generateFileContents($namespace, $classname, $extends);
            if (@file_put_contents($destination, $contents) > 0) {
                $this->command->output->writeLn($this->command->tree($last) . $this->command->ok($destination));
            } else {
                $this->command->output->writeLn($this->command->tree($last) . $this->command->fail($destination));
            }
        } else {
            $this->command->output->writeLn($this->command->tree($last) . $this->command->skip($destination));
        }
    }
}
