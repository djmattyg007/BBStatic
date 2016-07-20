<?php
declare(strict_types=1);

namespace MattyG\BBStatic\CLI\Vendor;

use Aura\Di\Container as DiContainer;
use Symfony\Component\Console\Helper\ProgressBar;
use Webmozart\Console\Api\IO\IO;

class ProgressBarFactory
{
    /**
     * @var DiContainer
     */
    private $diContainer;

    /**
     * @var IOOutputAdapterFactory
     */
    private $ioOutputAdapterFactory;

    /**
     * @param DiContainer $diContainer
     * @param IOOutputAdapterFactory $ioOutputAdapterFactory
     */
    public function __construct(DiContainer $diContainer, IOOutputAdapterFactory $ioOutputAdapterFactory)
    {
        $this->diContainer = $diContainer;
        $this->ioOutputAdapterFactory = $ioOutputAdapterFactory;
    }

    /**
     * @param IO $io
     * @param int $max
     * @return ProgressBar
     */
    public function create(IO $io, int $max = 0) : ProgressBar
    {
        $ioOutputAdapter = $this->diContainer->newInstance(IOOutputAdapter::class, array("io" => $io));
        return $this->diContainer->newInstance(ProgressBar::class, array("output" => $ioOutputAdapter, "max" => $max));
    }
}
