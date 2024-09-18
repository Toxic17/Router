<?php
    use Core\Route;
    use App\Http\Controller\UserController;

    Route::post('/main/form/send',[UserController::class,'send'])->name('123');

    Route::get('/main/{posts}',[UserController::class,'go'])->name('123');

    Route::get('/main',[UserController::class,'index'])->name('444');

    Route::get('/test/1/',[UserController::class,'index_test'])->name('333');

    Route::get('/test/1/2/3/4/5',[UserController::class,'index_test_1'])->name('12333');

    Route::get('/test',[UserController::class,'index'])->name('343');

?>