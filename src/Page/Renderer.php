<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Page;

use MattyG\BBStatic\NeedsDirectoryManagerTrait;
use MattyG\BBStatic\BBCode\NeedsBBCodeRendererTrait;
use MattyG\BBStatic\Util\Vendor\NeedsTemplateEngineTrait;
use Symfony\Component\Filesystem\NeedsFilesystemTrait;

final class Renderer
{
    use NeedsDirectoryManagerTrait;
    use NeedsBBCodeRendererTrait;
    use NeedsFilesystemTrait;
    use NeedsTemplateEngineTrait;

    /**
     * @param Page $page
     * @return string The filename of the rendered page.
     */
    public function render(Page $page) : string
    {
        $tempDirectory = $this->directoryManager->getTempDirectory("page-content");
        $pageType = $page->getPageType();
        $contentFilename = $page->getContentFilename();
        $inputFilename = $this->makeInputFilenameRelative($contentFilename);

        $convertedContentFilename = $this->filesystem->tempnam($tempDirectory . DIRECTORY_SEPARATOR, $page->getName());
        $this->bbcodeRenderer->buildAndOutput($contentFilename, $convertedContentFilename);

        $template = $this->templateEngine->loadTemplate($pageType);
        $context = array(
            "title" => $page->getTitle(),
            "author" => $page->getAuthor(),
            "date_posted" => $page->getDatePosted(),
            "date_updated" => $page->getDateUpdated(),
            "content" => file_get_contents($convertedContentFilename),
            "vars" => $page->getTemplateVariables(),
        );
        $renderedContent = $template->render($context);

        $outFilename = $this->directoryManager->getHtmlDirectory() . DIRECTORY_SEPARATOR . $inputFilename . ".html";
        $this->filesystem->dumpFile($outFilename, $renderedContent);
        return $outFilename;
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function makeInputFilenameRelative(string $filename) : string
    {
        $pagesDirectory = $this->directoryManager->getPagesDirectory();
        $relativeFilename = $this->filesystem->makePathRelative($filename, $pagesDirectory);
        // Strip trailing slash from makePathRelative() and remove '.bb' file extension
        return substr(rtrim($relativeFilename, "/"), 0, -3);
    }
}
