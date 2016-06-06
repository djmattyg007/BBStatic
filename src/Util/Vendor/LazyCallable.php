<?php

namespace MattyG\BBStatic\Util\Vendor;

use Aura\Di\Injection\LazyInterface;

/**
 * Shim, until my PR to the Aura.Di library is accepted
 */
class LazyCallable implements LazyInterface
{
    protected $callable;

    protected $callableChecked = false;

    public function __construct($callable)
    {
        $this->callable = $callable;
    }

    public function __invoke()
    {
        if ($this->callableChecked == false) {
            if ($this->callable instanceof LazyInterface) {
                $this->callable = $this->callable->__invoke();
            }
            $this->callableChecked = true;
        }

        return call_user_func_array($this->callable, func_get_args());
    }
}
