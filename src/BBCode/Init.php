<?php
declare(strict_types=1);

namespace MattyG\BBStatic\BBCode;

use MattyG\BBStatic\BBCode\Rule\ConfigurableCallableRuleInterface;
use Nbbc\BBCode;

/**
 * @param string $filename
 * @return array
 */
function MattyGBBStaticInitRequire(string $filename): array
{
    return require($filename);
}

class Init
{
    /**
     * @var string[]
     */
    protected $rulesDirs = array();

    /**
     * @var string[]
     */
    protected $templateOverridesDirs = array();

    /**
     * @param string[]
     */
    protected $customRules = array();

    /**
     * @param string $rulesDir
     * @param string $handlersDir
     */
    public function __construct(string $rulesDir = __DIR__ . "/rules", string $templateOverridesDir = __DIR__ . "/template_overrides")
    {
        $this->addRulesDir($rulesDir);
        $this->addTemplateOverridesDir($templateOverridesDir);
    }

    /**
     * @param string $rulesDir
     */
    public function addRulesDir(string $rulesDir)
    {
        $this->rulesDirs[] = realpath($rulesDir);
    }

    /**
     * @param string $templateOverridesDir
     */
    public function addTemplateOverridesDir(string $templateOverridesDir)
    {
        $this->templateOverridesDirs[] = realpath($templateOverridesDir);
    }

    public function init(): BBCode
    {
        $bbcode = new BBCode;
        $this->initRules($bbcode);
        $this->initTemplateOverrides($bbcode);
        return $bbcode;
    }

    protected function initRules(BBCode $bbcode)
    {
        foreach ($this->rulesDirs as $rulesDir) {
            foreach (glob($rulesDir . "/*.php") as $ruleFile) {
                $ruleName = pathinfo($ruleFile, PATHINFO_FILENAME);
                $this->customRules[] = $ruleName;
                $rule = MattyGBBStaticInitRequire($ruleFile);
                /*if (isset($rule["mode"]) && $rule["mode"] === BBCode::BBCODE_MODE_CALLBACK) {
                    if ($rule["method"] instanceof ConfigurableCallableRuleInterface) {
                        $rule["method"] = $this->wrapCallbackRule($rule["method"]);
                    }
                }*/
                $bbcode->addRule($ruleName, $rule);
            }
        }
    }

    /**
     * @param ConfigurableCallableRuleInterface $callback
     * @return callable
     */
    protected function wrapCallbackRule(ConfigurableCallableRuleInterface $callback): callable
    {
        //
    }

    /**
     * @param BBCode $bbcode
     */
    protected function initTemplateOverrides(BBCode $bbcode)
    {
        foreach ($this->templateOverridesDirs as $templateOverridesDir) {
            foreach (glob($templateOverridesDir . "/*.php") as $templateOverrideFile) {
                $ruleName = pathinfo($templateOverrideFile, PATHINFO_FILENAME);
                $overrides = MattyGBBStaticInitRequire($templateOverrideFile);
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
                        $bbcode->addRule($key, $value);
                    }
                }
            }
        }
    }
}
