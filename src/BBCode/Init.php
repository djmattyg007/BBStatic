<?php
declare(strict_types=1);

namespace MattyG\BBStatic\BBCode;

use Aura\Di\Container as DiContainer;
use Nbbc\BBCode;
use Nbbc\Debugger as BBCodeDebugger;
use Symfony\Component\Finder\Finder as SymfonyFinder;

/**
 * @param DiContainer $di
 * @param string $filename
 * @return array
 */
function MattyGBBStaticInitRequire(DiContainer $di, string $filename): array
{
    return require($filename);
}

final class Init
{
    /**
     * @var DiContainer
     */
    private $di;

    /**
     * @var string[]
     */
    private $rulesDirs = array();

    /**
     * @var string[]
     */
    private $templateOverridesDirs = array();

    /**
     * @var SymfonyFinder
     */
    private $finderProto;

    /**
     * @param string $rulesDir
     * @param string $handlersDir
     */
    public function __construct(DiContainer $di, string $rulesDir = __DIR__ . "/rules", string $templateOverridesDir = __DIR__ . "/template_overrides")
    {
        $this->di = $di;
        $this->addRulesDir($rulesDir);
        $this->addTemplateOverridesDir($templateOverridesDir);

        $this->finderProto = new SymfonyFinder();
        $this->finderProto->files()
            ->name("*.php")
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->followLinks();
    }

    /**
     * @param string $rulesDir
     */
    public function addRulesDir(string $rulesDir)
    {
        $this->rulesDirs[] = $rulesDir;
    }

    /**
     * @param string $templateOverridesDir
     */
    public function addTemplateOverridesDir(string $templateOverridesDir)
    {
        $this->templateOverridesDirs[] = $templateOverridesDir;
    }

    /**
     * @return BBCode
     */
    public function init() : BBCode
    {
        $bbcode = new BBCode;
        $this->initRules($bbcode);
        $this->initTemplateOverrides($bbcode);

        if (getenv("BBCODE_DEBUG") == 1) {
            BBCodeDebugger::$level = 0;
        }

        return $bbcode;
    }

    /**
     * @param BBCode $bbcode
     */
    protected function initRules(BBCode $bbcode)
    {
        foreach ($this->rulesDirs as $rulesDir) {
            $globber = clone $this->finderProto;
            $globber->in($rulesDir);
            foreach ($globber as $ruleFile) {
                $ruleFilename = $ruleFile->getRelativePathname();
                $ruleName = pathinfo($ruleFilename, PATHINFO_FILENAME);
                $rule = MattyGBBStaticInitRequire($this->di, $rulesDir . "/" . $ruleFilename);
                $bbcode->addRule($ruleName, $rule);
            }
        }
    }

    /**
     * @param BBCode $bbcode
     */
    protected function initTemplateOverrides(BBCode $bbcode)
    {
        foreach ($this->templateOverridesDirs as $templateOverridesDir) {
            $globber = clone $this->finderProto;
            $globber->in($templateOverridesDir);
            foreach ($globber as $templateOverrideFile) {
                $templateOverrideFilename = $templateOverrideFile->getRelativePathname();
                $ruleName = pathinfo($templateOverrideFilename, PATHINFO_FILENAME);
                $overrides = MattyGBBStaticInitRequire($this->di, $templateOverridesDir . "/" . $templateOverrideFilename);
                foreach ($overrides as $key => $value) {
                    $rule = $bbcode->getRule($ruleName);
                    if ($key === "method_template") {
                        $method = $value["method"];
                        if (isset($rule["method"]) && $rule["method"] instanceof ConfigurableCallableRuleInterface) {
                            $rule["method"]->$method($value["template"]);
                        } else {
                            $bbcode->$method($value["template"]);
                        }
                    } else {
                        $rule[$key] = $value;
                        $bbcode->addRule($ruleName, $rule);
                    }
                }
            }
        }
    }
}
