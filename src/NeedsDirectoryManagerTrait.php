<?php
declare(strict_types=1);

namespace MattyG\BBStatic;

trait NeedsDirectoryManagerTrait
{
    /**
     * @var DirectoryManager
     */
    protected $directoryManager = null;

    /**
     * @param DirectoryManager $directoryManager
     */
    public function setDirectoryManager(DirectoryManager $directoryManager)
    {
        $this->directoryManager = $directoryManager;
    }
}
