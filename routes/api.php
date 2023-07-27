<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

Route::group(["middleware"=>['auth:sanctum']],function(){
    //user
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get("/user", [AuthController::class, "user"]);


    Route::get("/post",[PostController::class, "index"]);
    Route::post("/post",[PostController::class, "store"]);
    Route::get("/post/{id}",[PostController::class, "show"]);
    Route::put("/post/{id}",[PostController::class, "update"]);
    Route::delete("/post/{id}",[PostController::class, "destroy"]);


    Route::get("/post/{id}/comment",[CommentController::class, "index"]);
    Route::post("/post/{id}/comments",[CommentController::class, "store"]);
    //Route::get("/comment/{id}",[CommentController::class, "show"]);
    Route::put("/comment/{id}",[CommentController::class, "update"]);
    Route::delete("/comment/{id}",[CommentController::class, "destroy"]);


    Route::post("/posts/{id}/likes",[LikeController::class,"likeOrUnlike"]);



});
/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
