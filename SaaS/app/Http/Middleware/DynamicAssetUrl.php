<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
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

        // When behind a proxy (ngrok), disable Vite HMR so @vite() falls back
        // to the prebuilt manifest (public/build/). This way assets load from
        // the correct host instead of localhost:5173.
        if ($request->header('X-Forwarded-Proto') || $request->header('X-Forwarded-Host')) {
            Vite::useHotFile(storage_path('framework/cache/.vite_hot_disabled'));
        }

        return $next($request);
    }
}
