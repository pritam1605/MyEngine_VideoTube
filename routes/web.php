<?php

Route::get('/home', function () {
    return redirect()->route('home');
});

Route::get('/', 'HomeController@index')->name('home');
Route::get('/homepage-videos', 'HomeController@loadHomepageVideos');

//--------------------------------------------AUTH-------------------------------------------
Route::get('/register', [
	'uses' => 'Auth\RegisterController@showRegisterForm',
	'middleware' => ['guest',],
])->name('register');

Route::post('/register', [
	'uses' => 'Auth\RegisterController@register',
	'middleware' => ['guest',],
]);

Route::get('/login', [
	'uses' => 'Auth\LoginController@showLoginForm',
	'middleware' => ['guest',],
])->name('login');

Route::post('/login', [
	'uses' => 'Auth\LoginController@login',
	'middleware' => ['guest',],
]);

Route::get('/login/{service}', [
	'uses' => 'Auth\SocialLoginController@redirectToProvider',
	'middleware' => ['social_login', 'guest',],
]);

Route::get('/login/{service}/callback', [
	'uses' => 'Auth\SocialLoginController@handleProviderCallback',
	'middleware' => ['social_login', 'guest'],
]);

Route::get('/logout', [
	'uses' => 'Auth\LoginController@logout',
	'middleware' => ['auth',],
])->name('logout');

Route::get('/account/activate/{token}', [
	'uses' => 'Auth\AccountActivationController@activateAccount',
	'middleware' => ['guest',],
])->name('activate.account');

Route::get('/resend/account-activation-link', [
	'uses' => 'Auth\AccountActivationController@resendActivationLink',
	'middleware' => ['guest',],
])->name('resend.activate.account');

// ----------------------------------------PASSWORD-------------------------------------------

//--------------------Password Forgot---------------------------------------------------------
Route::get('/password/forgot', [
	'uses' => 'PasswordController@showForgotPasswordPage',
	'middleware' => ['guest',],
])->name('password.forgot');

Route::post('/password/forgot', [
	'uses' => 'PasswordController@sendResetPasswordLink',
	'middleware' => ['guest',],
]);

Route::get('/password/forgot/reset/{token}', [
	'uses' => 'PasswordController@showResetPasswordPage',
	'middleware' => ['guest',],
])->name('password.reset');

Route::put('/password/forgot/reset/{token}', [
	'uses' => 'PasswordController@resetPassword',
	'middleware' => ['guest',],
]);

// --------------------Password Change---------------------------------------------------------

Route::get('/password/change', [
	'uses' => 'PasswordController@showChangePasswordPage',
	'middleware' => ['auth',],
])->name('password.change');

Route::put('/password/change', [
	'uses' => 'PasswordController@changePassword',
	'middleware' => ['auth',],
]);

Route::get('/account/activate/{token}', [
	'uses' => 'Auth\AccountActivationController@activateAccount',
	'middleware' => ['guest',],
])->name('activate.account');

Route::post('/password/check', [
	'uses' => 'PasswordController@checkCurrentPassword',
	'middleware'=> ['auth',],
])->name('check.current.password');


// --------------------Passwordless Entry---------------------------------------------------------
Route::get('/passwordless-entry', [
	'uses' => 'Auth\PasswordlessEntryController@index',
	'middleware' => ['guest',],
])->name('passwordless.entry.page');

Route::post('/passwordless-entry', [
	'uses' => 'Auth\PasswordlessEntryController@sendMagicLink',
	'middleware' => ['guest',],
]);

Route::get('/passwordless-entry/{token}', [
	'uses' => 'Auth\PasswordlessEntryController@logInMagically',
	'middleware' => ['guest',],
])->name('passwordless.entry');


// -------------------Payment------------------------------------------------------------------------


Route::get('/account/connect/complete', [
	'uses' => 'MarketplaceConnectController@store',
	'middleware' => ['auth', 'has.marketplace',],
])->name('stripe.account.connect');


