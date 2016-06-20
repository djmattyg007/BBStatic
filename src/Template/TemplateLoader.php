<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Template;

use RuntimeException;

final class TemplateLoader
{
    /**
     * @param string
     */
    private $templatesFolder;

    /**
     * @param string
     */
    private $fileExtension = "hbs";

    /**
     * @param string[]
     */
    private $templates = array();

    /**
     * @param string $templatesFolder
     * @throws RuntimeException
     */
    public function __construct(string $templatesFolder)
    {
        if (is_dir($templatesFolder) === false) {
            throw new RuntimeException(sprintf("Template folder '%s' does not exist", $templatesFolder));
        }
        $this->templatesFolder = $templatesFolder;
    }

    /**
     * @param string $fileExtension
     */
    public function setFileExtension(string $fileExtension)
    {
        $this->fileExtension = ltrim($fileExtension, ".");
    }

    /**
     * @param string $name
     * @return string
     */
    public function load(string $name) : string
    {
        if (!isset($this->templates[$name])) {
            $this->templates[$name] = $this->loadFile($name);
        }

        return $this->templates[$name];
    }

    /**
     * @param string $name
     * @return string
     */
    private function loadFile(string $name) : string
    {
        $filename = $this->templatesFolder . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $name) . "." . $this->fileExtension;
        if (!is_readable($filename)) {
            throw new RuntimeException(sprintf("Template '%s' could not be found or cannot be read.", $name));
        }
        return file_get_contents($filename);
    }
}
