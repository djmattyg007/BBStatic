<?php
declare(strict_types=1);

namespace MattyG\BBStatic;

use Nbbc\BBCode;

class FileBuilder
{
    /**
     * @var BBCode
     */
    private $processor;

    /**
     * @param BBCode $processor
     */
    public function __construct(BBCode $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @param string $content
     * @return string
     */
    public function build(string $content): string
    {
        return $this->processor->parse($content);
    }
}
