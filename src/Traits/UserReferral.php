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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cookie;
use Ramsey\Uuid\Uuid;

trait UserReferral
{
    public function Referrer()
    {
        return static::getReferrer($this->referred_by);
    }
    public function getReferralLink()
    {
        return url('/') . '/?ref=' . $this->affiliate_id;
    }

    public static function scopeReferralExists(Builder $query, $referral)
    {
        // No longuer need this function, keeping if for lolz.
        return $query->whereAffiliateId($referral)->exists();
    }
    public function scopeGetReferrer(Builder $query, $referral)
    {
        $referrer = $query->whereAffiliateId($referral)->get();
        if($referrer){
            return $referrer[0];
        }
        return null;
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($referredBy = Cookie::get('referral')) {
                $model->referred_by = $referredBy;
            }
            $model->affiliate_id = self::generateReferral();
        });
    }

    protected static function generateReferral()
    {
        /*
            str_random (Str::random()) tries to use openssl_random_pseudo_bytes 
            which is a pseudo random number generator optimized for cryptography, not uniqueness !
            the following line generates a version 1 (time-based) UUID object. 
            Uniqueness is now guaranteed !
         */
        return Uuid::uuid1();
    }
}
