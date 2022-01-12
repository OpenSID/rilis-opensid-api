<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Facades\Http;

class HandlePremiumMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $stack = HandlerStack::create();
        $stack->push(new StackCache(app('cache.store'), 86400));

        $response = Http::withOptions([
            'handler' => $stack,
            'base_uri' => config('services.layanan.domain'),
        ])
        ->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])
        ->withToken(config('services.layanan.secret'))
        ->get('api/v1/pelanggan/domain', ['kode_desa' => config('services.layanan.key')]);

        if ($response->clientError()) {
            return response()->json($response->json(), $response->status());
        }

        return $next($request);
    }
}
