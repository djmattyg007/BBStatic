<?php
declare(strict_types=1);

namespace MattyG\BBStatic\CLI\Command;

use Webmozart\Console\Api\Args\Args;

/**
 * This trait needs \MattyG\BBStatic\Util\NeedsConfigTrait. Unfortunately, traits in PHP
 * are copy+paste with no dependency resolution, so we cannot include it here.
 *
 * @see \MattyG\BBStatic\Util\NeedsConfigTrait
 */
trait ShouldSignOutputTrait
{
    /**
     * @param Args $args
     * @return bool
     */
    private function shouldSignOutput(Args $args) : bool
    {
        $configuredSigningOption = $this->config->getValue("signing/enabled", false);
        if ($configuredSigningOption === true) {
            return !$args->isOptionSet("no-sign");
        } else {
            return $args->isOptionSet("sign");
        }
    }
}
