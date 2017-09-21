<?php

namespace Vulpine\Traits;

use Vulpine\Scopes\ExcludeHiddenScope;

trait ExcludeHidden
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public static function bootExcludeHidden()
    {
        static::addGlobalScope(
            new ExcludeHiddenScope()
        );
    }
}