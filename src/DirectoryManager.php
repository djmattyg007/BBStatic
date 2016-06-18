<?php
declare(strict_types=1);

namespace MattyG\BBStatic;

use MattyG\BBStatic\Util\Config;
use Symfony\Component\Filesystem\Filesystem;

class DirectoryManager
{
    // Private
    const DS = DIRECTORY_SEPARATOR;

    /**
     * @var array
     */
    protected $directories;

    /**
     * @var array
     */
    protected $urlPaths;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    protected $tempDirectory;

    /**
     * @param Config $config
     * @param Filesystem $filesystem
     */
    public function __construct(Config $config, Filesystem $filesystem)
    {
        $directories = $config->getValue("directories");
        $this->directories = array_map(function($dir) { return rtrim($dir, self::DS); }, array_filter($directories));
        $this->urlPaths = array(
            "pages" => str_replace("/", self::DS, $config->getValue("site/pages_url_path")),
            "posts" => str_replace("/", self::DS, $config->getValue("site/posts_url_path")),
        );

        $this->filesystem = $filesystem;

        $this->initTemp();
    }

    /**
     * Find usable temporary directory and ensure it's usable.
     */
    private function initTemp()
    {
        $this->tempDirectory = $this->directories["temp"] ?? rtrim(sys_get_temp_dir(), self::DS) . self::DS . getenv("USER") . "_bbstatic";
        $this->filesystem->remove($this->tempDirectory);
        $this->filesystem->mkdir($this->tempDirectory, 0755);
    }

    /**
     * @param string $segment
     * @return string
     */
    public function getCacheDirectory(string $segment) : string
    {
        $directory = $this->directories["cache"] . self::DS . $segment;
        if (is_dir($directory) === false) {
            $this->filesystem->mkdir($directory, 0755);
        }
        return $directory;
    }

    /**
     * @param string $segment
     * @return string
     */
    public function getTempDirectory(string $segment) : string
    {
        $directory = $this->tempDirectory . self::DS . $segment;
        if (is_dir($directory) === false) {
            $this->filesystem->mkdir($directory, 0755);
        }
        return $directory;
    }

    /**
     * @return string
     */
    public function getConfigFolderDirectory() : string
    {
        return $this->directories["conf"];
    }

    /**
     * @return string
     */
    public function getTemplatesDirectory() : string
    {
        return $this->directories["theme"];
    }

    /**
     * @return string
     */
    public function getTemplatePartialsDirectory() : string
    {
        return $this->directories["theme"] . self::DS . "partials";
    }

    /**
     * @return string
     */
    public function getHtmlDirectory() : string
    {
        return $this->directories["html"];
    }

    /**
     * @return string|null
     */
    public function getPageContentDirectory()
    {
        return $this->directories["pages"];
    }

    /**
     * @return string
     */
    public function getPageOutputDirectory() : string
    {
        return rtrim($this->getHtmlDirectory() . self::DS . $this->urlPaths["pages"], self::DS);
    }

    /**
     * @return string|null
     */
    public function getPostContentDirectory()
    {
        return $this->directories["posts"];
    }

    /**
     * @return string
     */
    public function getPostOutputDirectory() : string
    {
        return rtrim($this->getHtmlDirectory() . self::DS . $this->urlPaths["posts"], self::DS);
    }
}
