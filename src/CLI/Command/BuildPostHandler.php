<?php
declare(strict_types=1);

namespace MattyG\BBStatic\CLI\Command;

use MattyG\BBStatic\Content\Post\NeedsPostBuilderTrait;
use MattyG\BBStatic\Util\NeedsConfigTrait;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class BuildPostHandler
{
    use NeedsConfigTrait;
    use NeedsPostBuilderTrait;
    use ShouldSignOutputTrait;

    /**
     * @param Args $args
     * @param IO $io
     */
    public function handle(Args $args, IO $io)
    {
        $postName = $args->getArgument("post");

        $this->postBuilder->createAndBuildPost($postName, $this->shouldSignOutput($args));
    }
}
