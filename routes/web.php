<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/collections', 'DiggingDeeperController@collections')->name('collections');




Route::group(['prefix' =>'digging_deeper'], function () {
    Route::get('collections', 'DiggingDeeperController@collections')
        ->name('digging_deeper.collections');

    Route::get('prepare-catalog','DiggingDeeperController@prepareCatalog')
        ->name('digging_deeper.prepareCatalog');
});

Route::group(['namespace' => 'Blog', 'prefix' => 'blog'], function () {
    Route::resource('posts', 'PostController')->names('blog.posts');
});

$groupData = [
    'namespace' => 'Blog\Admin',
    'prefix' => 'admin/blog',
];

Route::group($groupData, function () {
    $methods = ['index', 'edit', 'store', 'update', 'create',];
    Route::resource('categories', 'CategoryController')
        ->only($methods)
        ->names('blog.admin.categories');

    Route::resource('posts', 'PostController')
        ->except(['show'])
        ->names('blog.admin.posts');
});

//Route::resource('rest','RestTestController')->names('restTest');

