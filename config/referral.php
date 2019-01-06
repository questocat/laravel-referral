<?php

/*
 * This file is part of questocat/laravel-referral package.
 *
 * (c) questocat <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    /*
     * Model class name of users.
     */
    'user_model' => 'App\User',

    /*
     * It is used to identify that the link is an affiliate link.
     */
    'ref_query' => 'ref',

    /*
     * This cookie stores the affiliate ID.
     */
    'ref_cookie' => 'referred_by',

    /*
     * The lifetime in number of minutes for this affiliate ID.
     * If zero (the default), the affiliate id never expires.
     */
    'lifetime_minutes' => 0,

    /*
     * Allows you to credit the last affiliate who referred the customer.
     */
    'credit_last_referrer' => false,
];
