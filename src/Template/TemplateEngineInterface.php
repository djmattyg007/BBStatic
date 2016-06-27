<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Template;

interface TemplateEngineInterface
{
    /**
     * @param string $name
     * @return callable
     */
    public function compile(string $name) : callable;

    /**
     * @param string $name
     * @param array $context
     * @return string
     */
    public function render(string $name, array $context) : string;

    /**
     * @param string $name
     * @param callable $helper
     */
    public function registerHelper(string $name, callable $helper);

    /**
     * @param string $name
     * @param string $partialString
     */
    public function registerPartial(string $name, string $partialString);
}
