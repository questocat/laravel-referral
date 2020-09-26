<?php

/*
 * This file is part of questocat/laravel-referral package.
 *
 * (c) questocat <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Questocat\Referral\Traits\UserReferral;

class AddReferralToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::beginTransaction();
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->string('referred_by')->nullable()->index();
                $table->string('affiliate_id')->nullable()->unique();
            });

            /** @var \Illuminate\Database\Eloquent\Collection|UserReferral[] $users */
            $users = config('referral.user_model')::all();
            foreach ($users as $user) {
                $user->affiliate_id = $user::generateReferral();
                $user->save();
            }

            Schema::table('users', function (Blueprint $table) {
                $table->string('affiliate_id')->nullable(false)->change();
            });

            DB::commit();
        }
        catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('referred_by');
            $table->dropColumn('affiliate_id');
        });
    }
}
