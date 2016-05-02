<?php
declare(strict_types=1);

/** @var $config \MattyG\BBStatic\Cli\Config */
/** @var $di \Aura\Di\Container */

use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\Args\Format\Option;

$config->beginCommand("build-file")
    ->setDescription("Convert a single file from BBCode into HTML.")
    ->setHandler($di->lazyNew("MattyG\\BBStatic\\Cli\\Command\\BuildFileHandler"))
    ->addArgument("in-filename", Argument::REQUIRED, "File to convert")
    ->addArgument("out-filename", Argument::OPTIONAL, "File to create with converted content")
    ->addOption("sign", null, Option::NO_VALUE, "Create OpenPGP signature (overrides configuration)")
    ->addOption("no-sign", null, Option::NO_VALUE, "Do not create OpenPGP signature (overrides configuration)")
    ->end();
