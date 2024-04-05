<?php

namespace App\Listeners;

use App\Http\Traits\CacheTrait;

class ClearEventCache
{
    use CacheTrait;

    public function handle(): void
    {
        $this->clearCache('all-events');
    }
}
