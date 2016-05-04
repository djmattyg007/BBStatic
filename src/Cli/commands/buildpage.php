<?php
declare(strict_types=1);

/** @var $config \MattyG\BBStatic\Cli\Config */
/** @var $di \Aura\Di\Container */

use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\Args\Format\Option;

$config->beginCommand("build-page")
    ->setDescription("Render a single page.")
    ->setHandler($di->lazyNew("MattyG\\BBStatic\\Cli\\Command\\BuildPageHandler"))
    ->addArgument("page", Argument::REQUIRED, "Page to render")
    ->addOption("sign", null, Option::NO_VALUE, "Create OpenPGP signature (overrides configuration)")
    ->addOption("no-sign", null, Option::NO_VALUE, "Do not create OpenPGP signature (overrides configuration)")
    ->end();
