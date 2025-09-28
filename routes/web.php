<?php
    use Core\Route;
    use App\Http\Controller\UserController;

    Route::get('/',[UserController::class,'index']);
    Route::get('/test',[UserController::class,'test']);
    Route::post('/post',[UserController::class,'post']);
?>