// --------------------------------------------------------------------------------------------------
Route::get('/videos/{video}', 'VideoController@show')->name('video.show');
Route::get('/videos/{video}/show', 'VideoController@showVideo');

Route::post('/videos/{video}/views', 'VideoViewController@store')->name('video.view');
Route::get('/videos/{video}/comments', 'VideoCommentController@index')->name('video.comments');

Route::get('/search', 'SearchController@index')->name('search.channer.or.video');
Route::get('/search-list', 'SearchController@getSearchList');

Route::get('/channels/{channel}', 'ChannelController@index')->name('channel.view');
Route::get('/channels/{channel}/channel-data', 'ChannelController@getChannelVideos');

Route::get('/videos/{video}/vote', 'VideoVoteController@index')->name('video.vote');

Route::post('/webhook/encoding', 'WebhookEncodingController@handle')->name('webhook');

Route::get('/channels/{channel}/subscription', 'ChannelSubscriptionController@index')->name('channel.subscription');

//-------------------Channel----------------------------------
Route::group(['middleware' => ['auth',]], function() {
	Route::get('/create-channel', 'ChannelController@showCreateChannelPage')->name('show.create.channel.page');
	Route::post('/create-channel', 'ChannelController@store');

	Route::get('/channels/{channel}/edit-channel', 'ChannelSettingController@editChannel');

	Route::post('/channels/{channel}/edit', 'ChannelSettingController@update');
	Route::delete('/channels/{channel}', 'ChannelSettingController@delete');

	Route::get('{user}/channels', 'ChannelController@showUserChannelsPage')->name('all.channels');
	Route::get('/channels/user/{user}/all-user-channels', 'ChannelController@getAllUserChannels');

	Route::post('/{channel}/purchase', 'PurchaseController@purchaseChannel')->name('channel.purchase');

	Route::get('/upload', 'VideoUploadController@index')->name('video.upload');
	Route::post('/upload', 'VideoUploadController@store');

	Route::get('{user}/videos', 'VideoController@index')->name('all.videos');
	Route::get('/videos/user/{user}/all-user-videos', 'VideoController@allUserVideos');

	Route::post('/videos', 'VideoController@store')->name('video.store');
	Route::put('/videos/{video}', 'VideoController@update')->name('video.update');
	Route::delete('/videos/{video}', 'VideoController@delete')->name('video.delete');

	Route::post('/videos/{video}/vote', 'VideoVoteController@store');

	Route::post('/videos/{video}/comments', 'VideoCommentController@store');
	Route::delete('/videos/{video}/comments/{comment}', 'VideoCommentController@delete');

	Route::post('/subscriptions/{channel}', 'ChannelSubscriptionController@create')->name('create.subscription');
	Route::delete('/subscriptions/{channel}', 'ChannelSubscriptionController@delete');

	Route::get('/{user}/profile', 'UserProfileController@index')->name('user.profile');
	Route::get('/{user}/get-profile', 'UserProfileController@getUserProfile');
	Route::post('/{user}/profile', 'UserProfileController@update');
	Route::get('/{user}/purchases', 'UserPurchasesController@index')->name('user.purchases');
	Route::get('/{user}/get-my-purchases-channel-list', 'UserPurchasesController@getUserPurchasedChannelList');

	Route::get('/{user}/subscriptions', 'UserSubscriptionController@index')->name('user.subscriptions');
	Route::get('/{user}/get-my-subscribed-channel-list', 'UserSubscriptionController@getUserSubscribedChannelList');


	Route::get('/{user}/get-info', 'UserProfileController@getUserInfo')->name('get.user.info');
	Route::get('/{channel}/get-user-info-from-channel', 'ChannelController@getUserInfo');
});

Route::get('/{user}', 'UserProfileController@showDashboard')->name('user.dashboard');
Route::get('/{user}/user-channels', 'UserProfileController@getDashboardData');