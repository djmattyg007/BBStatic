<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Signing\Adapter;

class NullAdapter implements SigningAdapterInterface
{
    /**
     * @param string $filename
     * @throws \Exception If a signature cannot be generated.
     */
    public function signDetached(string $filename)
    {
    }
}
