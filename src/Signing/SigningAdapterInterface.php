<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Signing;

interface SigningAdapterInterface
{
    /**
     * @param string $filename
     * @throws \Exception If a signature cannot be generated.
     */
    public function sign(string $filename);

    /**
     * @return string
     */
    public function getSignatureFileGlobPattern() : string;
}
