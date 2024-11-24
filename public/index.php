<?php
    require '../vendor/autoload.php';
    require '../routes/web.php';

    use Core\App;
    use Core\Database;
    
    Database::getORM();
    App::run();


?>