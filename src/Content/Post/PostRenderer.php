<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content\Post;

use MattyG\BBStatic\BBCode\NeedsBBCodeRendererTrait;
use MattyG\BBStatic\Template\NeedsTemplateEngineInterfaceTrait;
use Symfony\Component\Filesystem\NeedsFilesystemTrait;

class PostRenderer
{
    use NeedsBBCodeRendererTrait;
    use NeedsFilesystemTrait;
    use NeedsTemplateEngineInterfaceTrait;

    /**
     * @param Post $post
     * @return string The filename of the rendered page.
     */
    final public function render(Post $post) : string
    {
        $renderedContent = $this->bbcodeRenderer->build($post->getContentFilename());

        $context = array_merge($this->prepareContext($post), array("content" => $renderedContent));
        $renderedPage = $this->templateEngine->render($post->getPageType(), $context);

        $outFilename = $post->getOutputFolder() . DIRECTORY_SEPARATOR . "index.html";
        $this->filesystem->dumpFile($outFilename, $renderedPage);
        return $outFilename;
    }

    /**
     * @param Post $post
     * @return array
     */
    protected function prepareContext(Post $post) : array
    {
        return array(
            "title" => $post->getTitle(),
            "author" => $post->getAuthor(),
            "date_posted" => $post->getDatePosted(),
            "date_updated" => $post->getDateUpdated(),
            "tags" => $post->getTags(),
            "vars" => $post->getTemplateVariables(),
            "url_path" => $post->getUrlPath(),
        );
    }
}
