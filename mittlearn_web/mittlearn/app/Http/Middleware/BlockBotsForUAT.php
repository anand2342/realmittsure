<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockBotsForUAT
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Apply only on UAT or staging
        if (app()->environment(['uat', 'staging','local'])) {
            // Set header (for Google, Bing, etc.)
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

            // Inject meta tag for HTML responses
            if (str_contains($response->headers->get('Content-Type'), 'text/html')) {
                $content = $response->getContent();
                if (strpos($content, '<meta name="robots"') === false) {
                    $content = preg_replace(
                        '/<head(.*?)>/i',
                        '<head$1><meta name="robots" content="noindex, nofollow">',
                        $content,
                        1
                    );
                    $response->setContent($content);
                }
            }
        }

        return $response;
    }
}
