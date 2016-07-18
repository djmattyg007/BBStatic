<?php
declare(strict_types=1);

namespace MattyG\BBStatic\DI;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;

class TemplateEngine implements ContainerConfigInterface
{
    // Private
    const ROOT_NS = "MattyG\\BBStatic\\Template\\";
    const HBS_NS = "MattyG\\Handlebars\\";

    public function define(Container $di)
    {
        $di->params[self::ROOT_NS . "MattyGHandlebarsAdapter"]["handlebars"] = $di->lazyGet("mattyg_handlebars");
        $di->params[self::ROOT_NS . "MattyGHandlebarsAdapter"]["templateLoader"] = $di->lazyGet("template_loader");
        $di->params[self::HBS_NS . "Compiler"]["runtime"] = $di->lazyGet("handlebars_runtime");
        $di->params[self::HBS_NS . "Handlebars"]["compiler"] = $di->lazyGet("handlebars_compiler");
        $di->params[self::HBS_NS . "Handlebars"]["runtime"] = $di->lazyGet("handlebars_runtime");
        $di->setters[self::ROOT_NS . "NeedsTemplateEngineInterfaceTrait"]["setTemplateEngine"] = $di->lazyGet("template_engine");
        $di->setters[self::ROOT_NS . "TemplateLoader"]["setFileExtension"] = $di->lazyGetCall("config", "getValue", "templates/fileext", "hbs");
        $di->setters[self::HBS_NS . "Handlebars"]["setCachePath"] = $di->lazyGetCall("directory_manager", "getCacheDirectory", "templates");
        $di->set("handlebars_compiler", $di->lazyNew(self::HBS_NS . "Compiler"));
        $di->set("handlebars_runtime", $di->lazyNew(self::HBS_NS . "Runtime"));
        $templateEngineInit = $di->lazyNew(self::ROOT_NS . "Init", array(
            "partialsDir" => $di->lazyGetCall("directory_manager", "getTemplatePartialsDirectory"),
        ));
        $di->set("template_engine", $di->lazy(array($templateEngineInit, "init")));
        $di->set("mattyg_handlebars", $di->lazyNew(self::HBS_NS . "Handlebars"));
        $di->set("template_loader", $di->lazyNew(self::ROOT_NS . "TemplateLoader", array(
            "templatesFolder" => $di->lazyGetCall("directory_manager", "getTemplatesDirectory"),
        )));
    }

    public function modify(Container $di)
    {
    }
}
