<?php
declare(strict_types=1);

namespace MattyG\BBStatic;

use Thunder\Shortcode\Processor\Processor;

class FileBuilder
{
    /**
     * @var Processor
     */
    private $processor;

    /**
     * @param Processor $processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @param string $content
     * @return string
     */
    public function build(string $content): string
    {
        return $this->processor->process($content);
    }
}
