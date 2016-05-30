<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Signing;

use Aura\Di\Container as DiContainer;
use InvalidArgumentException; // TODO: Change this to a more appropriate exception type

class SigningAdapterInterfaceSharedProxy implements SigningAdapterInterface
{
    /**
     * @var DiContainer
     */
    private $_diContainer;

    /**
     * @var SigningAdapterInterface
     */
    private $_subject;

    /**
     * @param DiContainer $diContainer
     */
    public function __construct(DiContainer $diContainer)
    {
        $this->_diContainer = $diContainer;
    }

    /**
     * @return SigningAdapterInterface
     */
    private function _getSubject() : SigningAdapterInterface
    {
        if ($this->_subject === null) {
            $config = $this->_diContainer->get("config");
            $adapter = $config->getValue("signing/adapter", null);
            if ($adapter === null) {
                throw new InvalidArgumentException("No signing adapter configured.");
            }
            if ($adapter === "gnupg") {
                $this->_subject = $this->_diContainer->newInstance("MattyG\\BBStatic\\Signing\\GnuPGAdapter");
            } elseif (class_exists($adapter)) {
                $this->_subject = $this->_diContainer->newInstance($adapter);
            } else {
                throw new InvalidArgumentException(sprintf("Unrecognised signing adapter '%s' configured.", $adapter));
            }
            if (!$this->_subject instanceof SigningAdapterInterface) {
                throw new \RuntimeException(sprintf("Invalid signing adapter '%s' configured.", $adapter));
            }
        }
        return $this->_subject;
    }

    /**
     * @param string $filename
     * @throws \Exception If a signature cannot be generated.
     */
    public function sign(string $filename)
    {
        $this->_getSubject()->sign($filename);
    }

    /**
     * @return string
     */
    public function getSignatureFileGlobPattern() : string
    {
        return $this->_getSubject()->getSignatureFileGlobPattern();
    }
}
