<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1',[
   "namespace"=>'App\Http\Controllers\Api',
    "middleware"=>["bindings","change-locale",'serializer:array']
], function($api){
    $api->group(['middleware' => 'api.throttle', 'limit' => config('api.rate_limits.sign.limit'), 'expires' => config('api.rate_limits.sign.expires')],function($api){
        $api->post("verificationCodes",'VerificationCodesController@store')->name("api.verificationcodes.store");

        $api->post("users","UserController@store")->name("api.user.store");

        $api->post("captcha","CaptchaController@store")->name("api.captcha.store");

        $api->post("socials/{type}/authorizations","AuthorizationsController@socialStore")->name("api.socials.authorizations.store");

        $api->post("authorizations","AuthorizationsController@store")->name("api.authorizations.store");

        //passport
        $api->post("authorizations/passport","AuthorizationsController@passportStore")->name("api.authorizations.passportStore");

        $api->post("authorizations/logout","AuthorizationsController@logout")->name("api.authorizations.logout");

        $api->post("authorizations/refresh","AuthorizationsController@refresh")->name("api.authorizations.refresh");

        $api->get("categories","CategoriesController@index")->name("api.categories.index");

        $api->get("passportLogin","AuthorizationsController@passportLogin")->name("api.authorization.passportLogin");

        $api->post("weapp/authorizations","AuthorizationsController@weappStore")->name("api.authorization.weappstore");

        $api->post("weapp/users","UserController@weappStore")->name("api.user.weappstore");

        $api->get("topics","TopicsController@index")->name("api.topics.index");

        $api->get("topics/{topic}","TopicsController@show")->name("api.topics.show");

        $api->get("users/{user}/topics","TopicsController@userIndex")->name("api.topics.index");

        $api->get("books","BooksController@index")->name("api.books.index");

        // 用户详情
        $api->get('users/{user}', 'UserController@show')->name('api.users.show');

        $api->get("search","TopicsController@search")->name("api.topics.search");

        $api->group(['middleware' => 'api.auth'],function($api){

            $api->delete("topics/{topic}","TopicsController@destroy")->name("api.topics.destroy");

            $api->get("user","UserController@me")->name("api.user.show");

            $api->post("image","ImagesController@store")->name("api.image.store");

            $api->patch("users","UserController@update")->name("api.user.update");

            $api->put("users","UserController@update")->name("api.user.update");

            $api->post("topics","TopicsController@store")->name("api.topics.store");

            $api->patch("topics/{topic}","TopicsController@update")->name("api.topics.update");

            $api->post("topics/{topic}/replies","RepliesController@store")->name("api.topics.replies.store");

            $api->delete("topics/{topic}/replies/{reply}","RepliesController@destroy")->name("api.topics.replies.destroy");

            $api->get("topics/{topic}/replies","RepliesController@show")->name("api.topics.replies.show");

            $api->get("users/{user}/replies","RepliesController@userIndex")->name("api.users.replies.userIndex");

            // 通知列表
            $api->get('user/notifications', 'NotificationsController@index')
                ->name('api.user.notifications.index');
            // 通知统计
            $api->get('user/notifications/stats', 'NotificationsController@stats')
                ->name('api.user.notifications.stats');

            // 标记消息通知为已读
            $api->patch('user/read/notifications', 'NotificationsController@read')
                ->name('api.user.notifications.read');

            // 标记消息通知为已读
            $api->put('user/read/notifications', 'NotificationsController@read')
                ->name('api.user.notifications.read.put');

            // 当前登录用户权限
            $api->get('user/permissions', 'PermissionsController@index')
                ->name('api.user.permissions.index');
        });
    });
});

$api->version("v2",function($api){
    $api->get("version",function(){
        return response("this is version v2");
    });
});

