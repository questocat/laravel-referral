<?php

/*
 * This file is part of questocat/laravel-referral package.
 *
 * (c) questocat <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Mockery as m;
use Questocat\Referral\Http\Middleware\AffiliateTracking;
use Questocat\Referral\Referral;
use function route;
use Tests\Stubs\ApplicationStub;
use Tests\Stubs\UserStub;

class AffiliateTrackingTest extends TestCase
{
    public function testAffiliateLink()
    {
        $user = UserStub::find(1);
        $url = url('/register/');

        $this->assertEquals($url.'?ref='.$user->affiliate_id, $user->getAffiliateLink($url));
    }

    public function testGetAffiliateIdFromCookieAndWithoutCookie()
    {
        $user = UserStub::find(1);
        $request = m::mock(Request::class);
        $cookieName = config('referral.ref_cookie');

        $request->shouldReceive('query')->once()->andReturn($user->affiliate_id);
        $this->mockingCookieFacade($request, $cookieName, null);

        $middleware = new AffiliateTracking();
        $response = $middleware->handle($request, function ($request) {
            return response('');
        });

        $cookie = $response->headers->getCookies()[0];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($cookieName, $cookie->getName());
        $this->assertEquals($user->affiliate_id, $cookie->getValue());
    }

    public function testGetAffiliateIdFromCookieAndWithCookie()
    {
        $user = UserStub::find(1);
        $request = m::mock(Request::class);
        $cookieName = config('referral.ref_cookie');

        $request->shouldReceive('query')->once()->andReturn($user->affiliate_id);
        $this->mockingCookieFacade($request, $cookieName, $user->affiliate_id);

        $middleware = new AffiliateTracking();
        $response = $middleware->handle($request, function ($request) {
            return response('');
        });

        $cookie = $response->headers->getCookies();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty($cookie);
    }

    public function testCreateReferral()
    {
        $user = UserStub::find(1);
        $cookieName = config('referral.ref_cookie');
        $request = m::mock(Request::class);
        $this->mockingCookieFacade($request, $cookieName, $user->affiliate_id);

        $referral = UserStub::create([
            'name' => 'cat',
            'email' => 'cat@test.com',
            'password' => bcrypt('secret'),
        ]);

        $result = Referral::whereReferrerId($user->id)->first();

        $this->assertEquals($referral->id, $result->referral_id);
        $this->assertEquals($user->id, $result->referrer_id);
    }

    /**
     * @param Request $request
     * @param         $cookieName
     * @param         $value
     * @param null    $default
     */
    protected function mockingCookieFacade(Request $request, $cookieName, $value, $default = null)
    {
        $request->shouldReceive('cookie')
            ->times(1)
            ->withArgs([$cookieName, $default])
            ->andReturn($value);
        $app = new ApplicationStub();
        $app->setAttributes(['request' => $request]);
        Cookie::setFacadeApplication($app);
    }
}
