<?php
declare(strict_types=1);

namespace MattyG\BBStatic;

trait NeedsFileBuilderTrait
{
    /**
     * @var FileBuilder
     */
    protected $fileBuilder = null;

    /**
     * @param FileBuilder $fileBuilder
     */
    public function setFileBuilder(FileBuilder $fileBuilder)
    {
        $this->fileBuilder = $fileBuilder;
    }
}
