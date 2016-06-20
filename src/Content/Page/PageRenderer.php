<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content\Page;

use MattyG\BBStatic\BBCode\NeedsBBCodeRendererTrait;
use MattyG\BBStatic\Template\NeedsTemplateEngineInterfaceTrait;
use Symfony\Component\Filesystem\NeedsFilesystemTrait;

class PageRenderer
{
    use NeedsBBCodeRendererTrait;
    use NeedsFilesystemTrait;
    use NeedsTemplateEngineInterfaceTrait;

    /**
     * @param Page $page
     * @return string The filename of the rendered page.
     */
    final public function render(Page $page) : string
    {
        $renderedContent = $this->bbcodeRenderer->build($page->getContentFilename());

        $context = array_merge($this->prepareContext($page), array("content" => $renderedContent));
        $renderedPage = $this->templateEngine->render($page->getPageType(), $context);

        $outFilename = $page->getOutputFolder() . DIRECTORY_SEPARATOR . "index.html";
        $this->filesystem->dumpFile($outFilename, $renderedPage);
        return $outFilename;
    }

    /**
     * @param Page $page
     * @return array
     */
    protected function prepareContext(Page $page) : array
    {
        return array(
            "title" => $page->getTitle(),
            "author" => $page->getAuthor(),
            "date_posted" => $page->getDatePosted(),
            "date_updated" => $page->getDateUpdated(),
            "vars" => $page->getTemplateVariables(),
        );
    }
}
