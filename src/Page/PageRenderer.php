<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Page;

use MattyG\BBStatic\NeedsDirectoryManagerTrait;
use MattyG\BBStatic\BBCode\NeedsBBCodeRendererTrait;
use MattyG\BBStatic\Util\Vendor\NeedsTemplateEngineTrait;
use Symfony\Component\Filesystem\NeedsFilesystemTrait;

final class PageRenderer
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

        $convertedContent = $this->bbcodeRenderer->build($contentFilename);

        $template = $this->templateEngine->loadTemplate($pageType);
        $context = array(
            "title" => $page->getTitle(),
            "author" => $page->getAuthor(),
            "date_posted" => $page->getDatePosted(),
            "date_updated" => $page->getDateUpdated(),
            "content" => $convertedContent,
            "vars" => $page->getTemplateVariables(),
        );
        $renderedContent = $template->render($context);

        $outFilename = $this->directoryManager->getHtmlDirectory() . DIRECTORY_SEPARATOR . $page->getName() . ".html";
        $this->filesystem->dumpFile($outFilename, $renderedContent);
        return $outFilename;
    }
}
