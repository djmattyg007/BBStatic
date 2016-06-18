<?php
declare(strict_types=1);

/** @var $di \Aura\Di\Container */

return array(
    "mode" => \Nbbc\BBCode::BBCODE_MODE_CALLBACK,
    "method" => new \MattyG\BBStatic\Util\Vendor\LazyCallable($di->lazyNew("MattyG\\BBStatic\\BBCode\\Rule\\URLMap")),
    "class" => "link",
    "content" => \Nbbc\BBCode::BBCODE_REQUIRED,
    "allow_in" => array("listitem", "block", "columns", "inline"),
    "plain_start" => "<b>URL:</b>\n",
    "plain_end" => "\n",
);
