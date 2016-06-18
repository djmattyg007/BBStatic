<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content\Page;

use MattyG\BBStatic\BBCode\NeedsBBCodeRendererTrait;
use MattyG\BBStatic\Util\Vendor\NeedsTemplateEngineTrait;
use Symfony\Component\Filesystem\NeedsFilesystemTrait;

final class PageRenderer
{
    use NeedsBBCodeRendererTrait;
    use NeedsFilesystemTrait;
    use NeedsTemplateEngineTrait;

    /**
     * @param Page $page
     * @return string The filename of the rendered page.
     */
    public function render(Page $page) : string
    {
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

        $outFilename = $page->getOutputFolder() . DIRECTORY_SEPARATOR . "index.html";
        $this->filesystem->dumpFile($outFilename, $renderedContent);
        return $outFilename;
    }
}
