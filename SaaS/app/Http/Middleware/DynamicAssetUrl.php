<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class DynamicAssetUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $scheme = $request->header('X-Forwarded-Proto', $request->getScheme());
        $host = $request->getHttpHost();

        URL::forceRootUrl($scheme . '://' . $host);

        if ($scheme === 'https') {
            URL::forceScheme('https');
        }

        return $next($request);
    }
}
