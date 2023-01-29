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

/**
 * Public Routes
 */
Route::get('/', 'CmsController@landing')->name('landing');
Route::get('/about', 'CmsController@about');
//Route::get('/faq', 'CmsController@faq');
Route::get('/legal', 'CmsController@legal');
Route::get('/policy', 'CmsController@policy');
Route::get('/terms', 'CmsController@terms');
Route::get('/cookies', 'CmsController@cookies')->name('cookies_details');
Route::get('/rules', 'CmsController@rules');
//Route::get('/contact', 'CmsController@contact');
Route::get('/report_bug', 'BugReportController@show')->name('report_bug');
Route::get('/contact', 'ContactUsController@show')->name('show_contact_us');
Route::get('/faq', 'FAQController@showFaqPage');
Route::get('/showAnswerPage/{slug}','FAQController@showVideoPage')->name('FaqAnswerVideo');
Route::group(['prefix' => 'api'], function(){
    Route::post('/faq/search', 'API\FAQSearchController@index')->name('faq_search');
    Route::post('/save_bug','BugReportController@store')->name('save_bug');
    Route::post('/send_message','ContactUsController@sendMessage')->name('send_message');

});

Route::group(['prefix' => 'admin'], function(){
    Route::get('/login', 'Admin\AuthController@show')->name('admin_login');
    Route::post('/login', 'Admin\AuthController@authenticate')->name('admin_authenticate');
    Route::middleware(['auth', 'admin_filter'])->group(function(){
        Route::get('/post-categories', 'Admin\PostCategoryController@show')->name('admin_post_categories');
        Route::get('/connection-categories', 'Admin\ConnectionCategoryController@show')->name('admin_connection_categories');
        Route::get('/post-categories/edit/{id}', 'Admin\PostCategoryController@showEditPostCategoryFrom')->name('admin_edit_post_categories');
        Route::get('/connection-categories/edit/{id}', 'Admin\ConnectionCategoryController@showEditConnectionCategoryFrom')->name('admin_edit_connection_categories');
        Route::post('/post-categories/edit', 'Admin\PostCategoryController@editPostCategory')->name('admin_edit_post_categories_put');
        Route::post('/connection-categories/edit', 'Admin\ConnectionCategoryController@editConnectionCategory')->name('admin_edit_connection_categories_put');
        Route::delete('/post-categories', 'Admin\PostCategoryController@delete')->name('admin_delete_post_categories');
        Route::delete('/connection-categories', 'Admin\ConnectionCategoryController@delete')->name('admin_delete_connection_categories');
        Route::get('/post-categories/add', 'Admin\PostCategoryController@showPostCategoryForm')->name('admin_post_categories_form');
        Route::get('/connection-categories/add', 'Admin\ConnectionCategoryController@showConnectionCategoryForm')->name('admin_connection_categories_form');
        Route::post('/post-categories/add', 'Admin\PostCategoryController@store')->name('admin_add_post_categories');
        Route::post('/connection-categories/add', 'Admin\ConnectionCategoryController@store')->name('admin_add_connection_categories');
        Route::post('/logout', 'Admin\AuthController@logout')->name('admin_logout');
    });
});


/**
 * Auth Routes
 */
