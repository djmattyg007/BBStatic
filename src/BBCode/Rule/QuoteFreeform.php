<?php
declare(strict_types=1);

namespace MattyG\BBStatic\BBCode\Rule;

use Nbbc\BBCode;

class QuoteFreeform implements ConfigurableCallableRuleInterface
{
    /**
     * @var string
     */
    private $template = '<blockquote><p>{$content/v}</p><footer>{$citation/h}</blockquote>' . "\n";

    /**
     * @param string
     */
    private $shortTemplate = '<blockquote><p>{$content/v}</p></blockquote>' . "\n";

    /**
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    /**
     * @param string $template
     */
    public function setShortTemplate(string $template)
    {
        $this->shortTemplate = $template;
    }

    /**
     * @param BBCode $bbcode The BBCode object doing the parsing.
     * @param int $action The current action being performed on the tag.
     * @param string $name The name of the tag.
     * @param string $default The default value passed to the tag in the form: `[tag=default]`.
     * @param array $params All of the parameters passed to the tag.
     * @param string $content The content of the tag. Only available when $action is **BBCode::BBCODE_OUTPUT**.
     * @return bool|string Returns the quote HTML or **true** if $action is **BBCode::BBCODE_CHECK**.
     */
    public function __invoke(BBCode $bbcode, int $action, string $name, string $default, array $params, string $content)
    {
        if ($action == BBCode::BBCODE_CHECK) {
            return strlen($default) === 0;
        }

        if (empty($params["citation"])) {
            return $bbcode->fillTemplate($this->shortTemplate, array("content" => $content));
        } else {
            return $bbcode->fillTemplate($this->template, array("content" => $content, "citation" => $params["citation"]));
        }
    }
}
