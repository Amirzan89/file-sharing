<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
Route::group(['prefix'=>'/transaksi'], function(){
    Route::post('/midtrans/notify','Services\TransaksiController@createTransaksi');
    Route::post('/cancel','Services\TransaksiController@cancel');
    Route::post('/stop','Services\TransaksiController@stop');
});
Route::get('/login',function(Request $request){
    if($request->wantsJson()){
        return response()->json(['status' => 'success', 'message' => 'OK']);
    }
    return view('login');
});
Route::get('/register',function(Request $request){
    if($request->wantsJson()){
        return response()->json(['status' => 'success', 'message' => 'OK']);
    }
    return view('register');
});
Route::group(['prefix'=>'/upload'],function(){
    Route::post('/file','UploadController@uploadChunk');
    Route::post('/validate','UploadController@validation');
    Route::delete('/cancel','UploadController@cancelUpload');
    Route::delete('/delete','UploadController@deleteUpload');
});
Route::get('/', function () {
    return view('welcome');
});