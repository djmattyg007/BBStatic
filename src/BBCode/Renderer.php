<?php
declare(strict_types=1);

namespace MattyG\BBStatic\BBCode;

use InvalidArgumentException;
use MattyG\BBStatic\Util\Vendor\NeedsFilesystemTrait;
use Nbbc\BBCode;

final class Renderer
{
    use NeedsFilesystemTrait;

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
    public function process(string $content) : string
    {
        return $this->processor->parse($content);
    }

    /**
     * @param string $inputFilename
     * @return string
     * @throws InvalidArgumentException
     */
    public function build(string $inputFilename) : string
    {
        if (!is_readable($inputFilename)) {
            throw new InvalidArgumentException(sprintf("File '%s' does not exist.", $inputFilename));
        }
        $content = file_get_contents($inputFilename);
        $processedContent = $this->process($content);
        return $processedContent;
    }

    /**
     * @param string $inputFilename
     * @param string $outputFilename
     * @throws InvalidArgumentException
     */
    public function buildAndOutput(string $inputFilename, string $outputFilename)
    {
        $processedContent = $this->build($inputFilename);
        $this->filesystem->dumpFile($outputFilename, $processedContent);
    }
}
