<?php
declare(strict_types=1);

/** @var $di \Aura\Di\Container */

return array(
    "mode" => \Nbbc\BBCode::BBCODE_MODE_CALLBACK,
    "method" => new \MattyG\BBStatic\Util\Vendor\LazyCallable($di->lazyNew("MattyG\\BBStatic\\BBCode\\Rule\\Heading")),
    "allow" => array("level" => '/^[1-6]$/'),
    "class" => "inline",
    "allow_in" => array("listitem", "block", "columns"),
    "plain_start" => "<h2>",
    "plain_end" => "</h2>",
);
