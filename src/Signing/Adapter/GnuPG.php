<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Signing\Adapter;

class GnuPG implements SigningAdapterInterface
{
    const SIG_FILE_EXT_DETACHED = "sig";

    /**
     * @var string
     */
    protected $gpgPath;

    /**
     * @param array $options
     * @throws \RuntimeException
     */
    public function __construct(array $options = array())
    {
        if (isset($options["gpg_path"]) && is_executable($options["gpg_path"])) {
            $this->gpgPath = $options["gpg_path"];
        } else {
            $this->gpgPath = $this->findGpgPath();
        }
        if (!$this->gpgPath) {
            throw \RuntimeException("Cannot locate GnuPG binary.");
        }
    }

    /**
     * @return string
     */
    private function findGpgPath() : string
    {
        $paths = explode(PATH_SEPARATOR, getenv("PATH"));
        $gpgBinaries = array("gpg2", "gpg");
        foreach ($paths as $path) {
            foreach ($gpgBinaries as $gpgBinary) {
                if (is_executable($path . DIRECTORY_SEPARATOR . $gpgBinary) === true) {
                    return $path . DIRECTORY_SEPARATOR . $gpgBinary;
                }
            }
        }
        return null;
    }

    /**
     * @param string $filename
     * @throws \Exception If a signature cannot be generated.
     */
    public function signDetached(string $filename)
    {
        $sigFilename = $this->makeSigFilename($filename, self::SIG_FILE_EXT_DETACHED);
        if (file_exists($sigFilename) === true) {
            unlink($sigFilename);
        }

        $args = array(
            $this->gpgPath,
            "--output",
            $sigFilename,
            "--detach-sign",
            $filename
        );
        exec(implode(" ", array_map("escapeshellarg", $args)), $output, $ret);
        if ($ret !== 0) {
            throw new \RuntimeException(sprintf("GnuPG failed to create a detached signature for %s.", $filename));
        }
    }

    /**
     * @return string
     */
    public function getDetachedSignatureFileGlobPattern() : string
    {
        return "*." . self::SIG_FILE_EXT_DETACHED;
    }

    /**
     * @param string $filename
     * @param string $sigExtension
     * @return string
     */
    private function makeSigFilename(string $filename, string $sigExtension) : string
    {
        $pathInfo = pathinfo($filename);
        return $pathInfo["dirname"] . DIRECTORY_SEPARATOR . $pathInfo["filename"] . "." . $sigExtension;
    }
}
