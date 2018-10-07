<?php

Route::get('/', 'SiteController@index')->name('index');

Route::get('/category/{category}', 'SiteController@category')->name('category');

Route::get('/search', 'SiteController@search')->name('search');

Route::get('{any}', 'SiteController@e404')->where('any', '.+');
