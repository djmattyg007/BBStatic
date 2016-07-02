<?php
declare(strict_types=1);

namespace MattyG\BBStatic\Template;

use MattyG\Handlebars\SafeString as BaseSafeString;

/**
 * All helpers should use this class for their SafeString implementation,
 * and should NOT depend on whatever the parent of this class is.
 */
class SafeString extends BaseSafeString
{
}
