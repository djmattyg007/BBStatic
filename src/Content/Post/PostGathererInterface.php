<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Content\Post;

use Icecave\Collections\Vector as PostCollection;

interface PostGathererInterface
{
    /**
     * @return PostCollection
     */
    public function gatherPosts() : PostCollection;
}
