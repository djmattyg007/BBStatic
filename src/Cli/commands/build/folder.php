<?php
declare(strict_types=1);

/** @var $config \MattyG\BBStatic\Cli\Config */
/** @var $di \Aura\Di\Container */

use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\Args\Format\Option;

$config->beginCommand("build-folder")
    ->setDescription("Convert a folder full of BBCode files into HTML.")
    ->setHandler($di->lazyNew("MattyG\\BBStatic\\Cli\\Command\\Build\\Folder"))
    ->addArgument("folder-path", Argument::REQUIRED, "Folder full of files to convert")
    ->addOption("sign", null, Option::NO_VALUE, "Create detached OpenPGP signature (overrides configuration)")
    ->addOption("no-sign", null, Option::NO_VALUE, "Do not create detached OpenPGP signature (overrides configuration)")
    ->end();
