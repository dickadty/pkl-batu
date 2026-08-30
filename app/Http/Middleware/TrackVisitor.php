<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{

    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if ($this->shouldTrack($request)) {

            $now = now();
            $visitorHash = hash_hmac(
                'sha256',
                ($request->ip() ?? 'unknown')
                    . '|'
                    . ($request->userAgent() ?? 'unknown'),
                config('app.key')
            );

            $visitDate = $now->toDateString();

            $visit = Visitor::firstOrCreate(
                [
                    'visitor_hash' => $visitorHash,
                    'visit_date' => $visitDate,
                ],
                [
                    'hits' => 0,
                    'first_seen_at' => $now,
                    'last_seen_at' => $now,
                    'last_path' => $this->getPath($request),
                ]
            );
            $visit->increment('hits');
            $visit->update([
                'last_seen_at' => $now,
                'last_path' => $this->getPath($request),
            ]);
        }

        return $next($request);
    }

    private function shouldTrack(Request $request): bool
    {
        if (!$request->isMethod('GET')) {
            return false;
        }

        if ($request->is('admin/*')) {
            return false;
        }

        if (
            $request->is('admin')
            || $request->is('login')
            || $request->is('logout')
        ) {
            return false;
        }

        if (
            $request->is('css/*')
            || $request->is('js/*')
            || $request->is('images/*')
            || $request->is('img/*')
            || $request->is('storage/*')
            || $request->is('assets/*')
            || $request->is('build/*')
            || $request->is('favicon.ico')
        ) {
            return false;
        }

        return true;
    }

    private function getPath(Request $request): string
    {
        $path = $request->path();

        if ($path === '/') {
            return '/';
        }

        return '/' . ltrim($path, '/');
    }
}
