<?php
declare(strict_types=1);

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

return function(ShortcodeInterface $s) {
    return sprintf("Hello, %s!", $s->getParameter("name"));
};
