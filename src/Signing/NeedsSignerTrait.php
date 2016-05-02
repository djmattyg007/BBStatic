<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Signing;

trait NeedsSignerTrait
{
    /**
     * @var SigningManager
     */
    protected $signer = null;

    /**
     * @param SigningManager $signer
     */
    public function setSigner(SigningManager $signer)
    {
        $this->signer = $signer;
    }
}
