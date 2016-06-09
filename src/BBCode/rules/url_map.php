<?php
declare(strict_types=1);

/** @var $di \Aura\Di\Container */

return array(
    "mode" => \Nbbc\BBCode::BBCODE_MODE_CALLBACK,
    "method" => new \MattyG\BBStatic\Util\Vendor\LazyCallable($di->lazyNew("MattyG\\BBStatic\\BBCode\\Rule\\URLMap")),
    "class" => "link",
    "allow_in" => array("listitem", "block", "columns", "inline"),
    "before_tag" => "sns",
    "after_tag" => "sns",
    "before_endtag" => "sns",
    "after_endtag" => "sns",
    "plain_start" => "<b>URL:</b>\n",
    "plain_end" => "\n",
);
