<?php

namespace App\Posts;

use App\Posts\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Posts\Kms\PostController as KmsPostController;

final class PostRoutes
{

    /**
     * Route that are resolved from the Alias route to Rest Route by the AliasMiddleware.
     * Through the controller the models will be bind by Implicit Route Model Binding,
     * so no need to add Route::model in here.
     */
    public static function web(){
        Route::resource('posts', PostController::class, [
            'only' => [
                'index',
                'show'
            ]
        ]);
    }

    /**
     * Routes that are used for and within the KMS
     * Will be prefixed with 'kms' by the Route::group in the RouteResolver
     *
     * Note: Within the group we use the Explicit Route Model Binding to point to a Post
     * This is because we use a global controller to resolve the model
     */
    public static function kms(){
        Route::model('post', Post::class); //Explicit route model binding
        Route::resource('posts', KmsPostController::class);
    }
}