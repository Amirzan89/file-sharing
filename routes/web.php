<?php
use Illuminate\Support\Facades\Route;
use App\http\Controllers\UploadController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/pond',function(){
    return view('uploadPond');
});
Route::get('/upload1',function(){
    return view('uploadForm');
});
Route::get('/upload1_old',function(){
    return view('upload1_old');
});
Route::get('/upload2',function(){
    return view('upload2');
});
Route::get('/login',function(){
    return view('login');
});
Route::group(['prefix'=>'/upload'],function(){
    Route::post('/validate','UploadController@validation');
    Route::post('/upload','UploadController@uploadChunk');
});