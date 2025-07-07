<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Arsip;
use Carbon\Carbon;

class CheckArsipRetentionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only check for retention on web requests, not API requests
        if ($request->is('api/*')) {
            return $next($request);
        }

        // Only check every 10 minutes to avoid performance issues
        $cacheKey = 'arsip_retention_check_' . now()->format('Y-m-d-H-i');
        $lastCheck = cache()->get($cacheKey);

        if (!$lastCheck) {
            $this->checkAndMoveExpiredArsips();
            // Cache for 10 minutes
            cache()->put($cacheKey, true, 600);
        }

        return $next($request);
    }

    /**
     * Check for expired arsips and automatically move them to JRE
     */
    private function checkAndMoveExpiredArsips()
    {
        $arsips = Arsip::where('is_archived_to_jre', false)
                      ->whereNotNull('retention_date')
                      ->whereDate('retention_date', '<=', Carbon::now())
                      ->get();

        foreach ($arsips as $arsip) {
            if ($arsip->shouldMoveToJre()) {
                // Mark retention notification first
                $arsip->has_retention_notification = true;
                $arsip->save();

                // Then automatically move to JRE
                $arsip->moveToJre();
            }
        }
    }
}
