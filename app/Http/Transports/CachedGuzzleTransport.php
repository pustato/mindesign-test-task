<?php

namespace App\Http\Transports;

use Illuminate\Support\Carbon;

class CachedGuzzleTransport extends GuzzleTransport
{
    public function get(string $uri, ?array $params = null, ?array $headers = null): string
    {
        $key = sha1(vsprintf('%s_%s', [$uri, \GuzzleHttp\json_encode([$params, $headers])]));
        $expires = Carbon::now()->addDay();

        return \Cache::remember($key, $expires, function () use ($uri, $params, $headers) {
            return parent::get($uri, $params, $headers);
        });
    }
}
