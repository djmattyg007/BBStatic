<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Signing;

use MattyG\BBStatic\Signing\Adapter\SigningAdapterInterface;

trait NeedsSignerTrait
{
    /**
     * @var SigningAdapterInterface
     */
    protected $signer = null;

    /**
     * @param SigningAdapterInterface $signer
     */
    public function setSigner(SigningAdapterInterface $signer)
    {
        $this->signer = $signer;
    }
}
