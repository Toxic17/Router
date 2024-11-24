<?php
    use Core\Route;
    use App\Http\Controller\UserController;

    Route::get('/',[UserController::class,'index']);

?>