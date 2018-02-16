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
use Illuminate\Support\Facades\Cookie;

trait UserReferralTrait
{
    public function getReferralLink()
    {
        return url('/').'/?ref='.$this->affiliate_id;
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
        } while (self::whereAffiliateId($referral)->first());

        return $referral;
    }
}
