<?php
declare(strict_types=1);

namespace MattyG\BBStatic\CLI\Vendor;

use Webmozart\Console\Adapter\IOOutput as BaseIOOutputAdapter;

class IOOutputAdapter extends BaseIOOutputAdapter
{
    /**
     * The adapter in the webmozart/console package has this value
     * hard-coded to false for no apparent reason.
     * This feels like a big hack that shouldn't be necessary, but
     * until it's properly supported upstream there isn't a lot I
     * can do.
     */
    public function isDecorated()
    {
        return $this->getIO()->getOutput()->getStream()->supportsAnsi();
    }
}
