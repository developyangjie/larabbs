<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeixinIdToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->String("weixin_unionid")->unique()->nullable()->after("password");
            $table->String("weixin_openid")->unique()->nullable()->after("weixin_unionid");
            $table->String("password")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("weixin_unionid");
            $table->dropColumn("weixin_openid");
            $table->String("password")->nullable(false)->change();
        });
    }
}
