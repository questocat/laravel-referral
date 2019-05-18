<?php

/*
 * This file is part of noelbradford/laravel-referral package.
 *
 * (c) Noel Bradford 2019 
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * This is based on the questocat/laravel-referral package.
 */

return [
    /*
     * Model class name of users.
     */
    'user_model' => 'App\User',

    /*
     * The length of referral code.
     */
    'referral_length' => 5,
    
    /*
    * Setup the endpoint for the referral code
    */
    
    'referral_url' => 'register?referral=',
];
