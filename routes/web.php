<?php

use Illuminate\Support\Facades\Route;
use App\http\Controllers\UploadController;
// use App\http\Controllers\LoginController;
use App\Http\Controllers\LoginController;
use App\http\Controllers\RegisterController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/pond',function(){
    return view('uploadPond');
});
Route::get('/upload1',function(){
    return view('upload1');
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
Route::post('/upload-request', 'FileUploadController@initiateUploadRequest');
Route::get('/upload-status', 'FileUploadController@checkUploadStatus');
Route::post('/upload', 'FileUploadController@uploadFileChunk');
Route::post('/users/validate/upload', 'UploadController@validateUpload');
Route::post('/users/upload','UploadController@upload');
Route::post('/pond/upload_old','UploadController@uploadPond');
Route::post('/users/validate/download','DownloadController@validateDownload');
Route::get('/users/download','DownloadController@download');
Route::group(['middleware'=>'auth'],function(){
    Route::post('/logout',function(){
        // return view('login');
    });
    // Route::get('/login',function(){
    //     return view('login');
    // });
    Route::get('/register',function(){
        return view('register');
    });
    Route::get('/dashboard',function(){
        return view('dashboard');
    });
    // Route::post('/login-form','Login@Login');
    Route::post('/login-form','LoginController@Login');
    Route::post('/register-form','RegisterController@Register');
    Route::group(["prefix"=>"/user"],function(){
        Route::get('/pengaturan',function(){
            return view('pengaturan');
        });
    });
    Route::group(["prefix"=>"/api"],function(){
        Route::post("/logout",function(){
            // return view('dashboard');
        });
        Route::post("/weather","TestController@weather");
    });
});