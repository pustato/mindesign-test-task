<?php

namespace App\Http\Transports;

interface TransportContract
{
    public function get(string $uri, ?array $params = null, ?array $headers = null): string;
}
