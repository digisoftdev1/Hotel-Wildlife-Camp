<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogimageController;
use App\Http\Controllers\Api\v1\CustomerMessageController;

/*
|--------------------------------------------------------------------------
| Public API V1 Routes — rate-limited, no auth required (read-only)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')
    ->middleware('throttle:api_public')
    ->name('api.v1.')
    ->group(function () {
        Route::get('/pages', [\App\Http\Controllers\Api\V1\PageController::class, 'index']);
        Route::get('/pages/{slug}', [\App\Http\Controllers\Api\V1\PageController::class, 'show']);
        Route::get('/blogs', [\App\Http\Controllers\Api\V1\BlogApiController::class, 'index']);
        Route::get('/blogs/{slug}', [\App\Http\Controllers\Api\V1\BlogApiController::class, 'show']);
        Route::get('/rooms', [\App\Http\Controllers\Api\V1\RoomApiController::class, 'index']);
        Route::get('/rooms/{slug}', [\App\Http\Controllers\Api\V1\RoomApiController::class, 'show']);
        Route::get('/packages', [\App\Http\Controllers\Api\V1\PackageApiController::class, 'index']);
        Route::get('/packages/{slug}', [\App\Http\Controllers\Api\V1\PackageApiController::class, 'show']);
        Route::get('/activities', [\App\Http\Controllers\Api\V1\ExperienceActivityApiController::class, 'index']);
        Route::get('/activities/{slug}', [\App\Http\Controllers\Api\V1\ExperienceActivityApiController::class, 'show']);
        Route::post('/customer-messages', [CustomerMessageController::class, 'store']);
    });

/*
|--------------------------------------------------------------------------
| Authenticated / internal routes
|--------------------------------------------------------------------------
*/
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'throttle:30,1'])->group(function () {
    Route::post('/upload-editor-image', [BlogimageController::class, 'uploadEditorImage'])->name('editor.image.upload');
    Route::post('/delete-editor-image', [BlogimageController::class, 'deleteImage'])->name('editor.image.delete');
});