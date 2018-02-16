<?php

/*
 * This file is part of emanci/laravel-referral package.
 *
 * (c) emanci <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Emanci\Referral\Traits;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cookie;

trait UserReferralTrait
{
    public function getReferralLink()
    {
        return url('/').'/?ref='.$this->affiliate_id;
    }

    public static function scopeReferralExists(Builder $query, $referral)
    {
        return $query->whereAffiliateId($referral)->exists();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (User $model) {
            $referredBy = Cookie::get('referral');
            $model->referred_by = $referredBy;
            $model->affiliate_id = self::generateReferral();
        });
    }

    protected static function generateReferral()
    {
        $length = config('referral.referral_length', 5);

        do {
            $referral = str_random($length);
        } while (static::referralExists($referral));

        return $referral;
    }
}
