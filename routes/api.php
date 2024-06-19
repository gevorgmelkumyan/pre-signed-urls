<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/files', [Controller::class, 'getFiles']);
Route::get('/presigned-url', [Controller::class, 'generatePreSignedUrl']);
Route::put('/files/{file}/update-file-status', [Controller::class, 'updateFileStatus']);
