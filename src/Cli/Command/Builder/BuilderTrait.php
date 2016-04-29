<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Cli\Command\Builder;

use MattyG\BBStatic\FileBuilder;

trait BuilderTrait
{
    /**
     * @var FileBuilder
     */
    private $fileBuilder = null;

    /**
     * @return FileBuilder
     */
    protected function getFileBuilder() : FileBuilder
    {
        if ($this->fileBuilder === null) {
            $bbCodeInit = new \MattyG\BBStatic\BBCode\Init();
            $this->fileBuilder = new \MattyG\BBStatic\FileBuilder($bbCodeInit->init());
        }
        return $this->fileBuilder;
    }
}
