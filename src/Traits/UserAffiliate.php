<?php

/*
 * This file is part of questocat/laravel-referral package.
 *
 * (c) questocat <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Questocat\Referral\Traits;

use Illuminate\Support\Facades\Cookie;
use Questocat\Referral\Referral;
use Ramsey\Uuid\Uuid;

trait UserAffiliate
{
    /**
     * @param string $path
     * @param array  $parameters
     * @param null   $secure
     *
     * @return string
     */
    public function getAffiliateLink($path = '/', $parameters = [], $secure = null)
    {
        $refQuery = config('referral.ref_query');

        return url($path, $parameters, $secure).'/?'.$refQuery.'='.$this->affiliate_id;
    }

    /**
     * @return mixed
     */
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    protected static function bootUserAffiliate()
    {
        static::creating(function ($model) {
            $model->affiliate_id = self::generateAffiliateId();
        });

        static::created(function ($model) {
            $affiliateId = Cookie::get(config('referral.ref_cookie'));
            if ($affiliateId && $referrer = static::whereAffiliateId($affiliateId)->first()) {
                Referral::create(['referrer_id' => $referrer->id, 'referral_id' => $model->id]);
            }
        });
    }

    /**
     * Generate an affiliate id.
     *
     * @return \Ramsey\Uuid\UuidInterface
     *
     * @throws \Exception
     */
    protected static function generateAffiliateId()
    {
        return Uuid::uuid1();
    }
}
