<?php
namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

trait CacheTrait {


    protected function cacheData(string $key, int $duration, $data): Collection {
        return Cache::remember($key, $duration, fn() => $data);
    }

    protected function clearCache(string $key) {
        Cache::forget($key);
    }
}