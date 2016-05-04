<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Util\Vendor;

use Mustache_Engine;

trait NeedsTemplateEngineTrait
{
    /**
     * @var Mustache_Engine
     */
    protected $templateEngine = null;

    /**
     * @param Mustache_Engine $templateEngine
     */
    public function setTemplateEngine(Mustache_Engine $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }
}
