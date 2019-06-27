<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test',function(){
		return app('common')->readExcelsToGenerateUser();
});


Route::group(['middleware'=>['web'],'namespace'=>'Front'],function(){
	//前端路由
	Route::get('/', 'MainController@index')->name('index');

	//图表统计
	Route::get('chart','MainController@chart');

	Route::group(['prefix'=>'ajax'],function(){
		
		Route::get('name_varify','AjaxController@publishNameVarify');

		Route::get('public_sign','AjaxController@publishSign');

		Route::get('chart','MainController@ajaxChart');

		Route::get('get_login_status/{user_id}','AjaxController@getLoginStatus');

		Route::get('get_time','AjaxController@getEndTime');

		Route::get('get_showstatus','AjaxController@getShowStatus');

	});

});

/**
 *后台
 */
//刷新缓存
Route::post('/clearCache','CommonApiController@clearCache');

//在页面中的URL尽量试用ACTION来避免前缀的干扰
Route::group([ 'prefix' => 'zcjy', 'namespace' => 'Admin'], function () {
	//登录
	Route::get('login', 'LoginController@showLoginForm');
	Route::post('login', 'LoginController@login');
	Route::get('logout', 'LoginController@logout');
});

//后台管理系统
Route::group(['middleware' => ['auth.admin:admin'], 'prefix' => 'zcjy'], function () {
	//说明文档
	Route::get('/doc', 'SettingController@settingDoc');
	//后台首页
	Route::get('/', 'SettingController@setting');
	
    //系统设置
    Route::get('settings/setting', 'SettingController@setting')->name('settings.setting');
    Route::post('settings/setting', 'SettingController@update')->name('settings.setting.update');
    //地图选择
    Route::get('settings/map','SettingController@map');
    //修改密码
	Route::get('setting/edit_pwd','SettingController@edit_pwd')->name('settings.edit_pwd');
    Route::post('setting/edit_pwd/{id}','SettingController@edit_pwd_api')->name('settings.pwd_update');

	//部署操作
	Route::get('helper', 'SettingController@helper')->name('settings.helper');

	Route::resource('candidates', 'CandidateController');
	//更新状态
	Route::post('update_status_waard/{id}','AwardController@updateAwardStatus')->name('awards.upstatus');
	//更新状态
	Route::post('update_show_status_waard/{id}','AwardController@updateAwardShowStatus')->name('awards.upshow');
	
	//更新投票数量
	Route::post('update_num/{award_id}/{candidate_id}','AwardController@updateNumAction')->name('awards.upnum');

	Route::resource('awards', 'AwardController');

	Route::resource('awardCandidates', 'AwardCandidateController');

	Route::resource('voteLogs', 'VoteLogController');

	//取消登录
	Route::post('set_logout/{user_id}', 'ParticipantController@setLogoutAction')->name('participants.setlogout');
	
	Route::resource('participants', 'ParticipantController');
});





