<?php
declare(strict_types=1);

namespace MattyG\BBStatic\BBCode\Rule;

use Nbbc\BBCode;

class Heading implements ConfigurableCallableRuleInterface
{
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
            return strlen($default) === 0 && !empty($params["level"]);
        }

        $level = $params["level"];
        if (empty($params["id"])) {
            return $bbcode->fillTemplate('<h' . $level . '>{$content/v}</h' . $level . '>', array("content" => $content));
        } else {
            return $bbcode->fillTemplate('<h' . $level . ' id="{$id/twu}">{$content/v}</h' . $level . '>', array("id" => $params["id"], "content" => $content));
        }
    }
}
