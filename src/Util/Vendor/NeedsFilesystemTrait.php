<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Util\Vendor;

use Symfony\Component\Filesystem\Filesystem;

trait NeedsFilesystemTrait
{
    /**
     * @var Filesystem
     */
    protected $filesystem = null;

    /**
     * @param Filesystem $filesystem
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }
}
