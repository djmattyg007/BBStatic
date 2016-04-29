<?php
declare(strict_types=1);

namespace MattyG\BBStatic;

use InvalidArgumentException;
use Nbbc\BBCode;

final class FileBuilder
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
    public function process(string $content): string
    {
        return $this->processor->parse($content);
    }

    /**
     * @param string $inputFilename
     * @return string
     * @throws InvalidArgumentException
     */
    public function build(string $inputFilename): string
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
     * @return bool Whether or not the output file was written to successfully.
     * @throws InvalidArgumentException
     */
    public function buildAndOutput(string $inputFilename, string $outputFilename): bool
    {
        $processedContent = $this->build($inputFilename);
        $check = file_put_contents($outputFilename, $processedContent);
        if ($check === false) {
            return false;
        } else {
            return true;
        }
    }
}
