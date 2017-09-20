<?php

namespace Vulpine\Traits;

use Vulpine\Scopes\ExcludeHiddenScope;

trait ExcludeHidden
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function bootExcludeHidden()
    {
        static::addGlobalScope(
            new ExcludeHiddenScope()
        );
    }
}