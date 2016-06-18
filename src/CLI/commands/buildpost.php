<?php
declare(strict_types=1);

/** @var $config \MattyG\BBStatic\CLI\Config */
/** @var $di \Aura\Di\Container */

use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\Args\Format\Option;

$config->beginCommand("build-post")
    ->setDescription("Render a single post.")
    ->setHandler($di->lazyNew("MattyG\\BBStatic\\CLI\\Command\\BuildPostHandler"))
    ->addArgument("post", Argument::REQUIRED, "Post to render")
    ->addOption("sign", null, Option::NO_VALUE, "Create OpenPGP signatures (overrides configuration)")
    ->addOption("no-sign", null, Option::NO_VALUE, "Do not create OpenPGP signatures (overrides configuration)")
    ->end();
