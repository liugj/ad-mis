<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/crop', function()
        {
            $thumb = new Imagick();
            $thumb->readImage(public_path(). '/images/0/6iCZ65Qvph1435500565.6478.jpg');
            $thumb->resizeImage(320,240,Imagick::FILTER_LANCZOS,1);
            $thumb->writeImage(public_path(). '/images/0/6iCZ65Qvph1435500565.6478.320x240.jpg');

        });
Route::get('/', function () {
    return Auth:: user()->check()?  redirect('/home') :  view('welcome');
        });
Route::get('/admin', function () {
    return Auth:: admin()->check()?  redirect('/admin/home') :  view('admin.welcome');
});

Route::group(['namespace'=>'Auth'], function() {
        Route::get('login',  'AuthController@getLogin');
        Route::post('login', 'AuthController@postLogin');
        Route::get('logout', 'AuthController@getLogout');

        Route::post('register', 'AuthController@postRegister');
        });

Route::group(['middleware' => 'auth'], function () {
        Route::get('change_password',  ['as'=>'user', 'uses'=>'UserController@getChangePassword']);
        Route::post('change_password',  ['as'=>'user', 'uses'=>'UserController@postChangePassword']);

        Route::get('change',  ['as'=>'user', 'uses'=>'UserController@getChange']);
        Route::post('change',  ['as'=>'user', 'uses'=>'UserController@postChange']);


        Route::get('/region/lists',  [
            'as' => 'idea', 'uses' => 'RegionController@lists'
            ]);
        Route::get('/idea/create',  [
            'as' => 'idea', 'uses' => 'IdeaController@create'
            ]);
        Route::get('/idea',  [
            'as' => 'idea', 'uses' => 'IdeaController@index'
            ]);
        Route::post('/idea/update/{id}',  [
            'as' => 'idea', 'uses' => 'IdeaController@update'
            ])->where('id','[0-9]+');
        Route::get('/idea/show/{id}',  [
            'as' => 'idea', 'uses' => 'IdeaController@show'
            ])->where('id','[0-9]+');
        Route::get('/idea/edit/{id}',  [
            'as' => 'idea', 'uses' => 'IdeaController@edit'
            ])->where('id','[0-9]+');
        Route::post('/idea/destroy/{id}',  [
                'as' => 'idea', 'uses' => 'IdeaController@destroy'
                ])->where('id','[0-9]+');
        Route::post('/idea/store',  [
                'as' => 'idea', 'uses' => 'IdeaController@store'
                ]);
        Route :: post('idea/upload', ['as'=>'idea', 'uses'=>'IdeaController@upload']);
        Route :: post('idea/crop', ['as'=>'idea', 'uses'=>'IdeaController@crop']);
        Route :: get('idea/preview/{id}', ['as'=>'idea', 'uses'=>'IdeaController@preview'])
            ->where('id', '[0-9]+');

        Route::get('/home', ['as'=>'home', 'uses'=>'HomeController@index']);
        Route::get('/home/chart', ['as'=>'home', 'uses'=>'HomeController@chart']);
        Route::get('/profile', ['as'=>'home', 'uses'=>'UserController@profile']);
        Route::get('/report', ['as'=>'report', 'uses'=>'ReportController@index']);
        Route::get('/report/lists', ['as'=>'report', 'uses'=>'ReportController@lists']);
        Route::get('/report/summary', ['as'=>'report', 'uses'=>'ReportController@summary']);

        Route::get('/plan', ['as'=>'plan', 'uses'=>'PlanController@index']);
        Route::get('/plan/show/{id}', ['as'=>'plan', 'uses'=>'PlanController@show'])->where('id','[0-9]+');
        Route::post('/plan/destroy/{id}', ['as'=>'plan', 'uses'=>'PlanController@destroy'])->where('id','[0-9]+');
        Route::get('/plan/lists', ['as'=>'plan', 'uses'=>'PlanController@lists']);
        Route::post('/plan/store', ['as'=>'plan', 'uses'=>'PlanController@store']);
        Route::get('/plan/create', ['as'=>'plan', 'uses'=>'PlanController@create']);
        Route::get('/plan/edit/{id}', ['as'=>'plan', 'uses'=>'PlanController@edit'])->where('id','[1-9][0-9]*');
        Route::post('/plan/update/{id}', ['as'=>'plan', 'uses'=>'PlanController@update'])->where('id','[1-9][0-9]*');
}
        );
Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function() {
      Route::group(['namespace'=>'Auth'], function() {
          Route::post('login', 'AuthController@postLogin');
          Route::get('logout', 'AuthController@getLogout');
        });

    Route::group(['middleware' => 'admin'], function() {
        Route::get('/home', ['as'=>'adminHome', 'uses'=>'HomeController@index']); 

        Route::get('/users', ['as'=>'users', 'uses'=>'UserController@index']); 
        Route::get('/user/lists', ['as'=>'users', 'uses'=>'UserController@lists']); 
        Route::get('/ideas', ['as'=>'users', 'uses'=>'IdeaController@index']); 
        Route::get('/idea/lists', ['as'=>'users', 'uses'=>'IdeaController@lists']); 
        Route::post('/idea/destroy/{id}', ['as'=>'users', 'uses'=>'IdeaController@destroy']); 

        Route::get('/recharge/create', ['as'=>'recharge', 'uses'=>'RechargeController@create']); 
        Route::post('/recharge/store', ['as'=>'recharge', 'uses'=>'RechargeController@store']); 
    });
});