Route::get('/login', 'Auth\LoginController@showLoginForm');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('login/google', 'Auth\LoginController@redirectToProvider')->name('google_login');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('passwords/email', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('passwords/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('forgotPassword');
Route::get('passwords/email/info', 'Auth\ForgotPasswordController@showResetInfo');

Route::group(['prefix' => 'register', 'namespace' => 'Auth'], function() {
    Route::get('/', 'RegisterController@showRegistrationForm');
    Route::post('/', 'RegisterController@register')->name('register');
    Route::post('/{step}', 'RegisterController@partialValidator')
        ->where('step', '(email|name|birthdate|password)');

    Route::get('/terms/decline/{token?}', 'TermsController@showDeclineForm');
    Route::get('/terms/{token?}', 'TermsController@showTermsForm');
    Route::post('/terms/decline/{token?}', 'TermsController@decline');
    Route::post('/terms/{token?}', 'TermsController@accept');

    Route::get('/cookies/{token?}', 'CookiesController@showCookiesForm');
    Route::post('/cookies/{token?}', 'CookiesController@acceptCookies');
});

Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');
Route::get('/checkemail', 'UserController@checkEmail');

/**
 * Private routes
 */
Route::group(['middleware' => 'auth'], function() {
    // own users route 
    Route::get('/profile', 'FeedController@profile');
    Route::get('/profile/map', 'FeedController@profileMap');
    Route::get('/profile/apiFeed', 'FeedController@apiFeed');
    Route::get('/timeline', 'FeedController@timeline');
    Route::get('/map', 'TimelineMapController@index');
    Route::get('/personal-map', 'PersonalMapController@index')->name('personal-map');
    Route::get('/horizontal-timeline', 'HorizontalTimelineController@index')->name('horizontal-timeline');
    // other users routes
    Route::get('/codex/{username}', 'UserController@user')->name('codex');
    Route::get('/codex/{username}/map', 'UserController@userMap');
    Route::get('/codex/{username}/timeline', 'UserController@userTimeline');
    Route::get('/activities', 'NotificationController@index');

// --------------notification count rout-------------------
    Route::get('/countNot', 'NotificationController@countOfNotification');


    Route::group(['prefix' => 'setup'], function() {
        Route::get('/', 'SetupController@index');
        Route::get('/contacts', 'SetupController@contacts');
        Route::get('/contacts/search', 'SetupController@contactsSearch');
        Route::get('/contacts/results', 'SetupController@contactsResults');
        Route::get('/moment', 'SetupController@moment');
    });

    Route::get('/search', 'SearchController@index')->name('search');
    Route::get('/search/filters', 'SearchController@filters')->name('search.filters');

    Route::group(['prefix' => 'settings'], function() {
        Route::get('/', 'SettingsController@index')->name('settings');
        Route::get('/profile', 'SettingsController@profile');
        Route::get('/account', 'SettingsController@account');
        Route::get('/account/email', 'SettingsController@email');
        Route::get('/account/password', 'SettingsController@password');
        Route::get('/notifications', 'SettingsController@notifications');
        Route::get('/access-data', 'AccessUserDataController@show')->name('access_data');
        Route::get('/access-data/moments', 'AccessUserDataController@showMoments')->name('moments');
        Route::get('/access-data/comments', 'AccessUserDataController@showComments')->name('comments');
        Route::get('/access-data/kudos', 'AccessUserDataController@showKudos')->name('kudos');
        Route::get('/access-data/connections', 'AccessUserDataController@showConnections')->name('connections');
        Route::get('/data/download', 'DownloadUserDataController@show')->name('download_data');
        Route::post('/data/download', 'DownloadUserDataController@printPDF')->name('download_data_categories');
        Route::get('/data/delete', 'DeleteUserDataController@show')->name('delete_account_data');
        Route::post('/data/delete/account', 'DeleteUserDataController@delete')->name('delete_user_account_data');
        Route::get('/data/delete/account/confirm', 'DeleteUserDataController@showConfirmation')->name('delete_user_account_data_confirm');
    });
});

// Legacy
//Route::get('/report/{username}', 'UserController@report');
//Route::post('/connect/{username}', 'UserController@connect');
//Route::get('/connect/accept/{connectionId}', 'UserController@acceptConnection');
//Route::get('/connect/decline/{connectionId}', 'UserController@declineConnection');
//Route::post('/disconnect', 'UserController@disconnect');
//Route::get('/mute/{username}', 'UserController@mute');
//Route::get('/unmute/{username}', 'UserController@unmute');
//Route::get('/notification/delete/{id}', 'NotificationController@destroy');

/**
 * API Routes
 */
Route::group(['prefix' => 'api', 'middleware' => 'auth'], function(){

    Route::group(['prefix' => 'connections', 'namespace' => 'API'], function() {
        Route::get('/{user}', 'ConnectionsController@connectionsList');
        Route::get('/categories/connections', 'ConnectionsController@getConnectionsByConnectionCategories');
        Route::get('/categories/all/{user}', 'ConnectionsController@connectionCategoryList');
        Route::post('/categories/send', 'ConnectionsController@connectionCategoryStore');
        Route::delete('/{user}', 'ConnectionsController@removeConnection');
        Route::post('/', 'ConnectionsController@sendInvitation');
    });
    Route::group(['prefix' => 'requests', 'namespace' => 'API'], function() {
        Route::get('/{user}', 'RequestsController@requestsList');
        Route::post('/{connection}', 'RequestsController@approveRequest');
        Route::delete('/{connection}', 'RequestsController@declineRequest');
    });

    Route::get('map', 'FeedController@apiMap');
    Route::get('mapPublic', 'FeedController@apiMapPublic');
    Route::get('timeline/feed', 'FeedController@apiFeed');
    Route::get('post-categories', 'PostCategoryController@index');
    Route::get('timeline/get_user_post/{username}', 'FeedController@apiTimelineUser');
    Route::get('/profile/userData/{param}', 'FeedController@userData')->name('user_post');
    Route::get('/timeline/connection/posts/{connectionId}', 'TimelineMapController@getConnectionPosts');
    Route::get('timeline/{user}', 'FeedController@apiTimeline');
    Route::get('timeline/filter/connection/posts', 'ConnectionPostFilterController@filterPostsByConnections');

    Route::post('/timeline', 'FeedController@post');
    Route::delete('/timeline/{post}', 'FeedController@remove');
    Route::put('/timeline/{post}', 'FeedController@update');
    Route::post('/timeline/{post}/comment', 'CommentController@create');
    Route::post('/timeline/{post}/kudos', 'KudosController@toggle');
    Route::post('/profile/mark_as_viewed', 'FeedController@markAsViewed');
    Route::post('/profile/mark_all_as_viewed', 'FeedController@markAllAsViewed');
    Route::get('/timeline/post-categories', 'PostCategoryController@index');
    Route::get('user', function() {
       return [
           'message' => '',
           'status' => 'success',
           'data' => request()->user()
       ];
    });

    Route::get('/userPosts/{userId}','FeedController@usersPost');

//    Route::post('/save_bug','BugReportController@store')->name('save_bug');

    Route::get('/search/places', 'API\SearchController@places');

    Route::group(['prefix' => 'settings', 'namespace' => 'API'], function() {
        Route::get('/profile', 'SettingsController@getProfile');
        Route::put('/profile', 'SettingsController@updateProfile');
        Route::put('/avatar', 'SettingsController@updateAvatar');
        Route::put('/email', 'SettingsController@updateEmail');
        Route::put('/password', 'SettingsController@updatePassword');
    });

    Route::group(['prefix' => 'cookies', 'namespace' => 'API'], function() {
        Route::post('/login/accept', 'CookieController@acceptCookies');
    });
});
