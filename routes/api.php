<?php
use Illuminate\Support\Facades\Route;
Route::post('/users/transaksi/midtrans/notify','Services\TransaksiController@midtransNotify');