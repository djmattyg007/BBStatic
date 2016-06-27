<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Template;

use MattyG\Handlebars\Handlebars;

class MattyGHandlebarsAdapter implements TemplateEngineInterface
{
    /**
     * @var Handlebars
     */
    protected $handlebars;

    /**
     * @var TemplateLoader
     */
    protected $templateLoader;

    /**
     * @param Handlebars $handlebars
     * @param TemplateLoader $templateLoader
     */
    public function __construct(Handlebars $handlebars, TemplateLoader $templateLoader)
    {
        $this->handlebars = $handlebars;
        $this->templateLoader = $templateLoader;
    }

    /**
     * @param string $name
     * @return callable
     */
    public function compile(string $name) : callable
    {
        $templateString = $this->templateLoader->load($name);
        return $this->handlebars->compile($templateString);
    }

    /**
     * @param string $name
     * @param array $context
     * @return string
     */
    public function render(string $name, array $context) : string
    {
        $template = $this->compile($name);
        return $template($context);
    }

    /**
     * @param string $name
     * @param callable $helper
     */
    public function registerHelper(string $name, callable $helper)
    {
        $this->handlebars->registerHelper($name, $helper);
    }

    /**
     * @param string $name
     * @param string $partialString
     */
    public function registerPartial(string $name, string $partialString)
    {
        $this->handlebars->registerPartial($name, $partialString);
    }
}
