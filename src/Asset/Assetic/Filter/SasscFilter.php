<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Asset\Assetic\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Asset\FileAsset;
use Assetic\Filter\BaseProcessFilter;
use Assetic\Exception\FilterException;

class SasscFilter extends BaseProcessFilter
{
    const OUTPUTSTYLE_NESTED = "nested";
    const OUTPUTSTYLE_EXPANDED = "expanded";
    const OUTPUTSTYLE_COMPACT = "compact";
    const OUTPUTSTYLE_COMPRESSED = "compressed";

    /**
     * @var string
     */
    private $sasscPath;

    /**
     * @var string
     */
    private $outputStyle;

    /**
     * @param int
     */
    private $numberPrecision;

    /**
     * @param bool
     */
    private $outputSourcemap;

    /**
     * @param string $sasscPath
     */
    public function __construct(string $sasscPath = "/usr/bin/sassc")
    {
        $this->sasscPath = $sasscPath;
    }

    /**
     * @param string $outputStyle
     */
    public function setOutputStyle(string $outputStyle)
    {
        $this->outputStyle = $outputStyle;
    }

    /**
     * @param int $numberPrecision
     */
    public function setNumberPrecision(int $numberPrecision)
    {
        $this->numberPrecision = $numberPrecision;
    }

    /**
     * @param bool $outputSourcemap
     */
    public function setOutputSourcemap(bool $outputSourcemap)
    {
        $this->outputSourcemap = $outputSourcemap;
    }

    /**
     * @param AssetInterface $asset
     */
    public function filterLoad(AssetInterface $asset)
    {
        $pb = $this->createProcessBuilder(array($this->sasscPath));
        $pb->add("--stdin")->setInput($asset->getContent());

        if ($loadPath = $asset->getSourceDirectory()) {
            $pb->setWorkingDirectory($loadPath);
        }

        if ($this->outputStyle) {
            $pb->add("--style")->add($this->outputStyle);
        }
        if ($this->numberPrecision) {
            $pb->add("--precision")->add($this->numberPrecision);
        }
        if ($this->outputSourcemap === true) {
            $pb->add("--sourcemap");
        }

        $proc = $pb->getProcess();
        $returnCode = $proc->run();
        if ($returnCode !== 0) {
            throw FilterException::fromProcess($proc);
        }

        $asset->setContent($proc->getOutput());
    }

    /**
     * @param AssetInterface $asset
     */
    public function filterDump(AssetInterface $asset)
    {
    }
}
