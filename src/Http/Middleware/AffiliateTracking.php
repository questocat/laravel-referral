<?php

/*
 * This file is part of questocat/laravel-referral package.
 *
 * (c) questocat <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Questocat\Referral\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class AffiliateTracking
{
    /**
     * @param         $request
     * @param Closure $next
     *
     * @return $this|mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $ref = $request->query(config('referral.ref_query'));

        if ($ref && $this->hasStoreAffiliateId($ref)) {
            $name = config('referral.ref_cookie');
            $minutes = config('referral.lifetime_minutes', 0);

            $cookie = $this->storeAffiliateId($name, $ref, $minutes);

            return $response->withCookie($cookie);
        }

        return $response;
    }

    /**
     * @param $ref
     *
     * @return bool
     */
    public function hasStoreAffiliateId($ref)
    {
        if ($affiliateId = Cookie::get(config('referral.ref_cookie'))) {
            if (0 === strcmp($affiliateId, $ref) || false === config('referral.credit_last_referrer')) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param     $name
     * @param     $ref
     * @param int $lifetime
     *
     * @return \Illuminate\Cookie\CookieJar|\Symfony\Component\HttpFoundation\Cookie
     */
    protected function storeAffiliateId($name, $ref, $lifetime = 0)
    {
        if ($lifetime > 0) {
            return cookie($name, $ref, $lifetime);
        }

        return cookie()->forever($name, $ref);
    }
}
