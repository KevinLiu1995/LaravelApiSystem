<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::namespace('Api')->name('api.')->middleware('operate.log')->group(function () {
    // 图片验证码
    Route::middleware('throttle:10,1')->get('captcha', 'CaptchasController@store')->name('captchas.store');

    //后端接口
    Route::prefix('admin')->name('admin.')->group(function () {
        // 登录
        Route::post('/authorization', 'AuthorizationsController@store')->name('authorization.store');

        // 需要登录访问
        Route::middleware('auth:user')->group(function () {
            // 刷新Token
            Route::put('/authorization/current', 'AuthorizationsController@update')->name('authorization.update');
            // 删除Token
            Route::delete('/authorization/current', 'AuthorizationsController@destroy')->name('authorization.destroy');
            // 获取登录用户信息
            Route::get('/authorization/info', 'AuthorizationsController@info')->name('authorization.info');

            // 权限管理
            Route::middleware('permission:system.permission')->name('permission.')->group(function () {
                Route::get('/permission', 'PermissionsController@index')->name('list');
                Route::put('/permission/{permission}', 'PermissionsController@update')->name('update');
            });

            // 角色管理
            Route::middleware('permission:system.role')->name('role.')->group(function (){
                Route::get('/role','RolesController@index')->name('list');
            });

            // 日志管理
            Route::middleware('permission:system.log')->name('log.')->group(function () {
                Route::get('/log/login','LoginlogsController@index')->name('list');
            });

        });

    });

});

//回退路由
Route::fallback(function () {
    return response()->json(['code' => 404, 'msg' => '请检查访问地址或请求方式是否正确'])->setStatusCode(404);
});
